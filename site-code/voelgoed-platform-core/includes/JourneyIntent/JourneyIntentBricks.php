<?php
declare(strict_types=1);

namespace Voelgoed\PlatformCore\JourneyIntent;

defined('ABSPATH') || exit;

/** Connects native Bricks Forms and links to the Journey Intent service. */
final class JourneyIntentBricks
{
    private const FORM_ACTION = 'vgpc_journey_intent';
    private const RETURN_ACTION = 'vgpc_journey_intent_return';
    private const NONCE_ACTION = 'vgpc_journey_intent';
    private const MEMBERSHIP_PATH = '/voelgoedvriendinne/';

    private JourneyIntentService $service;

    private ?\Closure $redirector;

    private ?\Closure $terminator;

    public function __construct(
        ?JourneyIntentService $service = null,
        ?callable $redirector = null,
        ?callable $terminator = null
    ) {
        $this->service = $service ?? new JourneyIntentService();
        $this->redirector = $redirector !== null ? \Closure::fromCallable($redirector) : null;
        $this->terminator = $terminator !== null ? \Closure::fromCallable($terminator) : null;
    }

    public function register(): void
    {
        add_filter('bricks/elements/form/controls', [$this, 'register_form_action'], 10, 1);
        add_filter('bricks/dynamic_tags_list', [$this, 'register_tags'], 10, 1);
        add_filter('bricks/dynamic_data/render_tag', [$this, 'render_tag'], 20, 3);
        add_filter('bricks/dynamic_data/render_content', [$this, 'render_content'], 11, 3);
        add_action('bricks/form/action/' . self::FORM_ACTION, [$this, 'handle_form_action'], 10, 1);
        add_action('wp_login', [$this->service, 'promote_after_login'], 10, 2);
        add_action('admin_post_' . self::RETURN_ACTION, [$this, 'handle_return'], 10, 0);
        add_action('admin_post_nopriv_' . self::RETURN_ACTION, [$this, 'handle_return'], 10, 0);
    }

    /** @param array<string, mixed> $controls @return array<string, mixed> */
    public function register_form_action(array $controls): array
    {
        if (
            ! isset($controls['actions'])
            || ! is_array($controls['actions'])
            || ! isset($controls['actions']['options'])
            || ! is_array($controls['actions']['options'])
        ) {
            return $controls;
        }

        if (! array_key_exists(self::FORM_ACTION, $controls['actions']['options'])) {
            $controls['actions']['options'][self::FORM_ACTION] = 'Journey Intent';
        }

        return $controls;
    }

    /** @param array<int, array<string, mixed>> $tags @return array<int, array<string, mixed>> */
    public function register_tags(array $tags): array
    {
        $registered = array_column(array_filter($tags, 'is_array'), 'name');
        foreach ([
            '{vgpc_journey_intent_return_url}' => 'Journey Intent: Course return URL',
            '{vgpc_journey_intent_nonce}' => 'Journey Intent: Form nonce',
            '{vgpc_journey_intent_active}' => 'Journey Intent: Active reminder',
            '{vgpc_journey_intent_course_title}' => 'Journey Intent: Course title',
            '{vgpc_journey_intent_id}' => 'Journey Intent: Current intent ID',
        ] as $name => $label) {
            if (in_array($name, $registered, true)) {
                continue;
            }
            $tags[] = ['name' => $name, 'label' => $label, 'group' => 'Voelgoed Journey'];
        }
        return $tags;
    }

    /** @param mixed $value @param mixed $post */
    public function render_tag($value, $post = null, string $context = 'text'): mixed
    {
        unset($post);
        $tag = is_string($value) ? trim($value, '{}') : '';
        if ($tag === 'vgpc_journey_intent_return_url') {
            return function_exists('esc_url') ? esc_url($this->return_url()) : $this->return_url();
        }
        if ($tag === 'vgpc_journey_intent_nonce') {
            if (get_current_user_id() > 0 && function_exists('nocache_headers')) {
                nocache_headers();
            }
            $nonce = function_exists('wp_create_nonce') ? wp_create_nonce(self::NONCE_ACTION) : '';
            return function_exists('esc_attr') ? esc_attr($nonce) : $nonce;
        }
        if (in_array($tag, ['vgpc_journey_intent_active', 'vgpc_journey_intent_course_title', 'vgpc_journey_intent_id'], true)) {
            if (function_exists('nocache_headers')) {
                nocache_headers();
            }
            $intent = $this->service->current_intent();
            if (is_wp_error($intent) || $intent === null || $intent['status'] !== JourneyIntentService::STATUS_ACTIVE) {
                return $tag === 'vgpc_journey_intent_active' ? '0' : '';
            }
            if ($tag === 'vgpc_journey_intent_active') {
                return '1';
            }
            if ($tag === 'vgpc_journey_intent_id') {
                return function_exists('esc_attr') ? esc_attr($intent['intent_id']) : $intent['intent_id'];
            }
            $title = get_the_title((int) $intent['target_id']);
            return function_exists('esc_html') ? esc_html($title) : $title;
        }
        return $value;
    }

    /** @param mixed $content @param mixed $post */
    public function render_content($content, $post = null, string $context = 'text'): mixed
    {
        unset($post);
        if (! is_string($content)) {
            return $content;
        }
        foreach ([
            'vgpc_journey_intent_return_url',
            'vgpc_journey_intent_nonce',
            'vgpc_journey_intent_active',
            'vgpc_journey_intent_course_title',
            'vgpc_journey_intent_id',
        ] as $tag) {
            $token = '{' . $tag . '}';
            if (str_contains($content, $token)) {
                $content = str_replace($token, (string) $this->render_tag($token, null, $context), $content);
            }
        }
        return $content;
    }

    /** Handle only the explicit native Bricks form actions `start` and `dismiss`. */
    public function handle_form_action(object $form): void
    {
        if (! method_exists($form, 'get_fields') || ! method_exists($form, 'set_result')) {
            return;
        }

        $fields = $form->get_fields();
        if (! is_array($fields) || ! $this->valid_form_request($fields)) {
            $this->set_form_result($form, 'danger', 'Die versoek kon nie bevestig word nie. Herlaai die blad en probeer weer.');
            return;
        }

        $action = $this->field_value($fields, 'vgpc_journey_intent_action');
        if ($action === 'start') {
            $course_value = $this->field_value($fields, 'vgpc_journey_course_id');
            if (! preg_match('/^[1-9][0-9]*$/D', $course_value)) {
                $this->set_form_result($form, 'danger', 'Kies ’n geldige kursus en probeer weer.');
                return;
            }

            $result = $this->service->start_course_intent((int) $course_value);
        } elseif ($action === 'dismiss') {
            $intent_id = $this->field_value($fields, 'vgpc_journey_intent_id');
            $result = $this->service->dismiss_current_intent($intent_id);
        } else {
            $this->set_form_result($form, 'danger', 'Hierdie aksie is nie beskikbaar nie.');
            return;
        }

        if (is_wp_error($result)) {
            $this->set_form_result($form, 'danger', 'Die versoek kon nie voltooi word nie. Herlaai die blad en probeer weer.');
            return;
        }

        // No Thanks dismisses the reminder in place. It must not send the
        // visitor back into membership or imply that a stale form succeeded.
        if ($action === 'dismiss') {
            if (! is_array($result) || ($result['status'] ?? null) !== JourneyIntentService::STATUS_DISMISSED) {
                $this->set_form_result($form, 'danger', 'Die herinnering kon nie verwyder word nie. Herlaai die blad en probeer weer.');
                return;
            }

            $this->set_form_result($form, 'success', 'Die kursusherinnering is verwyder.');
            return;
        }

        // Only an explicitly started ACTIVE Course intent may open membership.
        if (! is_array($result) || ($result['status'] ?? null) !== JourneyIntentService::STATUS_ACTIVE) {
            $this->set_form_result($form, 'danger', 'Die kursus kon nie onthou word nie. Herlaai die blad en probeer weer.');
            return;
        }

        $form->set_result([
            'action' => self::FORM_ACTION,
            'type' => 'redirect',
            'redirectTo' => home_url(self::MEMBERSHIP_PATH),
            'redirectTimeout' => 0,
        ]);
    }

    /** Return through a fixed endpoint; destination comes only from validated server state. */
    public function handle_return(): void
    {
        if (function_exists('nocache_headers')) {
            nocache_headers();
        }

        $destination = $this->service->return_destination();
        $url = is_wp_error($destination) ? home_url(self::MEMBERSHIP_PATH) : $destination;
        $redirected = $this->redirector !== null
            ? (bool) ($this->redirector)($url)
            : wp_safe_redirect($url, 302, 'VGPC Journey Intent');
        if (! $redirected) {
            return;
        }

        if ($this->terminator !== null) {
            ($this->terminator)();
            return;
        }

        exit;
    }

    public function return_url(): string
    {
        $base = admin_url('admin-post.php');
        return function_exists('add_query_arg')
            ? add_query_arg('action', self::RETURN_ACTION, $base)
            : $base . '?action=' . self::RETURN_ACTION;
    }

    /** @param array<string, mixed> $fields */
    private function valid_form_request(array $fields): bool
    {
        $nonce = $this->field_value($fields, 'vgpc_journey_intent_nonce');
        if ($nonce === '' || ! function_exists('wp_verify_nonce') || ! wp_verify_nonce($nonce, self::NONCE_ACTION)) {
            return false;
        }

        $origin = (string) ($_SERVER['HTTP_ORIGIN'] ?? '');
        $origin_parts = parse_url($origin);
        $home_parts = parse_url(home_url('/'));
        if (
            ! is_array($origin_parts)
            || ! is_array($home_parts)
            || ! in_array(strtolower((string) ($origin_parts['scheme'] ?? '')), ['http', 'https'], true)
            || strtolower((string) ($origin_parts['scheme'] ?? '')) !== strtolower((string) ($home_parts['scheme'] ?? ''))
            || strtolower((string) ($origin_parts['host'] ?? '')) !== strtolower((string) ($home_parts['host'] ?? ''))
            || $this->effective_port($origin_parts) !== $this->effective_port($home_parts)
            || isset($origin_parts['user'])
            || isset($origin_parts['pass'])
            || isset($origin_parts['path']) && $origin_parts['path'] !== ''
            || isset($origin_parts['query'])
            || isset($origin_parts['fragment'])
        ) {
            return false;
        }

        $fetch_site = strtolower((string) ($_SERVER['HTTP_SEC_FETCH_SITE'] ?? ''));
        return $fetch_site === '' || $fetch_site === 'same-origin';
    }

    /** @param array<string, mixed> $parts */
    private function effective_port(array $parts): int
    {
        if (isset($parts['port'])) {
            return (int) $parts['port'];
        }

        return strtolower((string) ($parts['scheme'] ?? '')) === 'https' ? 443 : 80;
    }

    /** @param array<string, mixed> $fields */
    private function field_value(array $fields, string $id): string
    {
        $value = $fields['form-field-' . $id] ?? '';
        return is_scalar($value) ? trim((string) $value) : '';
    }

    private function set_form_result(object $form, string $type, string $message): void
    {
        $form->set_result([
            'action' => self::FORM_ACTION,
            'type' => $type,
            'message' => $message,
        ]);
    }
}
