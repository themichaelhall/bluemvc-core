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
     * Returns the view item value by view item name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The view item name.
     *
     * @return string|null The view item value by view item name if it exists, null otherwise.
     */
    public function get($name)
    {
        return null; // fixme
    }

    /**
     * @var array My view items.
     */
    private $myItems;
}
