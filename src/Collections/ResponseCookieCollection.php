<?php

/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

declare(strict_types=1);

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
        $this->responseCookies = [];
    }

    /**
     * Returns the number of response cookies.
     *
     * @since 1.0.0
     *
     * @return int The number of response cookies.
     */
    public function count(): int
    {
        return count($this->responseCookies);
    }

    /**
     * Returns the current response cookie value.
     *
     * @since 1.0.0
     *
     * @return ResponseCookieInterface The current response cookie value.
     */
    public function current(): ResponseCookieInterface
    {
        return current($this->responseCookies);
    }

    /**
     * Returns the response cookie by name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The name.
     *
     * @return ResponseCookieInterface|null The response cookie by name if it exists, null otherwise.
     */
    public function get(string $name): ?ResponseCookieInterface
    {
        if (!isset($this->responseCookies[$name])) {
            return null;
        }

        return $this->responseCookies[$name];
    }

    /**
     * Returns the current response cookie name.
     *
     * @since 1.0.0
     *
     * @return string The current response cookie name.
     */
    public function key(): string
    {
        return strval(key($this->responseCookies));
    }

    /**
     * Moves forwards to the next response cookie.
     *
     * @since 1.0.0
     */
    public function next(): void
    {
        next($this->responseCookies);
    }

    /**
     * Rewinds the response cookie collection to first element.
     *
     * @since 1.0.0
     */
    public function rewind(): void
    {
        reset($this->responseCookies);
    }

    /**
     * Sets a response cookie by name.
     *
     * @since 1.0.0
     *
     * @param string                  $name           The name.
     * @param ResponseCookieInterface $responseCookie The response cookie.
     */
    public function set(string $name, ResponseCookieInterface $responseCookie): void
    {
        $this->responseCookies[$name] = $responseCookie;
    }

    /**
     * Returns true if the current response cookie is valid.
     *
     * @since 1.0.0
     *
     * @return bool True if the current response cookie is valid.
     */
    public function valid(): bool
    {
        return key($this->responseCookies) !== null;
    }

    /**
     * @var ResponseCookieInterface[] My response cookies.
     */
    private $responseCookies;
}
