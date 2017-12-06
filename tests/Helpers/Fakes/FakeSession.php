<?php

namespace BlueMvc\Core\Tests\Helpers\Fakes {

    /**
     * Helper for fake session functions.
     */
    class FakeSession
    {
        /**
         * Disable fake session.
         */
        public static function disable()
        {
            self::$isEnabled = false;
            self::$status = PHP_SESSION_NONE;
            unset($_SESSION);
        }

        /**
         * Enable fake session.
         */
        public static function enable()
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
        public static function getStatus()
        {
            return self::$status;
        }

        /**
         * @return bool True if fake cookies is enabled, false otherwise.
         */
        public static function isEnabled()
        {
            return self::$isEnabled;
        }

        /**
         * Starts the session.
         */
        public static function start()
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
     */
    function session_start()
    {
        if (FakeSession::isEnabled()) {
            FakeSession::start();

            return;
        }

        \session_start();
    }

    /**
     * Fakes the session_status method.
     *
     * @return int The session status.
     */
    function session_status()
    {
        if (FakeSession::isEnabled()) {
            return FakeSession::getStatus();
        }

        return \session_status();
    }
}
