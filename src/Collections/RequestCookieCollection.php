<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\Collections;

use BlueMvc\Core\Interfaces\Collections\RequestCookieCollectionInterface;
use BlueMvc\Core\Interfaces\RequestCookieInterface;

/**
 * Class representing a collection of request cookies.
 *
 * @since 1.0.0
 */
class RequestCookieCollection implements RequestCookieCollectionInterface
{
    /**
     * Constructs the collection of request cookies.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->myRequestCookies = [];
    }

    /**
     * Returns the number of request cookies.
     *
     * @since 1.0.0
     *
     * @return int The number of request cookies.
     */
    public function count()
    {
        return count($this->myRequestCookies);
    }

    /**
     * Returns the current request cookie value.
     *
     * @since 1.0.0
     *
     * @return string The current request cookie value.
     */
    public function current()
    {
        return current($this->myRequestCookies);
    }

    /**
     * Returns the request cookie by name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The name.
     *
     * @throws \InvalidArgumentException If the $name parameter is not a string.
     *
     * @return RequestCookieInterface|null The request cookie by name if it exists, null otherwise.
     */
    public function get($name)
    {
        if (!is_string($name)) {
            throw new \InvalidArgumentException('$name parameter is not a string.');
        }

        if (!isset($this->myRequestCookies[$name])) {
            return null;
        }

        return $this->myRequestCookies[$name];
    }

    /**
     * Returns the current request cookie name.
     *
     * @since 1.0.0
     *
     * @return string The current request cookie name.
     */
    public function key()
    {
        return key($this->myRequestCookies);
    }

    /**
     * Moves forwards to the next request cookie.
     *
     * @since 1.0.0
     */
    public function next()
    {
        next($this->myRequestCookies);
    }

    /**
     * Rewinds the request cookie collection to to first element.
     *
     * @since 1.0.0
     */
    public function rewind()
    {
        reset($this->myRequestCookies);
    }

    /**
     * Sets a request cookie by name.
     *
     * @since 1.0.0
     *
     * @param string                 $name          The name.
     * @param RequestCookieInterface $requestCookie The request cookie.
     *
     * @throws \InvalidArgumentException If the $name parameter is not a string.
     */
    public function set($name, RequestCookieInterface $requestCookie)
    {
        if (!is_string($name)) {
            throw new \InvalidArgumentException('$name parameter is not a string.');
        }

        $this->myRequestCookies[$name] = $requestCookie;
    }

    /**
     * Returns true if the current request cookie is valid.
     *
     * @since 1.0.0
     *
     * @return bool True if the current request cookie is valid.
     */
    public function valid()
    {
        return key($this->myRequestCookies) !== null;
    }

    /**
     * @var RequestCookieInterface[] My request cookies.
     */
    private $myRequestCookies;
}
