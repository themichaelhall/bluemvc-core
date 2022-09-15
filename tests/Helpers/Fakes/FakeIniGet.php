<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Helpers\Fakes {
    /**
     * Helper for fake ini_get method.
     */
    class FakeIniGet
    {
        /**
         * Returns the value for the option name.
         *
         * @param string $name The option name.
         *
         * @return string The option value.
         */
        public static function get(string $name): string
        {
            return self::$settings[$name];
        }

        /**
         * Sets an option value by name.
         *
         * @param string $name  The option name.
         * @param string $value The option value.
         */
        public static function set(string $name, string $value): void
        {
            self::$settings[$name] = $value;
        }

        /**
         * Disable fake ini_get.
         */
        public static function disable(): void
        {
            self::$isEnabled = false;
        }

        /**
         * Enable fake ini_get.
         */
        public static function enable(): void
        {
            self::$settings = [];
            self::$isEnabled = true;
        }

        /**
         * @return bool True if fake ini_get method is enabled, false otherwise.
         */
        public static function isEnabled(): bool
        {
            return self::$isEnabled;
        }

        /**
         * @var bool True if fake ini_get is enabled, false otherwise.
         */
        private static bool $isEnabled = false;

        /**
         * @var array The settings.
         */
        private static array $settings = [];
    }
}

namespace BlueMvc\Core\Collections {
    use BlueMvc\Core\Tests\Helpers\Fakes\FakeIniGet;

    /**
     * Fakes the ini_get method.
     *
     * @param string $name The name of the setting.
     *
     * @return mixed The result.
     */
    function ini_get(string $name): string
    {
        if (FakeIniGet::isEnabled()) {
            return FakeIniGet::get($name);
        }

        return \ini_get($name);
    }
}
