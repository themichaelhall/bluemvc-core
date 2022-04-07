<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Helpers\TestCollections;

use BlueMvc\Core\Interfaces\Collections\SessionItemCollectionInterface;

/**
 * A basic test session item collection.
 */
class BasicTestSessionItemCollection implements SessionItemCollectionInterface
{
    /**
     * Constructs the collection of session items.
     */
    public function __construct()
    {
        $this->items = [];
    }

    /**
     * Returns the number of session items.
     *
     * @return int The number of session items.
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Returns the current session item value.
     *
     * @return mixed The current session item value.
     */
    public function current(): mixed
    {
        return current($this->items);
    }

    /**
     * Returns the session item value by session item name if it exists, null otherwise.
     *
     * @param string $name The session item name.
     *
     * @return mixed The session item value by session item name if it exists, null otherwise.
     */
    public function get(string $name): mixed
    {
        if (!isset($this->items[$name])) {
            return null;
        }

        return $this->items[$name];
    }

    /**
     * Returns the current session item name.
     *
     * @return string The current session item name.
     */
    public function key(): string
    {
        return strval(key($this->items));
    }

    /**
     * Moves forwards to the next session item.
     */
    public function next(): void
    {
        next($this->items);
    }

    /**
     * Removes a session item by session item name.
     *
     * @param string $name The session item name.
     */
    public function remove(string $name): void
    {
        unset($this->items[$name]);
    }

    /**
     * Rewinds the session item collection to first element.
     */
    public function rewind(): void
    {
        reset($this->items);
    }

    /**
     * Sets a session item value by session item name.
     *
     * @param string $name  The session item name.
     * @param mixed  $value The session item value.
     */
    public function set(string $name, mixed $value): void
    {
        $this->items[$name] = $value;
    }

    /**
     * Returns true if the current session item is valid.
     *
     * @return bool True if the current session item is valid.
     */
    public function valid(): bool
    {
        return key($this->items) !== null;
    }

    /**
     * @var array The session items.
     */
    private array $items;
}
