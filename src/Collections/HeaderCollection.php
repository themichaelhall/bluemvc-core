<?php

/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

declare(strict_types=1);

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
        $this->headers = [];
    }

    /**
     * Adds a header value by header name.
     *
     * @since 1.0.0
     *
     * @param string $name  The header name.
     * @param string $value The header value.
     */
    public function add(string $name, string $value): void
    {
        $key = strtolower($name);
        if (!isset($this->headers[$key])) {
            $this->headers[$key] = [$name, $value];

            return;
        }

        $this->headers[$key] = [$name, $this->headers[$key][1] . ', ' . $value];
    }

    /**
     * Returns the number of headers.
     *
     * @since 1.0.0
     *
     * @return int The number of headers.
     */
    public function count(): int
    {
        return count($this->headers);
    }

    /**
     * Returns the current header value.
     *
     * @since 1.0.0
     *
     * @return string|false The current header value.
     */
    public function current(): string|false
    {
        $current = current($this->headers);

        return $current !== false ? $current[1] : false;
    }

    /**
     * Returns the header value by header name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The header name.
     *
     * @return string|null The header value by header name if it exists, null otherwise.
     */
    public function get(string $name): ?string
    {
        $key = strtolower($name);
        if (!isset($this->headers[$key])) {
            return null;
        }

        return $this->headers[$key][1];
    }

    /**
     * Returns the current header name.
     *
     * @since 1.0.0
     *
     * @return string|null The current header name.
     */
    public function key(): ?string
    {
        $current = current($this->headers);

        return $current !== false ? $current[0] : null;
    }

    /**
     * Moves forwards to the next header.
     *
     * @since 1.0.0
     */
    public function next(): void
    {
        next($this->headers);
    }

    /**
     * Rewinds the header collection to first element.
     *
     * @since 1.0.0
     */
    public function rewind(): void
    {
        reset($this->headers);
    }

    /**
     * Sets a header value by header name.
     *
     * @since 1.0.0
     *
     * @param string $name  The header name.
     * @param string $value The header value.
     */
    public function set(string $name, string $value): void
    {
        $key = strtolower($name);
        $this->headers[$key] = [$name, $value];
    }

    /**
     * Returns true if the current header is valid.
     *
     * @since 1.0.0
     *
     * @return bool True if the current header is valid.
     */
    public function valid(): bool
    {
        return current($this->headers) !== false;
    }

    /**
     * @var array<string, array{string, string}> The headers.
     */
    private array $headers;
}
