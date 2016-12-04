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

        return null;
    }

    /**
     * @var array My headers.
     */
    private $myHeaders;
}
