<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\Collections;

use BlueMvc\Core\Interfaces\Collections\HeaderCollectionInterface;

/**
 * Class representing a collection of headers.
 *
 * @since 1.0.0
 */
class HeaderCollection implements HeaderCollectionInterface
{
    /**
     * Constructs the collection of headers.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->myHeaders = [];
    }

    /**
     * Returns the number of headers.
     *
     * @since 1.0.0
     *
     * @return int The number of headers.
     */
    public function count()
    {
        return count($this->myHeaders);
    }

    /**
     * Returns the header value by header name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The header name.
     *
     * @throws \InvalidArgumentException If the $name parameter is not a string.
     *
     * @return null The header value by header name if it exists, null otherwise.
     */
    public function get($name)
    {
        if (!is_string($name)) {
            throw new \InvalidArgumentException('$name parameter is not a string.');
        }

        $key = strtolower($name);
        if (!isset($this->myHeaders[$key])) {
            return null;
        }

        return $this->myHeaders[$key][1];
    }

    /**
     * Sets a header value by header name.
     *
     * @since 1.0.0
     *
     * @param string $name  The header name.
     * @param string $value The header value.
     *
     * @throws \InvalidArgumentException If any of the parameters are of invalid type.
     */
    public function set($name, $value)
    {
        if (!is_string($name)) {
            throw new \InvalidArgumentException('$name parameter is not a string.');
        }

        if (!is_string($value)) {
            throw new \InvalidArgumentException('$value parameter is not a string.');
        }

        $key = strtolower($name);
        $this->myHeaders[$key] = [$name, $value];
    }

    /**
     * Adds a header value by header name.
     *
     * @since 1.0.0
     *
     * @param string $name  The header name.
     * @param string $value The header value.
     *
     * @throws \InvalidArgumentException If any of the parameters are of invalid type.
     */
    public function add($name, $value)
    {
        if (!is_string($name)) {
            throw new \InvalidArgumentException('$name parameter is not a string.');
        }

        if (!is_string($value)) {
            throw new \InvalidArgumentException('$value parameter is not a string.');
        }

        $key = strtolower($name);
        if (!isset($this->myHeaders[$key])) {
            $this->myHeaders[$key] = [$name, $value];

            return;
        }

        $this->myHeaders[$key] = [$name, $this->myHeaders[$key][1] . ', ' . $value];
    }

    /**
     * @var array My headers.
     */
    private $myHeaders;
}
