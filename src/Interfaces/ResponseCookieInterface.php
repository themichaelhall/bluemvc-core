<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\Interfaces;

use DataTypes\Interfaces\HostInterface;
use DataTypes\Interfaces\UrlPathInterface;

/**
 * Interface for ResponseCookie class.
 *
 * @since 1.0.0
 */
interface ResponseCookieInterface
{
    /**
     * Returns the domain or null if no domain.
     *
     * @since 1.0.0
     *
     * @return HostInterface|null The domain or null if no domain.
     */
    public function getDomain();

    /**
     * Returns the expiry time or null if no expiry time.
     *
     * @since 1.0.0
     *
     * @return \DateTimeInterface|null The expiry time or null if no expiry time.
     */
    public function getExpiry();

    /**
     * Returns the path or null if no path.
     *
     * @since 1.0.0
     *
     * @return UrlPathInterface|null The path or null if no path.
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
