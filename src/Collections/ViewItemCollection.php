<?php

/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

declare(strict_types=1);

namespace BlueMvc\Core\Collections;

use BlueMvc\Core\Interfaces\Collections\ViewItemCollectionInterface;

/**
 * Class representing a collection of view items.
 *
 * @since 1.0.0
 */
class ViewItemCollection implements ViewItemCollectionInterface
{
    /**
     * Constructs the collection of view items.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->items = [];
    }

    /**
     * Returns the number of view items.
     *
     * @since 1.0.0
     *
     * @return int The number of view items.
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Returns the current view item value.
     *
     * @since 1.0.0
     *
     * @return mixed The current view item value.
     */
    public function current()
    {
        return current($this->items);
    }

    /**
     * Returns the view item value by view item name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The view item name.
     *
     * @return mixed|null The view item value by view item name if it exists, null otherwise.
     */
    public function get(string $name)
    {
        if (!isset($this->items[$name])) {
            return null;
        }

        return $this->items[$name];
    }

    /**
     * Returns the current view item name.
     *
     * @since 1.0.0
     *
     * @return string The current view item name.
     */
    public function key(): string
    {
        return strval(key($this->items));
    }

    /**
     * Moves forwards to the next view item.
     *
     * @since 1.0.0
     */
    public function next(): void
    {
        next($this->items);
    }

    /**
     * Rewinds the view item collection to first element.
     *
     * @since 1.0.0
     */
    public function rewind(): void
    {
        reset($this->items);
    }

    /**
     * Sets a view item value by view item name.
     *
     * @since 1.0.0
     *
     * @param string $name  The view item name.
     * @param mixed  $value The view item value.
     */
    public function set(string $name, $value): void
    {
        $this->items[$name] = $value;
    }

    /**
     * Returns true if the current view item is valid.
     *
     * @since 1.0.0
     *
     * @return bool True if the current view item is valid.
     */
    public function valid(): bool
    {
        return key($this->items) !== null;
    }

    /**
     * @var array My view items.
     */
    private $items;
}
