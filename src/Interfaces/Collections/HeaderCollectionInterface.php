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
 * Interface for HeaderCollection class.
 *
 * @since 1.0.0
 */
interface HeaderCollectionInterface extends Countable, Iterator
{
    /**
     * Adds a header value by header name.
     *
     * @since 1.0.0
     *
     * @param string $name  The header name.
     * @param string $value The header value.
     */
    public function add(string $name, string $value): void;

    /**
     * Returns the header value by header name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The header name.
     *
     * @return string|null The header value by header name if it exists, null otherwise.
     */
    public function get(string $name): ?string;

    /**
     * Sets a header value by header name.
     *
     * @since 1.0.0
     *
     * @param string $name  The header name.
     * @param string $value The header value.
     */
    public function set(string $name, string $value): void;
}
