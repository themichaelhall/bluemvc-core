<?php

namespace BlueMvc\Core\Tests\Helpers\Fakes {

    /**
     * Helper for fake headers.
     */
    class FakeHeaders
    {
        /**
         * Adds a header.
         *
         * @param string $header The header.
         */
        public static function add($header)
        {
            self::$myHeaders[] = $header;
        }

        /**
         * Disable fake headers.
         */
        public static function disable()
        {
            self::$isEnabled = false;
        }

        /**
         * Enable fake headers.
         */
        public static function enable()
        {
            self::$myHeaders = [];
            self::$isEnabled = true;
        }

        /**
         * @return string[] The headers.
         */
        public static function get()
        {
            return self::$myHeaders;
        }

        /**
         * @return bool True if fake headers is enabled, false otherwise.
         */
        public static function isEnabled()
        {
            return self::$isEnabled;
        }

        /**
         * @var string[] My headers.
         */
        private static $myHeaders = [];

        /**
         * @var bool True if fake headers is enabled, false otherwise.
         */
        private static $isEnabled = false;
    }
}

namespace BlueMvc\Core {

    use BlueMvc\Core\Tests\Helpers\Fakes\FakeHeaders;

    /**
     * Fakes the header PHP method.
     *
     * @param string $header The header.
     */
    function header($header)
    {
        if (FakeHeaders::isEnabled()) {
            FakeHeaders::add($header);
        } else {
            \header($header);
        }
    }
}
