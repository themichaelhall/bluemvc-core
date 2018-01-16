<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\Interfaces\Collections;

/**
 * Interface for CustomItemCollection class.
 *
 * @since 1.0.0
 */
interface CustomItemCollectionInterface extends \Countable, \Iterator
{
    /**
     * Returns the custom item value by custom item name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The custom item name.
     *
     * @return mixed|null The custom item value by custom item name if it exists, null otherwise.
     */
    public function get($name);

    /**
     * Sets a custom item value by custom item name.
     *
     * @since 1.0.0
     *
     * @param string $name  The custom item name.
     * @param mixed  $value The custom item value.
     */
    public function set($name, $value);
}
