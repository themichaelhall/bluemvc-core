<?php

/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

declare(strict_types=1);

namespace BlueMvc\Core\Collections;

use BlueMvc\Core\Interfaces\Collections\CustomItemCollectionInterface;

/**
 * Class representing a collection of custom items.
 *
 * @since 1.0.0
 */
class CustomItemCollection implements CustomItemCollectionInterface
{
    /**
     * Constructs the collection of custom items.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->items = [];
    }

    /**
     * Returns the number of custom items.
     *
     * @since 1.0.0
     *
     * @return int The number of custom items.
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Returns the current custom item value.
     *
     * @since 1.0.0
     *
     * @return mixed The current custom item value.
     */
    public function current()
    {
        return current($this->items);
    }

    /**
     * Returns the custom item value by custom item name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The custom item name.
     *
     * @return mixed|null The custom item value by custom item name if it exists, null otherwise.
     */
    public function get(string $name)
    {
        if (!isset($this->items[$name])) {
            return null;
        }

        return $this->items[$name];
    }

    /**
     * Returns the current custom item name.
     *
     * @since 1.0.0
     *
     * @return string The current custom item name.
     */
    public function key(): string
    {
        return strval(key($this->items));
    }

    /**
     * Moves forwards to the next custom item.
     *
     * @since 1.0.0
     */
    public function next(): void
    {
        next($this->items);
    }

    /**
     * Rewinds the custom item collection to to first element.
     *
     * @since 1.0.0
     */
    public function rewind(): void
    {
        reset($this->items);
    }

    /**
     * Sets a custom item value by custom item name.
     *
     * @since 1.0.0
     *
     * @param string $name  The custom item name.
     * @param mixed  $value The custom item value.
     */
    public function set(string $name, $value): void
    {
        $this->items[$name] = $value;
    }

    /**
     * Returns true if the current custom item is valid.
     *
     * @since 1.0.0
     *
     * @return bool True if the current custom item is valid.
     */
    public function valid(): bool
    {
        return key($this->items) !== null;
    }

    /**
     * @var array My custom items.
     */
    private $items;
}
