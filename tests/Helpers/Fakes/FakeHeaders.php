<?php

declare(strict_types=1);

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
        public static function add(string $header): void
        {
            self::$headers[] = $header;
        }

        /**
         * Disable fake headers.
         */
        public static function disable(): void
        {
            self::$isEnabled = false;
        }

        /**
         * Enable fake headers.
         */
        public static function enable(): void
        {
            self::$headers = [];
            self::$isEnabled = true;
        }

        /**
         * @return string[] The headers.
         */
        public static function get(): array
        {
            return self::$headers;
        }

        /**
         * @return bool True if fake headers is enabled, false otherwise.
         */
        public static function isEnabled(): bool
        {
            return self::$isEnabled;
        }

        /**
         * @var string[] My headers.
         */
        private static $headers = [];

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
    function header(string $header): void
    {
        if (FakeHeaders::isEnabled()) {
            FakeHeaders::add($header);
        } else {
            \header($header);
        }
    }
}
