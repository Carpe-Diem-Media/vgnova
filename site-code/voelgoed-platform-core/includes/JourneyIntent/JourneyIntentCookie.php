<?php
declare(strict_types=1);

namespace Voelgoed\PlatformCore\JourneyIntent;

defined('ABSPATH') || exit;

/** Stores only a signed, minimal intent record in a first-party cookie. */
final class JourneyIntentCookie
{
    public const NAME = 'vgpc_journey_intent_v1';

    private ?\Closure $reader;

    private ?\Closure $writer;

    private ?\Closure $clearer;

    public function __construct(
        ?callable $reader = null,
        ?callable $writer = null,
        ?callable $clearer = null
    ) {
        $this->reader = $reader !== null ? \Closure::fromCallable($reader) : null;
        $this->writer = $writer !== null ? \Closure::fromCallable($writer) : null;
        $this->clearer = $clearer !== null ? \Closure::fromCallable($clearer) : null;
    }

    /** @return array<string, mixed>|null */
    public function read(): ?array
    {
        $value = $this->reader !== null
            ? ($this->reader)()
            : ($_COOKIE[self::NAME] ?? null);

        return is_string($value) ? $this->decode($value) : null;
    }

    /** @param array<string, mixed> $intent */
    public function write(array $intent): bool
    {
        $expires_at = (int) ($intent['expires_at'] ?? 0);
        $status = (string) ($intent['status'] ?? '');
        if ($status === JourneyIntentService::STATUS_EXPIRED) {
            // Keep an EXPIRED tombstone briefly so an ordinary next request does
            // not interpret the absence of a cookie as an untouched visitor.
            $expires_at = max(time(), $expires_at, (int) ($intent['updated_at'] ?? 0)) + DAY_IN_SECONDS;
        } elseif ($expires_at <= time()) {
            return false;
        } elseif ($status === JourneyIntentService::STATUS_ACTIVE) {
            // Keep the signed record through the logical expiry so a request
            // after the deadline can persist the EXPIRED transition.
            $expires_at += DAY_IN_SECONDS;
        }

        $value = $this->encode($intent);
        $options = $this->cookie_options($expires_at);
        if ($this->writer !== null) {
            return (bool) ($this->writer)(self::NAME, $value, $options);
        }

        return setcookie(self::NAME, $value, $options);
    }

    public function clear(): bool
    {
        $options = $this->cookie_options(1);
        if ($this->clearer !== null) {
            return (bool) ($this->clearer)(self::NAME, $options);
        }

        return setcookie(self::NAME, '', $options);
    }

    /** @param array<string, mixed> $intent */
    public function encode(array $intent): string
    {
        $json = wp_json_encode($intent, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $salt = wp_salt('auth');
        if (! is_string($json) || $json === '' || $salt === '') {
            return '';
        }

        $payload = rtrim(strtr(base64_encode($json), '+/', '-_'), '=');
        $signature = hash_hmac('sha256', $payload, $salt);
        return $payload . '.' . $signature;
    }

    /** @return array<string, mixed>|null */
    private function decode(string $value): ?array
    {
        if (strlen($value) > 2048 || ! preg_match('/^([A-Za-z0-9_-]+)\.([a-f0-9]{64})$/D', $value, $matches)) {
            return null;
        }

        $salt = wp_salt('auth');
        if ($salt === '' || ! hash_equals(hash_hmac('sha256', $matches[1], $salt), $matches[2])) {
            return null;
        }

        $encoded = strtr($matches[1], '-_', '+/');
        $padding = strlen($encoded) % 4;
        if ($padding !== 0) {
            $encoded .= str_repeat('=', 4 - $padding);
        }

        $json = base64_decode($encoded, true);
        if (! is_string($json)) {
            return null;
        }

        try {
            $intent = json_decode($json, true, 32, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            return null;
        }

        return is_array($intent) ? $intent : null;
    }

    /** @return array{expires:int,path:string,domain:string,secure:bool,httponly:bool,samesite:string} */
    private function cookie_options(int $expires_at): array
    {
        return [
            'expires' => $expires_at,
            'path' => defined('COOKIEPATH') && COOKIEPATH !== '' ? COOKIEPATH : '/',
            'domain' => defined('COOKIE_DOMAIN') ? (string) COOKIE_DOMAIN : '',
            'secure' => is_ssl(),
            'httponly' => true,
            'samesite' => 'Lax',
        ];
    }
}
