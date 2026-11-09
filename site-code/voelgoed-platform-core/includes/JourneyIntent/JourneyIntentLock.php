<?php
declare(strict_types=1);

namespace Voelgoed\PlatformCore\JourneyIntent;

defined('ABSPATH') || exit;

/** Serializes authenticated intent transitions and guest-to-user promotion. */
final class JourneyIntentLock
{
    private const WAIT_TIMEOUT_SECONDS = 5;

    public function with_user_lock(int $user_id, callable $operation): mixed
    {
        if ($user_id <= 0) {
            return new \WP_Error('journey_intent_invalid_user', 'A valid account is required for this intent operation.');
        }

        global $wpdb;
        if (! is_object($wpdb) || ! method_exists($wpdb, 'prepare') || ! method_exists($wpdb, 'get_var')) {
            return new \WP_Error('journey_intent_lock_unavailable', 'Journey Intent could not reserve the account state.');
        }

        $lock_name = $this->lock_name($user_id);
        try {
            $acquired = $wpdb->get_var($wpdb->prepare('SELECT GET_LOCK(%s, %d)', $lock_name, self::WAIT_TIMEOUT_SECONDS));
        } catch (\Throwable) {
            return new \WP_Error('journey_intent_lock_unavailable', 'Journey Intent could not reserve the account state.');
        }

        if ((string) $acquired !== '1') {
            return new \WP_Error('journey_intent_lock_unavailable', 'Journey Intent could not reserve the account state. Try again.');
        }

        try {
            return $operation();
        } finally {
            try {
                $wpdb->get_var($wpdb->prepare('SELECT RELEASE_LOCK(%s)', $lock_name));
            } catch (\Throwable) {
                // The database releases the lock when its connection closes.
            }
        }
    }

    private function lock_name(int $user_id): string
    {
        global $wpdb;

        $scope = implode(':', [
            is_object($wpdb) ? (string) ($wpdb->dbname ?? '') : '',
            is_object($wpdb) ? (string) ($wpdb->prefix ?? '') : '',
            function_exists('get_current_blog_id') ? (int) get_current_blog_id() : 0,
            $user_id,
        ]);

        return 'vgpc_ji_' . substr(hash('sha256', $scope), 0, 56);
    }
}
