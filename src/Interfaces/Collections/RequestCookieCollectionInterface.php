<?php

/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

declare(strict_types=1);

namespace BlueMvc\Core\Interfaces\Collections;

use BlueMvc\Core\Interfaces\RequestCookieInterface;
use Countable;
use Iterator;

/**
 * Interface for RequestCookieCollection class.
 *
 * @since 1.0.0
 *
 * @extends Iterator<string, RequestCookieInterface>
 */
interface RequestCookieCollectionInterface extends Countable, Iterator
{
    /**
     * Returns the request cookie by name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The name.
     *
     * @return RequestCookieInterface|null The request cookie by name if it exists, null otherwise.
     */
    public function get(string $name): ?RequestCookieInterface;

    /**
     * Sets a request cookie by name.
     *
     * @since 1.0.0
     *
     * @param string                 $name          The name.
     * @param RequestCookieInterface $requestCookie The request cookie.
     */
    public function set(string $name, RequestCookieInterface $requestCookie): void;
}
