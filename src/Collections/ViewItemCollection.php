<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

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
        $this->myItems = [];
    }

    /**
     * Returns the number of view items.
     *
     * @since 1.0.0
     *
     * @return int The number of view items.
     */
    public function count()
    {
        return count($this->myItems);
    }

    /**
     * Returns the current view item value.
     *
     * @since 1.0.0
     *
     * @return string The current view item value.
     */
    public function current()
    {
        return current($this->myItems);
    }

    /**
     * Returns the view item value by view item name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The view item name.
     *
     * @throws \InvalidArgumentException If the $name parameter is not a string.
     *
     * @return string|null The view item value by view item name if it exists, null otherwise.
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
     * Returns the current view item name.
     *
     * @since 1.0.0
     *
     * @return string The current view item name.
     */
    public function key()
    {
        return key($this->myItems);
    }

    /**
     * Moves forwards to the next view item.
     *
     * @since 1.0.0
     */
    public function next()
    {
        next($this->myItems);
    }

    /**
     * Rewinds the view item collection to to first element.
     *
     * @since 1.0.0
     */
    public function rewind()
    {
        reset($this->myItems);
    }

    /**
     * Sets a view item value by view item name.
     *
     * @since 1.0.0
     *
     * @param string $name  The view item name.
     * @param mixed  $value The view item value.
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
     * Returns true if the current view item is valid.
     *
     * @since 1.0.0
     *
     * @return bool True if the current view item is valid.
     */
    public function valid()
    {
        return key($this->myItems) !== null;
    }

    /**
     * @var array My view items.
     */
    private $myItems;
}
