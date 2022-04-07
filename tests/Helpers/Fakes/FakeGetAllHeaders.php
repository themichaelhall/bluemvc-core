<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Helpers\Fakes {

    /**
     * Helper for fake getallheaders method.
     */
    class FakeGetAllHeaders
    {
        /**
         * Disable fake getallheaders method.
         */
        public static function disable(): void
        {
            self::$isEnabled = false;
        }

        /**
         * Enable fake getallheaders method.
         */
        public static function enable(): void
        {
            self::$isEnabled = true;
            self::$headers = [];
        }

        /**
         * @return bool True if fake getallheaders method is enabled, false otherwise.
         */
        public static function isEnabled(): bool
        {
            return self::$isEnabled;
        }

        /**
         * Adds a header.
         *
         * @param string $name  The name.
         * @param string $value The value.
         */
        public static function addHeader(string $name, string $value): void
        {
            self::$headers[$name] = $value;
        }

        /**
         * @return array The headers.
         */
        public static function getHeaders(): array
        {
            return self::$headers;
        }

        /**
         * @var bool True if fake getallheaders method is enabled, false otherwise.
         */
        private static bool $isEnabled = false;

        /**
         * @var array The headers.
         */
        private static array $headers = [];
    }
}

namespace BlueMvc\Core {

    use BlueMvc\Core\Tests\Helpers\Fakes\FakeGetAllHeaders;

    /**
     * Fakes the getallheaders PHP function.
     *
     * @return array All headers.
     */
    function getallheaders(): array
    {
        if (FakeGetAllHeaders::isEnabled()) {
            return FakeGetAllHeaders::getHeaders();
        }

        return \getallheaders();
    }
}
