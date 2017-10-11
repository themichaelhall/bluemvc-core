<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\Interfaces;

use DataTypes\UrlPath;

/**
 * Interface for ResponseCookie class.
 *
 * @since 1.0.0
 */
interface ResponseCookieInterface
{
    /**
     * Returns the expiry time or null if no expiry time.
     *
     * @since 1.0.0
     *
     * @return \DateTimeImmutable|null The expiry time or null if no expiry time.
     */
    public function getExpiry();

    /**
     * Returns the path or null if no path.
     *
     * @since 1.0.0
     *
     * @return UrlPath|null The path or null if no path.
     */
    public function getPath();

    /**
     * Returns the value.
     *
     * @since 1.0.0
     *
     * @return string The value.
     */
    public function getValue();

    /**
     * Returns the value.
     *
     * @since 1.0.0
     *
     * @return string The value.
     */
    public function __toString();
}
