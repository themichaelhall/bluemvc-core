<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\Interfaces\Collections;

use BlueMvc\Core\Interfaces\ResponseCookieInterface;

/**
 * Interface for ResponseCookieCollection class.
 *
 * @since 1.0.0
 */
interface ResponseCookieCollectionInterface extends \Countable, \Iterator
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
    public function get($name);

    /**
     * Sets a response cookie by name.
     *
     * @since 1.0.0
     *
     * @param string                  $name           The name.
     * @param ResponseCookieInterface $responseCookie The response cookie.
     */
    public function set($name, ResponseCookieInterface $responseCookie);
}
