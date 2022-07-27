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
 * Interface for SessionItemCollection class.
 *
 * @since 1.0.0
 *
 * @extends Iterator<string, mixed>
 */
interface SessionItemCollectionInterface extends Countable, Iterator
{
    /**
     * Returns the session item value by session item name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The session item name.
     *
     * @return mixed The session item value by session item name if it exists, null otherwise.
     */
    public function get(string $name): mixed;

    /**
     * Sets a session item value by session item name.
     *
     * @since 1.0.0
     *
     * @param string $name  The session item name.
     * @param mixed  $value The session item value.
     */
    public function set(string $name, mixed $value): void;

    /**
     * Removes a session item by session item name.
     *
     * @since 1.0.0
     *
     * @param string $name The session item name.
     */
    public function remove(string $name): void;
}
