<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\Collections;

use BlueMvc\Core\Interfaces\Collections\ResponseCookieCollectionInterface;
use BlueMvc\Core\Interfaces\ResponseCookieInterface;

/**
 * Class representing a collection of response cookies.
 *
 * @since 1.0.0
 */
class ResponseCookieCollection implements ResponseCookieCollectionInterface
{
    /**
     * Constructs the collection of response cookies.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->myResponseCookies = [];
    }

    /**
     * Returns the number of response cookies.
     *
     * @since 1.0.0
     *
     * @return int The number of response cookies.
     */
    public function count()
    {
        return count($this->myResponseCookies);
    }

    /**
     * Returns the current response cookie value.
     *
     * @since 1.0.0
     *
     * @return string The current response cookie value.
     */
    public function current()
    {
        return current($this->myResponseCookies);
    }

    /**
     * Returns the response cookie by name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The name.
     *
     * @throws \InvalidArgumentException If the $name parameter is not a string.
     *
     * @return ResponseCookieInterface|null The response cookie by name if it exists, null otherwise.
     */
    public function get($name)
    {
        if (!is_string($name)) {
            throw new \InvalidArgumentException('$name parameter is not a string.');
        }

        if (!isset($this->myResponseCookies[$name])) {
            return null;
        }

        return $this->myResponseCookies[$name];
    }

    /**
     * Returns the current response cookie name.
     *
     * @since 1.0.0
     *
     * @return string The current response cookie name.
     */
    public function key()
    {
        return key($this->myResponseCookies);
    }

    /**
     * Moves forwards to the next response cookie.
     *
     * @since 1.0.0
     */
    public function next()
    {
        next($this->myResponseCookies);
    }

    /**
     * Rewinds the response cookie collection to to first element.
     *
     * @since 1.0.0
     */
    public function rewind()
    {
        reset($this->myResponseCookies);
    }

    /**
     * Sets a response cookie by name.
     *
     * @since 1.0.0
     *
     * @param string                  $name           The name.
     * @param ResponseCookieInterface $responseCookie The response cookie.
     *
     * @throws \InvalidArgumentException If the $name parameter is not a string.
     */
    public function set($name, ResponseCookieInterface $responseCookie)
    {
        if (!is_string($name)) {
            throw new \InvalidArgumentException('$name parameter is not a string.');
        }

        $this->myResponseCookies[$name] = $responseCookie;
    }

    /**
     * Returns true if the current response cookie is valid.
     *
     * @since 1.0.0
     *
     * @return bool True if the current response cookie is valid.
     */
    public function valid()
    {
        return key($this->myResponseCookies) !== null;
    }

    /**
     * @var ResponseCookieInterface[] My response cookies.
     */
    private $myResponseCookies;
}
