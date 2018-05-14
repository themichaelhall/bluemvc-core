<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Helpers\Fakes {

    /**
     * Helper for fake is_uploaded_file method.
     */
    class FakeIsUploadedFile
    {
        /**
         * Disable fake is_uploaded_file method.
         */
        public static function disable(): void
        {
            self::$isEnabled = false;
        }

        /**
         * Enable fake is_uploaded_file method.
         */
        public static function enable(): void
        {
            self::$isEnabled = true;
        }

        /**
         * @return bool True if fake is_uploaded_file method is enabled, false otherwise.
         */
        public static function isEnabled(): bool
        {
            return self::$isEnabled;
        }

        /**
         * @var bool True if fake is_uploaded_file method is enabled, false otherwise.
         */
        private static $isEnabled = false;
    }
}

namespace BlueMvc\Core {

    use BlueMvc\Core\Tests\Helpers\Fakes\FakeIsUploadedFile;

    /**
     * Returns true if a file is an uploaded file, false otherwise.
     *
     * @param string $filename The filename.
     *
     * @return bool True if file is an uploaded file, false otherwise.
     */
    function is_uploaded_file(string $filename): bool
    {
        if (FakeIsUploadedFile::isEnabled()) {
            return substr($filename, -13) !== '.not-uploaded';
        }

        return \is_uploaded_file($filename);
    }
}
