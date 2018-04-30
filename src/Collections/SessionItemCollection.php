<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */
declare(strict_types=1);

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
    public function count(): int
    {
        self::doInit();

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
        self::doInit();

        return current($_SESSION);
    }

    /**
     * Returns the session item value by session item name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The session item name.
     *
     * @return mixed|null The session item value by session item name if it exists, null otherwise.
     */
    public function get(string $name)
    {
        self::doInit();

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
    public function key(): string
    {
        self::doInit();

        return strval(key($_SESSION));
    }

    /**
     * Moves forwards to the next session item.
     *
     * @since 1.0.0
     */
    public function next(): void
    {
        self::doInit();

        next($_SESSION);
    }

    /**
     * Removes a session item by session item name.
     *
     * @since 1.0.0
     *
     * @param string $name The session item name.
     */
    public function remove(string $name): void
    {
        self::doInit();

        unset($_SESSION[$name]);
    }

    /**
     * Rewinds the session item collection to to first element.
     *
     * @since 1.0.0
     */
    public function rewind(): void
    {
        self::doInit();

        reset($_SESSION);
    }

    /**
     * Sets a session item value by session item name.
     *
     * @since 1.0.0
     *
     * @param string $name  The session item name.
     * @param mixed  $value The session item value.
     */
    public function set(string $name, $value): void
    {
        self::doInit();

        $_SESSION[$name] = $value;
    }

    /**
     * Returns true if the current session item is valid.
     *
     * @since 1.0.0
     *
     * @return bool True if the current session item is valid.
     */
    public function valid(): bool
    {
        self::doInit();

        return key($_SESSION) !== null;
    }

    /**
     * Initializes session if it is not already initialized.
     */
    private static function doInit(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start([
                'cookie_httponly' => true,
                'use_strict_mode' => true,
            ]);
        }
    }
}
