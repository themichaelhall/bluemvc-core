<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\Interfaces\Collections;

/**
 * Interface for SessionItemCollection class.
 *
 * @since 1.0.0
 */
interface SessionItemCollectionInterface extends \Countable, \Iterator
{
    /**
     * Returns the session item value by session item name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The session item name.
     *
     * @return string|null The session item value by session item name if it exists, null otherwise.
     */
    public function get($name);

    /**
     * Sets a session item value by session item name.
     *
     * @since 1.0.0
     *
     * @param string $name  The session item name.
     * @param mixed  $value The session item value.
     */
    public function set($name, $value);

    /**
     * Removes a session item by session item name.
     *
     * @since 1.0.0
     *
     * @param string $name The session item name.
     */
    public function remove($name);
}
