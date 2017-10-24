<?php

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
        public static function add($name, $value, $expire, $path, $domain, $secure, $httponly)
        {
            self::$myCookies[] = [
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
        public static function disable()
        {
            self::$isEnabled = false;
        }

        /**
         * Enable fake cookies.
         */
        public static function enable()
        {
            self::$myCookies = [];
            self::$isEnabled = true;
        }

        /**
         * @return array The cookies.
         */
        public static function get()
        {
            return self::$myCookies;
        }

        /**
         * @return bool True if fake cookies is enabled, false otherwise.
         */
        public static function isEnabled()
        {
            return self::$isEnabled;
        }

        /**
         * @var array My cookies.
         */
        private static $myCookies = [];

        /**
         * @var bool True if fake cookies is enabled, false otherwise.
         */
        private static $isEnabled = false;
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
    function setcookie($name, $value = '', $expire = 0, $path = '', $domain = '', $secure = false, $httponly = false)
    {
        if (FakeCookies::isEnabled()) {
            FakeCookies::add($name, $value, $expire, $path, $domain, $secure, $httponly);
        } else {
            \setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
        }
    }
}
