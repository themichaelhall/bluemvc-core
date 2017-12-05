<?php

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
        $this->myItems = [];
    }

    /**
     * Returns the number of session items.
     *
     * @return int The number of session items.
     */
    public function count()
    {
        return count($this->myItems);
    }

    /**
     * Returns the current session item value.
     *
     * @return string The current session item value.
     */
    public function current()
    {
        return current($this->myItems);
    }

    /**
     * Returns the session item value by session item name if it exists, null otherwise.
     *
     * @param string $name The session item name.
     *
     * @return string|null The session item value by session item name if it exists, null otherwise.
     */
    public function get($name)
    {
        if (!isset($this->myItems[$name])) {
            return null;
        }

        return $this->myItems[$name];
    }

    /**
     * Returns the current session item name.
     *
     * @return string The current session item name.
     */
    public function key()
    {
        return key($this->myItems);
    }

    /**
     * Moves forwards to the next session item.
     */
    public function next()
    {
        next($this->myItems);
    }

    /**
     * Removes a session item by session item name.
     *
     * @param string $name The session item name.
     */
    public function remove($name)
    {
        unset($this->myItems[$name]);
    }

    /**
     * Rewinds the session item collection to to first element.
     */
    public function rewind()
    {
        reset($this->myItems);
    }

    /**
     * Sets a session item value by session item name.
     *
     * @param string $name  The session item name.
     * @param mixed  $value The session item value.
     */
    public function set($name, $value)
    {
        $this->myItems[$name] = $value;
    }

    /**
     * Returns true if the current session item is valid.
     *
     * @return bool True if the current session item is valid.
     */
    public function valid()
    {
        return key($this->myItems) !== null;
    }

    /**
     * @var array My session items.
     */
    private $myItems;
}
