<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Helpers\Fakes {
    /**
     * Helper for fake function_exists method.
     */
    class FakeFunctionExists
    {
        /**
         * Disable fake function_exists method.
         */
        public static function disable(): void
        {
            self::$isEnabled = false;
        }

        /**
         * Enable fake function_exists method.
         */
        public static function enable(): void
        {
            self::$isEnabled = true;
            self::$disabledFunctions = [];
        }

        /**
         * @return bool True if fake function_exists method is enabled, false otherwise.
         */
        public static function isEnabled(): bool
        {
            return self::$isEnabled;
        }

        /**
         * @return string[] The disabled functions.
         */
        public static function getDisabledFunctions(): array
        {
            return self::$disabledFunctions;
        }

        /**
         * Disables a function.
         *
         * @param string $functionName The function name.
         */
        public static function disableFunction(string $functionName): void
        {
            self::$disabledFunctions[] = $functionName;
        }

        /**
         * @var bool True if fake function_exists method is enabled, false otherwise.
         */
        private static bool $isEnabled = false;

        /**
         * @var string[] The disabled functions.
         */
        private static array $disabledFunctions = [];
    }
}

namespace BlueMvc\Core {
    use BlueMvc\Core\Tests\Helpers\Fakes\FakeFunctionExists;

    /**
     * Fakes the function_exists PHP method.
     *
     * @param string $functionName The function name.
     *
     * @return bool True if function exists, false otherwise.
     */
    function function_exists(string $functionName): bool
    {
        if (FakeFunctionExists::isEnabled()) {
            return !in_array($functionName, FakeFunctionExists::getDisabledFunctions());
        }

        return \function_exists($functionName);
    }
}
