<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

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
        $this->myItems = [];
    }

    /**
     * Returns the number of custom items.
     *
     * @since 1.0.0
     *
     * @return int The number of custom items.
     */
    public function count()
    {
        return count($this->myItems);
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
        return current($this->myItems);
    }

    /**
     * Returns the custom item value by custom item name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The custom item name.
     *
     * @throws \InvalidArgumentException If the $name parameter is not a string.
     *
     * @return mixed|null The custom item value by custom item name if it exists, null otherwise.
     */
    public function get($name)
    {
        if (!is_string($name)) {
            throw new \InvalidArgumentException('$name parameter is not a string.');
        }

        if (!isset($this->myItems[$name])) {
            return null;
        }

        return $this->myItems[$name];
    }

    /**
     * Returns the current custom item name.
     *
     * @since 1.0.0
     *
     * @return string The current custom item name.
     */
    public function key()
    {
        return key($this->myItems);
    }

    /**
     * Moves forwards to the next custom item.
     *
     * @since 1.0.0
     */
    public function next()
    {
        next($this->myItems);
    }

    /**
     * Rewinds the custom item collection to to first element.
     *
     * @since 1.0.0
     */
    public function rewind()
    {
        reset($this->myItems);
    }

    /**
     * Sets a custom item value by custom item name.
     *
     * @since 1.0.0
     *
     * @param string $name  The custom item name.
     * @param mixed  $value The custom item value.
     *
     * @throws \InvalidArgumentException If the $name parameter is not a string.
     */
    public function set($name, $value)
    {
        if (!is_string($name)) {
            throw new \InvalidArgumentException('$name parameter is not a string.');
        }

        $this->myItems[$name] = $value;
    }

    /**
     * Returns true if the current custom item is valid.
     *
     * @since 1.0.0
     *
     * @return bool True if the current custom item is valid.
     */
    public function valid()
    {
        return key($this->myItems) !== null;
    }

    /**
     * @var array My custom items.
     */
    private $myItems;
}
