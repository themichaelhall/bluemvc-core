<?php

namespace BlueMvc\Core\Tests\Helpers\Fakes {

    /**
     * Helpers for fake file_get_content('php://input') method.
     */
    class FakeFileGetContentsPhpInput
    {
        /**
         * Disable fake file_get_content('php://input') method.
         */
        public static function disable()
        {
            self::$isEnabled = false;
        }

        /**
         * Enable fake file_get_content('php://input') method.
         */
        public static function enable()
        {
            self::$isEnabled = true;
            self::$content = '';
        }

        /**
         * @return bool True if fake file_get_content('php://input') method is enabled, false otherwise.
         */
        public static function isEnabled()
        {
            return self::$isEnabled;
        }

        /**
         * @return string The content.
         */
        public static function getContent()
        {
            return self::$content;
        }

        /**
         * Sets the content.
         *
         * @param string $content The content.
         */
        public static function setContent($content)
        {
            self::$content = $content;
        }

        /**
         * @var bool True if fake file_get_content('php://input') method is enabled, false otherwise.
         */
        private static $isEnabled = false;

        /**
         * @var string My content.
         */
        private static $content = '';
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
    function file_get_contents($filename)
    {
        if (FakeFileGetContentsPhpInput::isEnabled() && $filename === 'php://input') {
            $content = FakeFileGetContentsPhpInput::getContent();
            FakeFileGetContentsPhpInput::setContent('');

            return $content;
        }

        return \file_get_contents($filename);
    }
}
