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
            self::$options = [];
            unset($_SESSION);
            unset($_COOKIE[session_name()]);
        }

        /**
         * Enable fake session.
         */
        public static function enable(): void
        {
            self::$isEnabled = true;
            self::$status = PHP_SESSION_NONE;
            self::$options = [];
            unset($_SESSION);
            unset($_COOKIE[session_name()]);
        }

        /**
         * Sets the session content for a simulated previous session.
         *
         * @param array $session The session content.
         */
        public static function setPreviousSession(array $session): void
        {
            $_SESSION = $session;
            $_COOKIE[session_name()] = 'ABCDE';
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
         * @return bool True if fake session is enabled, false otherwise.
         */
        public static function isEnabled(): bool
        {
            return self::$isEnabled;
        }

        /**
         * Starts the session.
         *
         * @param array $options The options.
         */
        public static function start(array $options): void
        {
            self::$status = PHP_SESSION_ACTIVE;
            self::$options = $options;

            if (!isset($_SESSION)) {
                $_SESSION = [];
            }
        }

        /**
         * Destroys the session.
         */
        public static function destroy(): void
        {
            self::$status = PHP_SESSION_NONE;
        }

        /**
         * Returns the options.
         *
         * @return array The options.
         */
        public static function getOptions(): array
        {
            return self::$options;
        }

        /**
         * @var bool True if fake session is enabled, false otherwise.
         */
        private static $isEnabled = false;

        /**
         * @var int My current session status.
         */
        private static $status = PHP_SESSION_NONE;

        /**
         * @var array My options.
         */
        private static $options = [];
    }
}

namespace BlueMvc\Core {

    use BlueMvc\Core\Tests\Helpers\Fakes\FakeSession;

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

    /**
     * Fakes the session_destroy method.
     *
     * @return bool The result.
     */
    function session_destroy(): bool
    {
        if (FakeSession::isEnabled()) {
            FakeSession::destroy();

            return true;
        }

        return \session_destroy();
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
            FakeSession::start($options);

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
