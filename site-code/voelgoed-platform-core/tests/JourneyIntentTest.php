<?php
declare(strict_types=1);

use Voelgoed\PlatformCore\JourneyIntent\JourneyIntentBricks;
use Voelgoed\PlatformCore\JourneyIntent\JourneyIntentCookie;
use Voelgoed\PlatformCore\JourneyIntent\JourneyIntentLock;
use Voelgoed\PlatformCore\JourneyIntent\JourneyIntentRepository;
use Voelgoed\PlatformCore\JourneyIntent\JourneyIntentService;

if (! defined('DAY_IN_SECONDS')) {
    define('DAY_IN_SECONDS', 86400);
}

if (! function_exists('get_user_meta')) {
    function get_user_meta(int $user_id, string $key, bool $single = false): mixed
    {
        $value = $GLOBALS['vgji_test_user_meta'][$user_id][$key] ?? '';
        return $single ? $value : ($value === '' ? [] : [$value]);
    }
}

if (! function_exists('update_user_meta')) {
    function update_user_meta(int $user_id, string $key, mixed $value, mixed $previous = ''): int|bool
    {
        unset($previous);
        if (! empty($GLOBALS['vgji_test_fail_user_meta_write'])) {
            return false;
        }
        if (($GLOBALS['vgji_test_user_meta'][$user_id][$key] ?? null) === $value) {
            return false;
        }
        $GLOBALS['vgji_test_user_meta'][$user_id][$key] = $value;
        return 1;
    }
}

if (! function_exists('delete_user_meta')) {
    function delete_user_meta(int $user_id, string $key): bool
    {
        unset($GLOBALS['vgji_test_user_meta'][$user_id][$key]);
        return true;
    }
}

if (! function_exists('wp_salt')) {
    function wp_salt(string $scheme = 'auth'): string
    {
        return 'vgji-test-secret-' . $scheme;
    }
}

if (! function_exists('wp_json_encode')) {
    function wp_json_encode(mixed $data, int $flags = 0, int $depth = 512): string|false
    {
        return json_encode($data, $flags, $depth);
    }
}

if (! function_exists('is_ssl')) {
    function is_ssl(): bool
    {
        return (bool) ($GLOBALS['vgji_test_ssl'] ?? true);
    }
}

if (! function_exists('is_post_publicly_viewable')) {
    function is_post_publicly_viewable(object $post): bool
    {
        return ($post->post_status ?? '') === 'publish'
            && ($post->publicly_viewable ?? true) === true;
    }
}

if (! function_exists('get_permalink')) {
    function get_permalink(int|object $post): string|false
    {
        $post = is_object($post) ? $post : get_post($post);
        return is_object($post)
            ? (string) ($post->permalink ?? 'https://voelgoed.test/leer/course-' . (int) $post->ID . '/')
            : false;
    }
}

if (! function_exists('home_url')) {
    function home_url(string $path = ''): string
    {
        return 'https://voelgoed.test' . '/' . ltrim($path, '/');
    }
}

if (! function_exists('admin_url')) {
    function admin_url(string $path = ''): string
    {
        return 'https://voelgoed.test/wp-admin/' . ltrim($path, '/');
    }
}

if (! function_exists('add_query_arg')) {
    function add_query_arg(array|string $args, ?string $value = null, ?string $url = null): string
    {
        if (is_string($args)) {
            $args = [$args => $value ?? ''];
        }
        $url ??= '';
        $query = http_build_query(array_merge($_GET, $args));
        return strtok($url, '?') . ($query !== '' ? '?' . $query : '');
    }
}

if (! function_exists('wp_validate_redirect')) {
    function wp_validate_redirect(string $location, string $fallback = ''): string
    {
        $destination = parse_url($location);
        $home = parse_url(home_url('/'));
        if (
            ! is_array($destination)
            || ! is_array($home)
            || strtolower((string) ($destination['host'] ?? '')) !== strtolower((string) ($home['host'] ?? ''))
            || strtolower((string) ($destination['scheme'] ?? '')) !== strtolower((string) ($home['scheme'] ?? ''))
        ) {
            return $fallback;
        }
        return $location;
    }
}

if (! function_exists('wp_create_nonce')) {
    function wp_create_nonce(string $action = '-1'): string
    {
        return 'nonce-' . $action;
    }
}

if (! function_exists('wp_verify_nonce')) {
    function wp_verify_nonce(string $nonce, string $action = '-1'): int|false
    {
        return hash_equals('nonce-' . $action, $nonce) ? 1 : false;
    }
}

if (! function_exists('esc_url')) {
    function esc_url(string $url): string
    {
        return htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
    }
}

if (! function_exists('esc_attr')) {
    function esc_attr(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}

if (! function_exists('esc_html')) {
    function esc_html(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}

if (! function_exists('nocache_headers')) {
    function nocache_headers(): void
    {
        $GLOBALS['vgji_test_nocache_calls'] = (int) ($GLOBALS['vgji_test_nocache_calls'] ?? 0) + 1;
    }
}

if (! function_exists('wp_safe_redirect')) {
    function wp_safe_redirect(string $location, int $status = 302, string $x_redirect_by = 'WordPress'): bool
    {
        $GLOBALS['vgji_test_redirects'][] = [$location, $status, $x_redirect_by];
        return true;
    }
}

if (! function_exists('sanitize_text_field')) {
    function sanitize_text_field(string $value): string
    {
        return trim(strip_tags($value));
    }
}

final class VGJI_TestBricksForm
{
    public array $result = [];

    public function __construct(private array $fields)
    {
    }

    public function get_fields(): array
    {
        return $this->fields;
    }

    public function set_result(array $result): void
    {
        $this->result = $result;
    }
}

final class JourneyIntentTest
{
    public function test_course_view_alone_does_not_create_an_intent_or_cookie(): void
    {
        $this->reset();
        $GLOBALS['vgpc_test_current_user_id'] = 0;
        $service = $this->service();
        (new JourneyIntentBricks($service))->register();

        vgpc_test_same(null, $service->current_intent(), 'A Course view with no explicit action must not create intent.');
        vgpc_test_same(null, $GLOBALS['vgji_test_cookie_value'], 'A passive read must not set an intent cookie.');
        vgpc_test_same([], $GLOBALS['vgji_test_user_meta'], 'A passive read must not persist user metadata.');
        vgpc_test_true(! isset($GLOBALS['vgpc_test_actions']['template_redirect']), 'No passive frontend-view hook may create intent.');
    }

    public function test_only_published_public_masteriyo_courses_can_start(): void
    {
        $this->reset();
        $service = $this->service();
        $this->course(501, 'draft');
        $this->course(502, 'publish', 'post');
        $this->course(503, 'publish', 'mto-course', true);
        $this->course(504, 'publish', 'mto-course', false, 'private');

        foreach ([501, 502, 503, 504] as $course_id) {
            $result = $service->start_course_intent($course_id);
            vgpc_test_true(is_wp_error($result), 'An unpublished, wrong-type, non-public, or password-protected Course must be rejected.');
        }
        vgpc_test_same(null, $GLOBALS['vgji_test_cookie_value'], 'Rejected Course IDs must not create an intent cookie.');
    }

    public function test_explicit_start_creates_minimal_active_intent_and_server_derived_target(): void
    {
        $this->reset();
        $this->course(510);
        $service = $this->service();

        $intent = $service->start_course_intent(510);

        vgpc_test_true(is_array($intent), 'A valid explicit Course action must create an intent.');
        vgpc_test_same(JourneyIntentService::STATUS_ACTIVE, $intent['status'], 'The new intent must be ACTIVE.');
        vgpc_test_same('mto-course', $intent['target_type'], 'The Course post type must be the target authority.');
        vgpc_test_same(510, $intent['target_id'], 'The target Course ID must be stored.');
        vgpc_test_same('course_membership_cta', $intent['source_type'], 'The explicit source action must be recorded.');
        vgpc_test_same(510, $intent['source_id'], 'The Course context must be recorded as the source ID.');
        vgpc_test_true(! array_key_exists('return_to', $intent), 'No browser-supplied destination may be stored.');
        vgpc_test_true(! array_key_exists('price', $intent) && ! array_key_exists('membership_level', $intent), 'Commercial values must not be persisted.');
        vgpc_test_same('https://voelgoed.test/leer/course-510/', $service->return_destination(), 'Return URL must come from the validated Course permalink.');
    }

    public function test_repeated_same_course_action_is_idempotent_and_refreshes_expiry(): void
    {
        $this->reset();
        $GLOBALS['vgpc_test_current_user_id'] = 0;
        $this->course(520);
        $service = $this->service();

        $first = $service->start_course_intent(520);
        $GLOBALS['vgji_test_now'] += 60;
        $second = $service->start_course_intent(520);

        vgpc_test_same($first['intent_id'], $second['intent_id'], 'Repeated explicit action for the same Course must not create duplicate intent identity.');
        vgpc_test_same($first['created_at'], $second['created_at'], 'Refreshing the same intent must preserve its creation time.');
        vgpc_test_true($second['expires_at'] > $first['expires_at'], 'A repeated explicit action must refresh expiry.');
    }

    public function test_new_explicit_course_replaces_previous_active_intent(): void
    {
        $this->reset();
        $GLOBALS['vgpc_test_current_user_id'] = 0;
        $this->course(530);
        $this->course(531);
        $service = $this->service();

        $first = $service->start_course_intent(530);
        $second = $service->start_course_intent(531);

        vgpc_test_true($first['intent_id'] !== $second['intent_id'], 'A different Course action must supersede the former intent.');
        vgpc_test_same(531, $service->current_intent()['target_id'], 'Only the newer Course intent may remain current.');
        vgpc_test_same(JourneyIntentService::STATUS_ACTIVE, $service->current_intent()['status'], 'The replacement must be ACTIVE.');
    }

    public function test_dismissal_is_persistent_idempotent_and_requires_a_new_explicit_action_to_reactivate(): void
    {
        $this->reset();
        $GLOBALS['vgpc_test_current_user_id'] = 0;
        $this->course(540);
        $service = $this->service();
        $active = $service->start_course_intent(540);

        $dismissed = $service->dismiss_current_intent($active['intent_id']);
        $repeated = $service->dismiss_current_intent($active['intent_id']);

        vgpc_test_same(JourneyIntentService::STATUS_DISMISSED, $dismissed['status'], 'No Thanks must persist DISMISSED.');
        vgpc_test_same($dismissed, $repeated, 'Repeated dismissal must be idempotent.');
        vgpc_test_true(is_wp_error($service->return_destination()), 'Dismissed intent must not resolve to a return destination.');
        vgpc_test_same(JourneyIntentService::STATUS_DISMISSED, $service->current_intent()['status'], 'Return attempts must not resurrect a dismissed reminder.');

        $new = $service->start_course_intent(540);
        vgpc_test_true($new['intent_id'] !== $active['intent_id'], 'A later explicit action may begin a new intent.');
        vgpc_test_same(JourneyIntentService::STATUS_ACTIVE, $new['status'], 'Only the explicit new action may reactivate the journey.');
    }

    public function test_expiry_boundary_is_expired_and_never_redirects(): void
    {
        $this->reset();
        $GLOBALS['vgpc_test_current_user_id'] = 0;
        $this->course(550);
        $service = $this->service();
        $intent = $service->start_course_intent(550);
        vgpc_test_true(
            $GLOBALS['vgji_test_cookie_options']['expires'] > $intent['expires_at'],
            'The guest cookie must remain long enough to persist the logical EXPIRED state on a later request.'
        );
        $GLOBALS['vgji_test_now'] = (float) $intent['expires_at'];

        vgpc_test_true(is_wp_error($service->return_destination()), 'An intent at its expiry boundary must not redirect.');
        vgpc_test_same(JourneyIntentService::STATUS_EXPIRED, $service->current_intent()['status'], 'Expiry boundary must persist EXPIRED.');
        vgpc_test_true(
            $GLOBALS['vgji_test_cookie_options']['expires'] > $intent['expires_at'],
            'The expired guest tombstone must remain available briefly after its logical expiry.'
        );
        vgpc_test_same(
            JourneyIntentService::STATUS_EXPIRED,
            $service->current_intent()['status'],
            'A follow-up request must read the persisted EXPIRED tombstone.'
        );
    }

    public function test_ttl_defaults_to_thirty_days_and_can_be_configured(): void
    {
        $this->reset();
        $this->course(560);
        $service = $this->service();
        $default = $service->start_course_intent(560);
        vgpc_test_same(30 * DAY_IN_SECONDS, $default['expires_at'] - (int) floor($default['created_at']), 'Default expiry must be thirty days.');

        $this->reset();
        add_filter('vgpc_journey_intent_ttl_days', static fn (int $days): int => 7);
        $this->course(560);
        $configured = $this->service()->start_course_intent(560);
        vgpc_test_same(7 * DAY_IN_SECONDS, $configured['expires_at'] - (int) floor($configured['created_at']), 'The TTL filter must configure expiry.');
    }

    public function test_missing_or_unpublished_course_becomes_invalid_without_redirect(): void
    {
        $this->reset();
        $this->course(570);
        $service = $this->service();
        $service->start_course_intent(570);
        $GLOBALS['vgpc_test_posts'][570]->post_status = 'draft';

        vgpc_test_true(is_wp_error($service->return_destination()), 'An unpublished source Course must not be a return destination.');
        vgpc_test_same(JourneyIntentService::STATUS_INVALID, $service->current_intent()['status'], 'The stored intent must become INVALID.');
    }

    public function test_return_rejects_unsafe_permalink_and_does_not_complete(): void
    {
        $this->reset();
        $this->course(580);
        $service = $this->service();
        $service->start_course_intent(580);
        $GLOBALS['vgpc_test_posts'][580]->permalink = 'https://attacker.example/return';

        vgpc_test_true(is_wp_error($service->return_destination()), 'A Course permalink outside the local site must be rejected.');
        vgpc_test_same(JourneyIntentService::STATUS_INVALID, $service->current_intent()['status'], 'Unsafe destination must invalidate the intent.');
        vgpc_test_true(! method_exists($service, 'complete_current_intent'), 'VGN-5A must not expose a browser-callable completion transition.');
    }

    public function test_signed_cookie_is_tamper_resistant_and_uses_safe_flags(): void
    {
        $this->reset();
        $GLOBALS['vgpc_test_current_user_id'] = 0;
        $this->course(590);
        $service = $this->service();
        $service->start_course_intent(590);

        $cookie = $GLOBALS['vgji_test_cookie_value'];
        $options = $GLOBALS['vgji_test_cookie_options'];
        vgpc_test_same(true, $options['httponly'], 'Intent cookie must be HttpOnly.');
        vgpc_test_same('Lax', $options['samesite'], 'Intent cookie must use SameSite=Lax.');
        vgpc_test_same(true, $options['secure'], 'Intent cookie must be Secure over HTTPS.');

        $GLOBALS['vgji_test_cookie_value'] = $cookie . 'tampered';
        vgpc_test_same(null, $this->service()->current_intent(), 'A modified signed cookie must fail closed.');
    }

    public function test_anonymous_intent_promotes_once_under_user_lock_and_survives_cookie_removal(): void
    {
        $this->reset();
        $GLOBALS['vgpc_test_current_user_id'] = 0;
        $this->course(600);
        $service = $this->service();
        $guest = $service->start_course_intent(600);
        $GLOBALS['vgpc_test_current_user_id'] = 44;

        $promoted = $service->promote_guest_intent(44);
        $repeated = $service->promote_guest_intent(44);

        vgpc_test_same($guest['intent_id'], $promoted['intent_id'], 'Login must preserve the anonymous intent ID.');
        vgpc_test_same($promoted, $repeated, 'Repeated login/promotion must not create duplicate or changed intent.');
        vgpc_test_same(null, $GLOBALS['vgji_test_cookie_value'], 'Promotion must clear the guest cookie.');
        $GLOBALS['vgpc_test_current_user_id'] = 0;
        vgpc_test_same(null, $service->current_intent(), 'The promoted user intent must not be read as an anonymous cookie.');
        $GLOBALS['vgpc_test_current_user_id'] = 44;
        vgpc_test_same($guest['intent_id'], $service->current_intent()['intent_id'], 'Authenticated user meta must retain intent after browser-cookie removal.');

        $lock_calls = array_values(array_filter($GLOBALS['wpdb']->queries, static fn (array $query): bool => str_contains($query['query'], 'GET_LOCK')));
        vgpc_test_true(count($lock_calls) >= 1, 'Promotion must be serialized by a user-scoped lock.');
    }

    public function test_newer_user_dismissal_wins_over_stale_guest_cookie(): void
    {
        $this->reset();
        $GLOBALS['vgpc_test_current_user_id'] = 0;
        $this->course(610);
        $service = $this->service();
        $guest = $service->start_course_intent(610);
        $GLOBALS['vgpc_test_current_user_id'] = 45;
        $service->promote_guest_intent(45);
        $user_dismissed = $service->dismiss_current_intent($guest['intent_id']);

        $GLOBALS['vgji_test_cookie_value'] = $this->encode_cookie($guest);
        $stale_promotion = $service->promote_guest_intent(45);

        vgpc_test_same(JourneyIntentService::STATUS_DISMISSED, $stale_promotion['status'], 'A stale ACTIVE cookie must not overwrite newer user dismissal.');
        vgpc_test_same($user_dismissed['updated_at'], $stale_promotion['updated_at'], 'The newer user state timestamp must be preserved.');
    }

    public function test_authenticated_dismissal_promotes_guest_intent_without_login_callback(): void
    {
        $this->reset();
        $GLOBALS['vgpc_test_current_user_id'] = 0;
        $this->course(615);
        $service = $this->service();
        $guest = $service->start_course_intent(615);

        // Simulate a restored authenticated session without wp_login firing.
        $GLOBALS['vgpc_test_current_user_id'] = 46;
        $dismissed = $service->dismiss_current_intent($guest['intent_id']);

        vgpc_test_true(is_array($dismissed), 'Authenticated dismissal must recover the anonymous state before applying the action.');
        vgpc_test_same(JourneyIntentService::STATUS_DISMISSED, $dismissed['status'], 'The promoted intent must be durably dismissed.');
        vgpc_test_same(
            JourneyIntentService::STATUS_DISMISSED,
            $GLOBALS['vgji_test_user_meta'][46][JourneyIntentRepository::USER_META_KEY]['status'] ?? null,
            'The account must retain DISMISSED after the guest cookie is removed.'
        );
        vgpc_test_same(null, $GLOBALS['vgji_test_cookie_value'], 'The guest cookie may be cleared after account persistence.');
    }

    public function test_authenticated_dismissal_preserves_guest_cookie_when_promotion_storage_fails(): void
    {
        $this->reset();
        $GLOBALS['vgpc_test_current_user_id'] = 0;
        $this->course(616);
        $service = $this->service();
        $guest = $service->start_course_intent(616);
        $cookie = $GLOBALS['vgji_test_cookie_value'];
        $GLOBALS['vgpc_test_current_user_id'] = 47;
        $GLOBALS['vgji_test_fail_user_meta_write'] = true;

        $result = $service->dismiss_current_intent($guest['intent_id']);

        vgpc_test_true(is_wp_error($result), 'Storage failure must reject dismissal rather than report success.');
        vgpc_test_same('journey_intent_promotion_failed', $result->get_error_code(), 'Promotion failure must be distinguishable from a stale form.');
        vgpc_test_same($cookie, $GLOBALS['vgji_test_cookie_value'], 'Failed persistence must leave the only guest copy intact.');
        vgpc_test_same([], $GLOBALS['vgji_test_user_meta'], 'Failed promotion must not claim the intent was saved to the account.');
    }

    public function test_authenticated_dismissal_preserves_guest_cookie_when_promotion_lock_fails(): void
    {
        $this->reset();
        $GLOBALS['vgpc_test_current_user_id'] = 0;
        $this->course(617);
        $service = $this->service();
        $guest = $service->start_course_intent(617);
        $cookie = $GLOBALS['vgji_test_cookie_value'];
        $GLOBALS['vgpc_test_current_user_id'] = 48;
        $GLOBALS['wpdb']->get_var_handler = static fn (string $query): string => str_contains($query, 'GET_LOCK') ? '0' : '1';

        $result = $service->dismiss_current_intent($guest['intent_id']);

        vgpc_test_true(is_wp_error($result), 'Lock failure must reject dismissal without losing state.');
        vgpc_test_same('journey_intent_lock_unavailable', $result->get_error_code(), 'Lock failure must remain visible to the caller.');
        vgpc_test_same($cookie, $GLOBALS['vgji_test_cookie_value'], 'A failed lock must leave the only guest copy intact.');
        vgpc_test_same([], $GLOBALS['vgji_test_user_meta'], 'No account transition may occur without the user lock.');
    }

    public function test_stale_dismissal_bricks_form_returns_error_and_preserves_replacement(): void
    {
        $this->reset();
        $GLOBALS['vgpc_test_current_user_id'] = 0;
        $this->course(640);
        $this->course(641);
        $service = $this->service();
        $old = $service->start_course_intent(640);
        $new = $service->start_course_intent(641);
        $bricks = new JourneyIntentBricks($service);
        $_SERVER['HTTP_ORIGIN'] = 'https://voelgoed.test';
        $dismiss = new VGJI_TestBricksForm([
            'form-field-vgpc_journey_intent_action' => 'dismiss',
            'form-field-vgpc_journey_intent_id' => $old['intent_id'],
            'form-field-vgpc_journey_intent_nonce' => 'nonce-vgpc_journey_intent',
        ]);

        $bricks->handle_form_action($dismiss);

        vgpc_test_same('danger', $dismiss->result['type'], 'A stale No Thanks submission must return a form error.');
        vgpc_test_true(! isset($dismiss->result['redirectTo']), 'A stale dismissal must not be treated as success or redirect.');
        vgpc_test_same($new['intent_id'], $service->current_intent()['intent_id'], 'A stale form must preserve the newer active intent.');
        vgpc_test_same(JourneyIntentService::STATUS_ACTIVE, $service->current_intent()['status'], 'The replacement must remain active after stale submission.');
    }

    public function test_current_no_thanks_form_dismisses_in_place_without_membership_redirect(): void
    {
        $this->reset();
        $GLOBALS['vgpc_test_current_user_id'] = 0;
        $this->course(618);
        $service = $this->service();
        $active = $service->start_course_intent(618);
        $bricks = new JourneyIntentBricks($service);
        $_SERVER['HTTP_ORIGIN'] = 'https://voelgoed.test';

        $fields = [
            'form-field-vgpc_journey_intent_action' => 'dismiss',
            'form-field-vgpc_journey_intent_id' => $active['intent_id'],
            'form-field-vgpc_journey_intent_nonce' => 'nonce-vgpc_journey_intent',
        ];
        $dismiss = new VGJI_TestBricksForm($fields);
        $bricks->handle_form_action($dismiss);

        vgpc_test_same('vgpc_journey_intent', $dismiss->result['action'], 'The native action identity must remain stable.');
        vgpc_test_same('success', $dismiss->result['type'], 'A current No Thanks action must acknowledge success in place.');
        vgpc_test_true(! isset($dismiss->result['redirectTo']), 'No Thanks must not redirect the visitor back into membership.');
        vgpc_test_true(! isset($dismiss->result['redirectTimeout']), 'No Thanks must not schedule a membership redirect.');
        vgpc_test_same(JourneyIntentService::STATUS_DISMISSED, $service->current_intent()['status'], 'No Thanks must persist DISMISSED.');

        // A repeated submission of the already-dismissed intent is idempotent
        // and also must not restore ACTIVE or start a new membership redirect.
        $repeated = new VGJI_TestBricksForm($fields);
        $bricks->handle_form_action($repeated);
        vgpc_test_same('success', $repeated->result['type'], 'Repeated No Thanks must remain an idempotent acknowledgement.');
        vgpc_test_true(! isset($repeated->result['redirectTo']), 'Repeated No Thanks must never redirect.');
        vgpc_test_same(JourneyIntentService::STATUS_DISMISSED, $service->current_intent()['status'], 'Repeated No Thanks must not resurrect the intent.');
    }

    public function test_bricks_action_requires_nonce_and_exact_same_origin_and_ignores_return_to(): void
    {
        $this->reset();
        $this->course(620);
        $service = $this->service();
        $bricks = new JourneyIntentBricks($service, static function (string $url): bool { return true; }, static function (): void {});
        $bricks->register();
        $GLOBALS['_SERVER']['HTTP_ORIGIN'] = 'https://voelgoed.test';

        $invalid = new VGJI_TestBricksForm([
            'form-field-vgpc_journey_intent_action' => 'start',
            'form-field-vgpc_journey_course_id' => '620',
            'form-field-vgpc_journey_intent_nonce' => 'bad',
            'form-field-return_to' => 'https://attacker.example/',
        ]);
        $bricks->handle_form_action($invalid);
        vgpc_test_same('danger', $invalid->result['type'], 'Invalid nonce must reject form submission.');
        vgpc_test_same(null, $service->current_intent(), 'Invalid form submissions must not create intent.');

        $GLOBALS['_SERVER']['HTTP_ORIGIN'] = 'https://attacker.example';
        $cross_origin = new VGJI_TestBricksForm([
            'form-field-vgpc_journey_intent_action' => 'start',
            'form-field-vgpc_journey_course_id' => '620',
            'form-field-vgpc_journey_intent_nonce' => 'nonce-vgpc_journey_intent',
        ]);
        $bricks->handle_form_action($cross_origin);
        vgpc_test_same('danger', $cross_origin->result['type'], 'Cross-origin form submission must be rejected.');
        vgpc_test_same(null, $service->current_intent(), 'Cross-origin submission must not create intent.');

        $GLOBALS['_SERVER']['HTTP_ORIGIN'] = 'https://voelgoed.test';
        $valid = new VGJI_TestBricksForm([
            'form-field-vgpc_journey_intent_action' => 'start',
            'form-field-vgpc_journey_course_id' => '620',
            'form-field-vgpc_journey_intent_nonce' => 'nonce-vgpc_journey_intent',
            'form-field-return_to' => 'https://attacker.example/',
        ]);
        $bricks->handle_form_action($valid);
        vgpc_test_same('redirect', $valid->result['type'], 'Valid explicit Bricks action must continue to the membership page.');
        vgpc_test_same(home_url('/voelgoedvriendinne/'), $valid->result['redirectTo'], 'Post-action destination must be a fixed local membership page.');
        vgpc_test_same('https://voelgoed.test/leer/course-620/', $service->return_destination(), 'Browser return_to must never control the Course return.');

        $current = $service->current_intent();
        $dismiss = new VGJI_TestBricksForm([
            'form-field-vgpc_journey_intent_action' => 'dismiss',
            'form-field-vgpc_journey_intent_id' => $current['intent_id'],
            'form-field-vgpc_journey_intent_nonce' => 'nonce-vgpc_journey_intent',
        ]);
        $bricks->handle_form_action($dismiss);
        vgpc_test_same('success', $dismiss->result['type'], 'A valid No Thanks form must acknowledge dismissal in place.');
        vgpc_test_true(! isset($dismiss->result['redirectTo']), 'A valid No Thanks form must not return visitors to membership.');
        vgpc_test_same(JourneyIntentService::STATUS_DISMISSED, $service->current_intent()['status'], 'Bricks Dismiss must persist the matching intent as DISMISSED.');
    }

    public function test_native_bricks_form_action_is_selectable_in_the_builder(): void
    {
        $bricks = new JourneyIntentBricks($this->service());
        $controls = [
            'actions' => ['options' => ['email' => 'Email']],
            'submitButton' => ['type' => 'text'],
        ];

        $registered = $bricks->register_form_action($controls);

        vgpc_test_same(
            'Journey Intent',
            $registered['actions']['options']['vgpc_journey_intent'],
            'The Journey Intent action must be available in Bricks Form action settings.'
        );
        vgpc_test_same($controls['submitButton'], $registered['submitButton'], 'Unrelated Bricks Form controls must remain unchanged.');
        vgpc_test_same(
            $registered,
            $bricks->register_form_action($registered),
            'Action registration must be idempotent.'
        );
    }

    public function test_return_endpoint_uses_validated_destination_and_never_marks_completed(): void
    {
        $this->reset();
        $this->course(630);
        $service = $this->service();
        $service->start_course_intent(630);
        $redirected = null;
        $bricks = new JourneyIntentBricks(
            $service,
            static function (string $url) use (&$redirected): bool { $redirected = $url; return true; },
            static function (): void {}
        );

        $bricks->handle_return();

        vgpc_test_same('https://voelgoed.test/leer/course-630/', $redirected, 'Return endpoint must use the server-validated Course destination.');
        vgpc_test_same(JourneyIntentService::STATUS_ACTIVE, $service->current_intent()['status'], 'A return click must leave intent ACTIVE, not COMPLETED.');
    }

    public function test_return_url_tag_is_fixed_and_no_intent_data_is_embedded_in_public_markup(): void
    {
        $this->reset();
        $bricks = new JourneyIntentBricks($this->service());
        $tags = $bricks->register_tags([]);

        vgpc_test_true(in_array('{vgpc_journey_intent_return_url}', array_column($tags, 'name'), true), 'Bricks must expose the fixed return endpoint.');
        vgpc_test_true(in_array('{vgpc_journey_intent_nonce}', array_column($tags, 'name'), true), 'Bricks must expose a nonce for native forms.');
        vgpc_test_true(in_array('{vgpc_journey_intent_active}', array_column($tags, 'name'), true), 'Bricks must expose a native condition value for the active reminder.');
        vgpc_test_true(in_array('{vgpc_journey_intent_course_title}', array_column($tags, 'name'), true), 'Bricks must expose the active Course title for the reminder.');
        vgpc_test_true(in_array('{vgpc_journey_intent_id}', array_column($tags, 'name'), true), 'Dismiss forms must bind to the current intent ID.');
        vgpc_test_same(
            'https://voelgoed.test/wp-admin/admin-post.php?action=vgpc_journey_intent_return',
            $bricks->render_tag('{vgpc_journey_intent_return_url}'),
            'Return URL must not contain a Course ID or browser-supplied destination.'
        );
    }

    public function test_stale_dismissal_cannot_dismiss_a_newer_course_intent(): void
    {
        $this->reset();
        $GLOBALS['vgpc_test_current_user_id'] = 0;
        $this->course(640);
        $this->course(641);
        $service = $this->service();
        $old = $service->start_course_intent(640);
        $new = $service->start_course_intent(641);

        $result = $service->dismiss_current_intent($old['intent_id']);

        vgpc_test_true(is_wp_error($result), 'A stale dismissal must return an explicit error.');
        vgpc_test_same('journey_intent_stale_dismissal', $result->get_error_code(), 'Stale state must be distinguishable from successful dismissal.');
        vgpc_test_same($new['intent_id'], $service->current_intent()['intent_id'], 'Dismissal must be conditional on the intent ID rendered with the form.');
    }

    public function test_native_bricks_reminder_tags_are_per_visitor_and_return_only_current_active_data(): void
    {
        $this->reset();
        $GLOBALS['vgpc_test_current_user_id'] = 0;
        $this->course(650);
        $service = $this->service();
        $bricks = new JourneyIntentBricks($service);

        vgpc_test_same('0', $bricks->render_tag('{vgpc_journey_intent_active}'), 'No intent must hide the native reminder section.');
        $active = $service->start_course_intent(650);
        vgpc_test_same('1', $bricks->render_tag('{vgpc_journey_intent_active}'), 'An active intent must enable the native reminder section.');
        vgpc_test_same('Kursus 650', $bricks->render_tag('{vgpc_journey_intent_course_title}'), 'Reminder title must come from the canonical Course record.');
        vgpc_test_same($active['intent_id'], $bricks->render_tag('{vgpc_journey_intent_id}'), 'Dismiss form must render the exact current intent ID.');
        vgpc_test_true($GLOBALS['vgji_test_nocache_calls'] >= 3, 'Visitor-specific reminder tags must prevent shared page-cache leakage.');
    }

    public function test_all_lifecycle_states_are_named_and_assembly_wires_only_the_native_adapter(): void
    {
        $states = [
            JourneyIntentService::STATUS_ACTIVE,
            JourneyIntentService::STATUS_SUPERSEDED,
            JourneyIntentService::STATUS_DISMISSED,
            JourneyIntentService::STATUS_COMPLETED,
            JourneyIntentService::STATUS_INVALID,
            JourneyIntentService::STATUS_EXPIRED,
        ];
        vgpc_test_same(['ACTIVE', 'SUPERSEDED', 'DISMISSED', 'COMPLETED', 'INVALID', 'EXPIRED'], $states, 'The six required states must be explicit.');

        $patch = file_get_contents(dirname(__DIR__) . '/integration.patch');
        vgpc_test_true(is_string($patch), 'The Platform Core assembly patch must be readable.');
        foreach ([
            "'includes/JourneyIntent/JourneyIntentCookie.php',",
            "'includes/JourneyIntent/JourneyIntentLock.php',",
            "'includes/JourneyIntent/JourneyIntentRepository.php',",
            "'includes/JourneyIntent/JourneyIntentService.php',",
            "'includes/JourneyIntent/JourneyIntentBricks.php',",
            'use Voelgoed\\PlatformCore\\JourneyIntent\\JourneyIntentBricks;',
            "\$this->register_component('journey_intent_bricks', JourneyIntentBricks::class);",
        ] as $required_line) {
            vgpc_test_same(1, substr_count($patch, $required_line), 'The assembly patch must contain each Journey Intent module and registration exactly once.');
        }
    }

    private function reset(): void
    {
        vgpc_test_reset();
        $GLOBALS['vgji_test_user_meta'] = [];
        $GLOBALS['vgji_test_cookie_value'] = null;
        $GLOBALS['vgji_test_cookie_options'] = [];
        $GLOBALS['vgji_test_now'] = 1_800_000_000.0;
        $GLOBALS['vgji_test_ssl'] = true;
        $GLOBALS['vgji_test_redirects'] = [];
        $GLOBALS['vgji_test_nocache_calls'] = 0;
        $GLOBALS['vgji_test_fail_user_meta_write'] = false;
        $_SERVER = [];
    }

    private function service(): JourneyIntentService
    {
        $cookie = new JourneyIntentCookie(
            static fn (): ?string => $GLOBALS['vgji_test_cookie_value'],
            static function (string $name, string $value, array $options): bool {
                $GLOBALS['vgji_test_cookie_name'] = $name;
                $GLOBALS['vgji_test_cookie_value'] = $value;
                $GLOBALS['vgji_test_cookie_options'] = $options;
                return true;
            },
            static function (string $name, array $options): bool {
                $GLOBALS['vgji_test_cookie_name'] = $name;
                $GLOBALS['vgji_test_cookie_value'] = null;
                $GLOBALS['vgji_test_cookie_options'] = $options;
                return true;
            }
        );
        $repository = new JourneyIntentRepository($cookie);
        return new JourneyIntentService(
            $repository,
            new JourneyIntentLock(),
            static fn (): float => (float) $GLOBALS['vgji_test_now']
        );
    }

    private function course(
        int $id,
        string $status = 'publish',
        string $type = 'mto-course',
        bool $not_publicly_viewable = false,
        string $password = '',
        string $permalink = ''
    ): void {
        $GLOBALS['vgpc_test_posts'][$id] = (object) [
            'ID' => $id,
            'post_type' => $type,
            'post_status' => $status,
            'post_title' => 'Kursus ' . $id,
            'post_password' => $password,
            'publicly_viewable' => ! $not_publicly_viewable,
            'permalink' => $permalink !== '' ? $permalink : 'https://voelgoed.test/leer/course-' . $id . '/',
        ];
    }

    private function encode_cookie(array $intent): string
    {
        return (new JourneyIntentCookie())->encode($intent);
    }
}
