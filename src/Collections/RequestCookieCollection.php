<?php

/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

declare(strict_types=1);

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
        $this->requestCookies = [];
    }

    /**
     * Returns the number of request cookies.
     *
     * @since 1.0.0
     *
     * @return int The number of request cookies.
     */
    public function count(): int
    {
        return count($this->requestCookies);
    }

    /**
     * Returns the current request cookie value.
     *
     * @since 1.0.0
     *
     * @return RequestCookieInterface The current request cookie value.
     */
    public function current(): RequestCookieInterface
    {
        return current($this->requestCookies);
    }

    /**
     * Returns the request cookie by name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The name.
     *
     * @return RequestCookieInterface|null The request cookie by name if it exists, null otherwise.
     */
    public function get(string $name): ?RequestCookieInterface
    {
        if (!isset($this->requestCookies[$name])) {
            return null;
        }

        return $this->requestCookies[$name];
    }

    /**
     * Returns the current request cookie name.
     *
     * @since 1.0.0
     *
     * @return string The current request cookie name.
     */
    public function key(): string
    {
        return strval(key($this->requestCookies));
    }

    /**
     * Moves forwards to the next request cookie.
     *
     * @since 1.0.0
     */
    public function next(): void
    {
        next($this->requestCookies);
    }

    /**
     * Rewinds the request cookie collection to first element.
     *
     * @since 1.0.0
     */
    public function rewind(): void
    {
        reset($this->requestCookies);
    }

    /**
     * Sets a request cookie by name.
     *
     * @since 1.0.0
     *
     * @param string                 $name          The name.
     * @param RequestCookieInterface $requestCookie The request cookie.
     */
    public function set(string $name, RequestCookieInterface $requestCookie): void
    {
        $this->requestCookies[$name] = $requestCookie;
    }

    /**
     * Returns true if the current request cookie is valid.
     *
     * @since 1.0.0
     *
     * @return bool True if the current request cookie is valid.
     */
    public function valid(): bool
    {
        return key($this->requestCookies) !== null;
    }

    /**
     * @var RequestCookieInterface[] My request cookies.
     */
    private $requestCookies;
}
