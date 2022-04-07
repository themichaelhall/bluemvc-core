<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Helpers\Fakes {

    /**
     * Helpers for fake file_get_content('php://input') method.
     */
    class FakeFileGetContentsPhpInput
    {
        /**
         * Disable fake file_get_content('php://input') method.
         */
        public static function disable(): void
        {
            self::$isEnabled = false;
        }

        /**
         * Enable fake file_get_content('php://input') method.
         */
        public static function enable(): void
        {
            self::$isEnabled = true;
            self::$content = '';
        }

        /**
         * @return bool True if fake file_get_content('php://input') method is enabled, false otherwise.
         */
        public static function isEnabled(): bool
        {
            return self::$isEnabled;
        }

        /**
         * @return string The content.
         */
        public static function getContent(): string
        {
            return self::$content;
        }

        /**
         * Sets the content.
         *
         * @param string $content The content.
         */
        public static function setContent(string $content): void
        {
            self::$content = $content;
        }

        /**
         * @var bool True if fake file_get_content('php://input') method is enabled, false otherwise.
         */
        private static bool $isEnabled = false;

        /**
         * @var string The content.
         */
        private static string $content = '';
    }
}

namespace BlueMvc\Core {

    use BlueMvc\Core\Tests\Helpers\Fakes\FakeFileGetContentsPhpInput;

    /**
     * Fakes the file_get_content() method.
     *
     * @param string $filename The filename.
     *
     * @return string The content.
     */
    function file_get_contents(string $filename): string
    {
        if (FakeFileGetContentsPhpInput::isEnabled() && $filename === 'php://input') {
            $content = FakeFileGetContentsPhpInput::getContent();
            FakeFileGetContentsPhpInput::setContent('');

            return $content;
        }

        return \file_get_contents($filename);
    }
}
