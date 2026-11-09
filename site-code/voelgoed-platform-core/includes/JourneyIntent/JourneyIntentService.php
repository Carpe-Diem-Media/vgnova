<?php
declare(strict_types=1);

namespace Voelgoed\PlatformCore\JourneyIntent;

defined('ABSPATH') || exit;

/** Applies the narrow VS1 one-intent lifecycle. */
final class JourneyIntentService
{
    public const STATUS_ACTIVE = 'ACTIVE';
    public const STATUS_SUPERSEDED = 'SUPERSEDED';
    public const STATUS_DISMISSED = 'DISMISSED';
    public const STATUS_COMPLETED = 'COMPLETED';
    public const STATUS_INVALID = 'INVALID';
    public const STATUS_EXPIRED = 'EXPIRED';

    private const DEFAULT_TTL_DAYS = 30;
    private const MAX_TTL_DAYS = 365;

    private JourneyIntentRepository $repository;

    private JourneyIntentLock $lock;

    private \Closure $clock;

    public function __construct(
        ?JourneyIntentRepository $repository = null,
        ?JourneyIntentLock $lock = null,
        ?callable $clock = null
    ) {
        $this->repository = $repository ?? new JourneyIntentRepository();
        $this->lock = $lock ?? new JourneyIntentLock();
        $this->clock = $clock !== null ? \Closure::fromCallable($clock) : static fn (): float => microtime(true);
    }

    /** Create or refresh an intent only when an explicit Course CTA is submitted. */
    public function start_course_intent(int $course_id): array|\WP_Error
    {
        $validated = $this->validated_course($course_id);
        if (is_wp_error($validated)) {
            return $validated;
        }

        $user_id = (int) get_current_user_id();
        if ($user_id > 0) {
            $promoted = $this->promote_guest_intent($user_id);
            if (is_wp_error($promoted)) {
                return $promoted;
            }

            $result = $this->lock->with_user_lock($user_id, function () use ($course_id): array|\WP_Error {
                $existing = $this->repository->user_intent((int) get_current_user_id());
                $intent = $this->new_or_refreshed_intent($existing, $course_id);
                return $this->repository->save_user_intent((int) get_current_user_id(), $intent)
                    ? $intent
                    : new \WP_Error('journey_intent_save_failed', 'Could not save Journey Intent. Try again.');
            });
            $this->repository->clear_guest_intent();
            return $result;
        }

        $existing = $this->current_guest_intent();
        if (is_wp_error($existing)) {
            $existing = null;
        }
        $intent = $this->new_or_refreshed_intent($existing, $course_id);
        if (! $this->repository->save_guest_intent($intent)) {
            return new \WP_Error('journey_intent_save_failed', 'Could not save Journey Intent. Try again.');
        }

        return $intent;
    }

    /** Read current state and persist expiry or target invalidation when observed. */
    public function current_intent(): array|null|\WP_Error
    {
        $user_id = (int) get_current_user_id();
        if ($user_id > 0) {
            $promoted = $this->promote_guest_intent($user_id);
            if (is_wp_error($promoted)) {
                return $promoted;
            }
            $intent = $this->repository->user_intent($user_id);
        } else {
            $intent = $this->repository->guest_intent();
        }

        return $this->refresh_lifecycle_state($intent, $user_id);
    }

    /** Persist No Thanks as DISMISSED; a later explicit Start creates a new intent. */
    public function dismiss_current_intent(string $expected_intent_id): array|null|\WP_Error
    {
        if (! preg_match('/^[a-f0-9]{32}$/D', $expected_intent_id)) {
            return new \WP_Error('journey_intent_stale_dismissal', 'Reload the page before dismissing this reminder.');
        }

        $user_id = (int) get_current_user_id();
        if ($user_id > 0) {
            $result = $this->lock->with_user_lock($user_id, function () use ($user_id, $expected_intent_id): array|null|\WP_Error {
                $current = $this->refresh_record(
                    $this->repository->user_intent($user_id),
                    fn (array $intent): bool => $this->repository->save_user_intent($user_id, $intent)
                );
                if (
                    $current === null
                    || is_wp_error($current)
                    || $current['intent_id'] !== $expected_intent_id
                    || $current['status'] !== self::STATUS_ACTIVE
                ) {
                    return $current;
                }

                $current['status'] = self::STATUS_DISMISSED;
                $current['updated_at'] = ($this->clock)();
                return $this->repository->save_user_intent($user_id, $current)
                    ? $current
                    : new \WP_Error('journey_intent_save_failed', 'Could not save the dismissal. Try again.');
            });
            $this->repository->clear_guest_intent();
            return $result;
        }

        $current = $this->current_guest_intent();
        if (
            $current === null
            || is_wp_error($current)
            || $current['intent_id'] !== $expected_intent_id
            || $current['status'] !== self::STATUS_ACTIVE
        ) {
            return $current;
        }
        $current['status'] = self::STATUS_DISMISSED;
        $current['updated_at'] = ($this->clock)();
        return $this->repository->save_guest_intent($current)
            ? $current
            : new \WP_Error('journey_intent_save_failed', 'Could not save the dismissal. Try again.');
    }

    /** Resolve a Course URL from stored identity, never from request parameters. */
    public function return_destination(): string|\WP_Error
    {
        $intent = $this->current_intent();
        if ($intent === null || is_wp_error($intent) || $intent['status'] !== self::STATUS_ACTIVE) {
            return new \WP_Error('journey_intent_not_active', 'There is no active Course return to resolve.');
        }

        $validated = $this->validated_course((int) $intent['target_id']);
        if (is_wp_error($validated)) {
            $this->mark_terminal_if_current($intent, self::STATUS_INVALID);
            return $validated;
        }

        return $validated['url'];
    }

    /** Promote the newest valid guest state once, under a per-user lock. */
    public function promote_guest_intent(int $user_id): array|null|\WP_Error
    {
        if ($user_id <= 0) {
            return new \WP_Error('journey_intent_invalid_user', 'A valid account is required for intent promotion.');
        }

        $guest = $this->repository->guest_intent();
        if ($guest === null) {
            return $this->repository->user_intent($user_id);
        }

        if ((int) $guest['expires_at'] <= (int) floor(($this->clock)())) {
            $this->repository->clear_guest_intent();
            return $this->repository->user_intent($user_id);
        }

        if ($guest['status'] === self::STATUS_ACTIVE && is_wp_error($this->validated_course((int) $guest['target_id']))) {
            $this->repository->clear_guest_intent();
            return $this->repository->user_intent($user_id);
        }

        $result = $this->lock->with_user_lock($user_id, function () use ($user_id, $guest): array|\WP_Error {
            $existing = $this->repository->user_intent($user_id);
            if (
                $existing !== null
                && (
                    $existing['intent_id'] === $guest['intent_id']
                    || (float) $existing['updated_at'] >= (float) $guest['updated_at']
                )
            ) {
                return $existing;
            }

            return $this->repository->save_user_intent($user_id, $guest)
                ? $guest
                : new \WP_Error('journey_intent_promotion_failed', 'Could not carry the Course intent into the account.');
        });

        if (! is_wp_error($result)) {
            $this->repository->clear_guest_intent();
        }
        return $result;
    }

    /** WordPress login hook adapter. */
    public function promote_after_login(string $user_login, object $user): void
    {
        unset($user_login);
        $user_id = (int) ($user->ID ?? 0);
        if ($user_id > 0) {
            $this->promote_guest_intent($user_id);
        }
    }

    private function current_guest_intent(): array|null|\WP_Error
    {
        return $this->refresh_lifecycle_state($this->repository->guest_intent(), 0);
    }

    /** @param array<string, mixed>|null $intent @return array<string, mixed>|null|\WP_Error */
    private function refresh_lifecycle_state(?array $intent, int $user_id): array|null|\WP_Error
    {
        if ($user_id > 0) {
            $result = $this->lock->with_user_lock($user_id, function () use ($user_id): array|null|\WP_Error {
                $current = $this->repository->user_intent($user_id);
                return $this->refresh_record(
                    $current,
                    fn (array $record): bool => $this->repository->save_user_intent($user_id, $record)
                );
            });
            $this->repository->clear_guest_intent();
            return $result;
        }

        return $this->refresh_record($intent, fn (array $record): bool => $this->repository->save_guest_intent($record));
    }

    /** @param array<string, mixed>|null $intent @param callable(array<string, mixed>):bool $save @return array<string, mixed>|null|\WP_Error */
    private function refresh_record(?array $intent, callable $save): array|null|\WP_Error
    {
        if ($intent === null || $intent['status'] !== self::STATUS_ACTIVE) {
            return $intent;
        }

        $now = ($this->clock)();
        if ((int) $intent['expires_at'] <= (int) floor($now)) {
            $intent['status'] = self::STATUS_EXPIRED;
        } elseif (is_wp_error($this->validated_course((int) $intent['target_id']))) {
            $intent['status'] = self::STATUS_INVALID;
        } else {
            return $intent;
        }

        $intent['updated_at'] = $now;
        if (! $save($intent)) {
            return new \WP_Error('journey_intent_state_save_failed', 'Could not update Journey Intent state.');
        }

        return $intent;
    }

    /** @param array<string, mixed>|null $existing @return array<string, mixed> */
    private function new_or_refreshed_intent(?array $existing, int $course_id): array
    {
        $now = ($this->clock)();
        $same_active = $existing !== null
            && $existing['status'] === self::STATUS_ACTIVE
            && (int) $existing['target_id'] === $course_id
            && (int) $existing['expires_at'] > (int) floor($now);

        try {
            $intent_id = $same_active ? $existing['intent_id'] : bin2hex(random_bytes(16));
        } catch (\Throwable) {
            $intent_id = '';
        }

        return [
            'version' => 1,
            'intent_id' => $intent_id,
            'target_type' => 'mto-course',
            'target_id' => $course_id,
            'source_type' => 'course_membership_cta',
            'source_id' => $course_id,
            'created_at' => $same_active ? (float) $existing['created_at'] : $now,
            'updated_at' => $now,
            'expires_at' => (int) floor($now) + ($this->ttl_days() * DAY_IN_SECONDS),
            'status' => self::STATUS_ACTIVE,
        ];
    }

    private function ttl_days(): int
    {
        $configured = apply_filters('vgpc_journey_intent_ttl_days', self::DEFAULT_TTL_DAYS);
        if (is_string($configured) && ctype_digit($configured)) {
            $configured = (int) $configured;
        }

        if (! is_int($configured) || $configured < 1 || $configured > self::MAX_TTL_DAYS) {
            return self::DEFAULT_TTL_DAYS;
        }

        return $configured;
    }

    /** @return array{url:string}|\WP_Error */
    private function validated_course(int $course_id): array|\WP_Error
    {
        if ($course_id <= 0) {
            return new \WP_Error('journey_intent_invalid_course', 'Choose a valid published Course.');
        }

        $post = get_post($course_id);
        if (
            ! is_object($post)
            || (string) ($post->post_type ?? '') !== 'mto-course'
            || (string) ($post->post_status ?? '') !== 'publish'
            || (string) ($post->post_password ?? '') !== ''
            || ! function_exists('is_post_publicly_viewable')
            || ! is_post_publicly_viewable($post)
        ) {
            return new \WP_Error('journey_intent_invalid_course', 'Choose a valid published Course.');
        }

        $url = get_permalink($post);
        if (! is_string($url) || $url === '' || ! function_exists('wp_validate_redirect')) {
            return new \WP_Error('journey_intent_invalid_destination', 'The Course does not have a safe public destination.');
        }

        $home = home_url('/');
        $validated = wp_validate_redirect($url, $home);
        $destination_parts = parse_url($url);
        $home_parts = parse_url($home);
        if (
            $validated !== $url
            || ! is_array($destination_parts)
            || ! is_array($home_parts)
            || strtolower((string) ($destination_parts['scheme'] ?? '')) !== strtolower((string) ($home_parts['scheme'] ?? ''))
            || strtolower((string) ($destination_parts['host'] ?? '')) !== strtolower((string) ($home_parts['host'] ?? ''))
            || $this->effective_port($destination_parts) !== $this->effective_port($home_parts)
            || isset($destination_parts['user'])
            || isset($destination_parts['pass'])
        ) {
            return new \WP_Error('journey_intent_invalid_destination', 'The Course does not have a safe public destination.');
        }

        return ['url' => $url];
    }

    /** @param array<string, mixed> $parts */
    private function effective_port(array $parts): int
    {
        if (isset($parts['port'])) {
            return (int) $parts['port'];
        }

        return strtolower((string) ($parts['scheme'] ?? '')) === 'https' ? 443 : 80;
    }

    /** @param array<string, mixed> $intent */
    private function mark_terminal_if_current(array $intent, string $status): bool
    {
        $expected_intent_id = $intent['intent_id'];
        $expected_updated_at = (float) $intent['updated_at'];
        $intent['status'] = $status;
        $intent['updated_at'] = ($this->clock)();
        $user_id = (int) get_current_user_id();
        if ($user_id > 0) {
            $result = $this->lock->with_user_lock($user_id, function () use ($intent, $user_id, $expected_intent_id, $expected_updated_at): bool {
                $current = $this->repository->user_intent($user_id);
                if (
                    $current === null
                    || $current['intent_id'] !== $expected_intent_id
                    || (float) $current['updated_at'] !== $expected_updated_at
                ) {
                    return false;
                }
                return $this->repository->save_user_intent($user_id, $intent);
            });
            $this->repository->clear_guest_intent();
            return $result === true;
        }

        $current = $this->repository->guest_intent();
        if (
            $current === null
            || $current['intent_id'] !== $expected_intent_id
            || (float) $current['updated_at'] !== $expected_updated_at
        ) {
            return false;
        }
        return $this->repository->save_guest_intent($intent);
    }
}
