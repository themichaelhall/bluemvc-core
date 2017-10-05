<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\Interfaces;

/**
 * Interface for ResponseCookie class.
 *
 * @since 1.0.0
 */
interface ResponseCookieInterface
{
    /**
     * Returns the cookie value.
     *
     * @since 1.0.0
     *
     * @return string The cookie value.
     */
    public function getValue();

    /**
     * Returns the cookie value.
     *
     * @since 1.0.0
     *
     * @return string The cookie value.
     */
    public function __toString();
}
