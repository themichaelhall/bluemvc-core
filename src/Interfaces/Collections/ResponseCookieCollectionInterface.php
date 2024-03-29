<?php

/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

declare(strict_types=1);

namespace BlueMvc\Core\Interfaces\Collections;

use BlueMvc\Core\Interfaces\ResponseCookieInterface;
use Countable;
use Iterator;

/**
 * Interface for ResponseCookieCollection class.
 *
 * @since 1.0.0
 *
 * @extends Iterator<string, ResponseCookieInterface>
 */
interface ResponseCookieCollectionInterface extends Countable, Iterator
{
    /**
     * Returns the response cookie by name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The name.
     *
     * @return ResponseCookieInterface|null The response cookie by name if it exists, null otherwise.
     */
    public function get(string $name): ?ResponseCookieInterface;

    /**
     * Sets a response cookie by name.
     *
     * @since 1.0.0
     *
     * @param string                  $name           The name.
     * @param ResponseCookieInterface $responseCookie The response cookie.
     */
    public function set(string $name, ResponseCookieInterface $responseCookie): void;
}
