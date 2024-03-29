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
     * SessionItemCollection constructor.
     *
     * @since 2.1.0
     *
     * @param array<string, mixed> $options The options to pass to session_start() method.
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;
        $this->isInitialized = false;
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
        $this->doInit();

        return count($_SESSION);
    }

    /**
     * Returns the current session item value.
     *
     * @since 1.0.0
     *
     * @return mixed The current session item value.
     */
    public function current(): mixed
    {
        $this->doInit();

        return current($_SESSION);
    }

    /**
     * Returns the session item value by session item name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The session item name.
     *
     * @return mixed The session item value by session item name if it exists, null otherwise.
     */
    public function get(string $name): mixed
    {
        $this->doInit();

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
        $this->doInit();

        return strval(key($_SESSION));
    }

    /**
     * Moves forwards to the next session item.
     *
     * @since 1.0.0
     */
    public function next(): void
    {
        $this->doInit();

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
        if ($this->hasNoSession()) {
            return;
        }

        $this->doInit(true);

        unset($_SESSION[$name]);
    }

    /**
     * Rewinds the session item collection to first element.
     *
     * @since 1.0.0
     */
    public function rewind(): void
    {
        $this->doInit();

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
    public function set(string $name, mixed $value): void
    {
        $this->doInit(true);

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
        $this->doInit();

        return key($_SESSION) !== null;
    }

    /**
     * Initializes session if it is not already initialized.
     *
     * @param bool $write If true, initialize for write, if false initialize for read-only.
     */
    private function doInit(bool $write = false): void
    {
        if ($this->isInitialized) {
            return;
        }

        if (!$write && $this->hasNoSession()) {
            $_SESSION = [];

            return;
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start($this->options);
        }

        $this->isInitialized = true;
    }

    /**
     * Checks if there is no session available, without unnecessary creation of session cookie.
     *
     * @return bool True if there is no session, false otherwise.
     */
    private function hasNoSession(): bool
    {
        if ($this->isInitialized) {
            return false;
        }

        if (!boolval(ini_get('session.use_only_cookies'))) {
            // Can't tell for sure, since only cookie is checked.
            return false;
        }

        if (isset($_COOKIE[session_name()])) {
            return false;
        }

        return true;
    }

    /**
     * @var array<string, mixed> The options to pass to session_start() method.
     */
    private array $options;

    /**
     * @var bool True if session is initialized, false otherwise.
     */
    private bool $isInitialized;
}
