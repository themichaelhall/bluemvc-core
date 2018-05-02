<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */
declare(strict_types=1);

namespace BlueMvc\Core\Interfaces;

/**
 * Interface for RequestCookie class.
 *
 * @since 1.0.0
 */
interface RequestCookieInterface
{
    /**
     * Returns the cookie value.
     *
     * @since 1.0.0
     *
     * @return string The cookie value.
     */
    public function getValue(): string;

    /**
     * Returns the cookie value.
     *
     * @since 1.0.0
     *
     * @return string The cookie value.
     */
    public function __toString(): string;
}
