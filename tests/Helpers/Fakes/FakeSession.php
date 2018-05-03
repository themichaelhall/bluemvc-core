<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Helpers\Fakes {

    /**
     * Helper for fake session functions.
     */
    class FakeSession
    {
        /**
         * Disable fake session.
         */
        public static function disable(): void
        {
            self::$isEnabled = false;
            self::$status = PHP_SESSION_NONE;
            unset($_SESSION);
        }

        /**
         * Enable fake session.
         */
        public static function enable(): void
        {
            self::$isEnabled = true;
            self::$status = PHP_SESSION_NONE;

            if (!isset($_SESSION)) {
                $_SESSION = [];
            }
        }

        /**
         * Returns the session status.
         *
         * @return int The session status.
         */
        public static function getStatus(): int
        {
            return self::$status;
        }

        /**
         * @return bool True if fake cookies is enabled, false otherwise.
         */
        public static function isEnabled(): bool
        {
            return self::$isEnabled;
        }

        /**
         * Starts the session.
         */
        public static function start(): void
        {
            self::$status = PHP_SESSION_ACTIVE;
        }

        /**
         * @var bool True if fake session is enabled, false otherwise.
         */
        private static $isEnabled = false;

        /**
         * @var int My current session status.
         */
        private static $status = PHP_SESSION_NONE;
    }
}

namespace BlueMvc\Core\Collections {

    use BlueMvc\Core\Tests\Helpers\Fakes\FakeSession;

    /**
     * Fakes the session_start method.
     *
     * @param array $options The options.
     *
     * @return bool True if session was started, false otherwise.
     */
    function session_start(array $options = []): bool
    {
        if (FakeSession::isEnabled()) {
            FakeSession::start();

            return true;
        }

        return \session_start($options);
    }

    /**
     * Fakes the session_status method.
     *
     * @return int The session status.
     */
    function session_status(): int
    {
        if (FakeSession::isEnabled()) {
            return FakeSession::getStatus();
        }

        return \session_status();
    }
}
