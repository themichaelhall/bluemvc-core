<?php

/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

declare(strict_types=1);

namespace BlueMvc\Core\Interfaces\Collections;

use Countable;
use Iterator;

/**
 * Interface for ViewItemCollection class.
 *
 * @since 1.0.0
 */
interface ViewItemCollectionInterface extends Countable, Iterator
{
    /**
     * Returns the view item value by view item name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The view item name.
     *
     * @return mixed The view item value by view item name if it exists, null otherwise.
     */
    public function get(string $name): mixed;

    /**
     * Sets a view item value by view item name.
     *
     * @since 1.0.0
     *
     * @param string $name  The view item name.
     * @param mixed  $value The view item value.
     */
    public function set(string $name, mixed $value): void;
}
