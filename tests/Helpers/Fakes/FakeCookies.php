<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Helpers\Fakes {

    /**
     * Helper for fake cookies.
     */
    class FakeCookies
    {
        /**
         * Adds a cookie.
         *
         * @param string $name     The name.
         * @param string $value    The value.
         * @param int    $expire   The expiry time.
         * @param string $path     The path.
         * @param string $domain   The domain.
         * @param bool   $secure   True if cookie should only be used on secure connection, false otherwise.
         * @param bool   $httponly True if cookie should only be accessible through the http protocol, false otherwise.
         */
        public static function add(string $name, string $value, int $expire, string $path, string $domain, bool $secure, bool $httponly): void
        {
            self::$cookies[] = [
                'name'     => $name,
                'value'    => $value,
                'expire'   => $expire,
                'path'     => $path,
                'domain'   => $domain,
                'secure'   => $secure,
                'httponly' => $httponly,
            ];
        }

        /**
         * Disable fake cookies.
         */
        public static function disable(): void
        {
            self::$isEnabled = false;
        }

        /**
         * Enable fake cookies.
         */
        public static function enable(): void
        {
            self::$cookies = [];
            self::$isEnabled = true;
        }

        /**
         * @return array The cookies.
         */
        public static function get(): array
        {
            return self::$cookies;
        }

        /**
         * @return bool True if fake cookies is enabled, false otherwise.
         */
        public static function isEnabled(): bool
        {
            return self::$isEnabled;
        }

        /**
         * @var array The cookies.
         */
        private static array $cookies = [];

        /**
         * @var bool True if fake cookies is enabled, false otherwise.
         */
        private static bool $isEnabled = false;
    }
}

namespace BlueMvc\Core {

    use BlueMvc\Core\Tests\Helpers\Fakes\FakeCookies;

    /**
     * Fakes the setcookie PHP method.
     *
     * @param string $name     The name.
     * @param string $value    The value.
     * @param int    $expire   The expiry time.
     * @param string $path     The path.
     * @param string $domain   The domain.
     * @param bool   $secure   True if cookie should only be used on secure connection, false otherwise.
     * @param bool   $httponly True if cookie should only be accessible through the http protocol, false otherwise.
     */
    function setcookie(string $name, string $value = '', int $expire = 0, string $path = '', string $domain = '', bool $secure = false, bool $httponly = false)
    {
        if (FakeCookies::isEnabled()) {
            FakeCookies::add($name, $value, $expire, $path, $domain, $secure, $httponly);
        } else {
            \setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
        }
    }
}
