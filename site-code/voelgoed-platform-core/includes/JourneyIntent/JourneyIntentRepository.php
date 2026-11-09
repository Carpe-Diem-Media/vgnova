<?php
declare(strict_types=1);

namespace Voelgoed\PlatformCore\JourneyIntent;

defined('ABSPATH') || exit;

/** Persists the one current intent for a guest cookie or authenticated account. */
final class JourneyIntentRepository
{
    public const USER_META_KEY = '_vgpc_journey_intent_v1';

    private JourneyIntentCookie $cookie;

    public function __construct(?JourneyIntentCookie $cookie = null)
    {
        $this->cookie = $cookie ?? new JourneyIntentCookie();
    }

    /** @return array<string, mixed>|null */
    public function guest_intent(): ?array
    {
        return $this->normalize($this->cookie->read());
    }

    /** @param array<string, mixed> $intent */
    public function save_guest_intent(array $intent): bool
    {
        $intent = $this->normalize($intent);
        return $intent !== null && $this->cookie->write($intent);
    }

    public function clear_guest_intent(): bool
    {
        return $this->cookie->clear();
    }

    /** @return array<string, mixed>|null */
    public function user_intent(int $user_id): ?array
    {
        if ($user_id <= 0) {
            return null;
        }

        $intent = get_user_meta($user_id, self::USER_META_KEY, true);
        return $this->normalize(is_array($intent) ? $intent : null);
    }

    /** @param array<string, mixed> $intent */
    public function save_user_intent(int $user_id, array $intent): bool
    {
        if ($user_id <= 0 || ($intent = $this->normalize($intent)) === null) {
            return false;
        }

        try {
            update_user_meta($user_id, self::USER_META_KEY, $intent);
        } catch (\Throwable) {
            return false;
        }

        return $this->user_intent($user_id) === $intent;
    }

    /** @param array<string, mixed>|null $intent @return array<string, mixed>|null */
    private function normalize(?array $intent): ?array
    {
        if ($intent === null) {
            return null;
        }

        $statuses = [
            JourneyIntentService::STATUS_ACTIVE,
            JourneyIntentService::STATUS_SUPERSEDED,
            JourneyIntentService::STATUS_DISMISSED,
            JourneyIntentService::STATUS_COMPLETED,
            JourneyIntentService::STATUS_INVALID,
            JourneyIntentService::STATUS_EXPIRED,
        ];
        $id = $intent['intent_id'] ?? null;
        $target_id = $intent['target_id'] ?? null;
        $source_id = $intent['source_id'] ?? null;
        $created_at = $intent['created_at'] ?? null;
        $updated_at = $intent['updated_at'] ?? null;
        $expires_at = $intent['expires_at'] ?? null;
        $status = $intent['status'] ?? null;

        if (
            ($intent['version'] ?? null) !== 1
            || ! is_string($id)
            || ! preg_match('/^[a-f0-9]{32}$/D', $id)
            || ($intent['target_type'] ?? null) !== 'mto-course'
            || ! is_int($target_id)
            || $target_id <= 0
            || ($intent['source_type'] ?? null) !== 'course_membership_cta'
            || ! is_int($source_id)
            || $source_id !== $target_id
            || (! is_int($created_at) && ! is_float($created_at))
            || (! is_int($updated_at) && ! is_float($updated_at))
            || ! is_int($expires_at)
            || ! is_string($status)
            || ! in_array($status, $statuses, true)
        ) {
            return null;
        }

        // Allowlist persisted fields so a supplied return_to, price, or other
        // browser value can never become Journey Intent authority.
        return [
            'version' => 1,
            'intent_id' => $id,
            'target_type' => 'mto-course',
            'target_id' => $target_id,
            'source_type' => 'course_membership_cta',
            'source_id' => $source_id,
            'created_at' => (float) $created_at,
            'updated_at' => (float) $updated_at,
            'expires_at' => $expires_at,
            'status' => $status,
        ];
    }
}
