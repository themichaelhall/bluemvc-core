<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\Interfaces\Collections;

/**
 * Interface for HeaderCollection class.
 *
 * @since 1.0.0
 */
interface HeaderCollectionInterface extends \Countable, \Iterator
{
    /**
     * Adds a header value by header name.
     *
     * @since 1.0.0
     *
     * @param string $name  The header name.
     * @param string $value The header value.
     */
    public function add($name, $value);

    /**
     * Returns the number of headers.
     *
     * @since 1.0.0
     *
     * @return int The number of headers.
     */
    public function count();

    /**
     * Returns the current header value.
     *
     * @since 1.0.0
     *
     * @return string The current header value.
     */
    public function current();

    /**
     * Returns the header value by header name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The header name.
     *
     * @return string|null The header value by header name if it exists, null otherwise.
     */
    public function get($name);

    /**
     * Returns the current header name.
     *
     * @since 1.0.0
     *
     * @return string The current header name.
     */
    public function key();

    /**
     * Moves forwards to the next header.
     *
     * @since 1.0.0
     */
    public function next();

    /**
     * Rewinds the header collection to to first element.
     *
     * @since 1.0.0
     */
    public function rewind();

    /**
     * Sets a header value by header name.
     *
     * @since 1.0.0
     *
     * @param string $name  The header name.
     * @param string $value The header value.
     */
    public function set($name, $value);

    /**
     * Returns true if the current header is valid.
     *
     * @since 1.0.0
     *
     * @return bool True if the current header is valid.
     */
    public function valid();
}
