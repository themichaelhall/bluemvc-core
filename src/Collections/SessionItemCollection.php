<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\Collections;

use BlueMvc\Core\Interfaces\Collections\SessionItemCollectionInterface;

/**
 * Class representing a collection of session items.
 *
 * @since 1.0.0
 */
class SessionItemCollection implements SessionItemCollectionInterface
{
    /**
     * Constructs the collection of session items.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
    }

    /**
     * Returns the number of session items.
     *
     * @since 1.0.0
     *
     * @return int The number of session items.
     */
    public function count()
    {
        self::myInit();

        return count($_SESSION);
    }

    /**
     * Returns the current session item value.
     *
     * @since 1.0.0
     *
     * @return mixed The current session item value.
     */
    public function current()
    {
        self::myInit();

        return current($_SESSION);
    }

    /**
     * Returns the session item value by session item name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The session item name.
     *
     * @throws \InvalidArgumentException If the $name parameter is not a string.
     *
     * @return mixed|null The session item value by session item name if it exists, null otherwise.
     */
    public function get($name)
    {
        if (!is_string($name)) {
            throw new \InvalidArgumentException('$name parameter is not a string.');
        }

        self::myInit();

        if (!isset($_SESSION[$name])) {
            return null;
        }

        return $_SESSION[$name];
    }

    /**
     * Returns the current session item name.
     *
     * @since 1.0.0
     *
     * @return string The current session item name.
     */
    public function key()
    {
        self::myInit();

        return key($_SESSION);
    }

    /**
     * Moves forwards to the next session item.
     *
     * @since 1.0.0
     */
    public function next()
    {
        self::myInit();

        next($_SESSION);
    }

    /**
     * Removes a session item by session item name.
     *
     * @since 1.0.0
     *
     * @param string $name The session item name.
     *
     * @throws \InvalidArgumentException If the $name parameter is not a string.
     */
    public function remove($name)
    {
        if (!is_string($name)) {
            throw new \InvalidArgumentException('$name parameter is not a string.');
        }

        self::myInit();

        unset($_SESSION[$name]);
    }

    /**
     * Rewinds the session item collection to to first element.
     *
     * @since 1.0.0
     */
    public function rewind()
    {
        self::myInit();

        reset($_SESSION);
    }

    /**
     * Sets a session item value by session item name.
     *
     * @since 1.0.0
     *
     * @param string $name  The session item name.
     * @param mixed  $value The session item value.
     *
     * @throws \InvalidArgumentException If the $name parameter is not a string.
     */
    public function set($name, $value)
    {
        if (!is_string($name)) {
            throw new \InvalidArgumentException('$name parameter is not a string.');
        }

        self::myInit();

        $_SESSION[$name] = $value;
    }

    /**
     * Returns true if the current session item is valid.
     *
     * @since 1.0.0
     *
     * @return bool True if the current session item is valid.
     */
    public function valid()
    {
        self::myInit();

        return key($_SESSION) !== null;
    }

    /**
     * Initializes session if it is not already initialized.
     */
    private static function myInit()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }
}
