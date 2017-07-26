<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\Interfaces\Collections;

/**
 * Interface for ViewItemCollection class.
 *
 * @since 1.0.0
 */
interface ViewItemCollectionInterface extends \Countable
{
    /**
     * Returns the view item value by view item name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The view item name.
     *
     * @return string|null The view item value by view item name if it exists, null otherwise.
     */
    public function get($name);
}
