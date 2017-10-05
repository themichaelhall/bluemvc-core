<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core;

use BlueMvc\Core\Base\AbstractCookie;
use BlueMvc\Core\Interfaces\ResponseCookieInterface;

/**
 * Class representing a response cookie.
 *
 * @since 1.0.0
 */
class ResponseCookie extends AbstractCookie implements ResponseCookieInterface
{
    /**
     * Constructs a response cookie.
     *
     * @since 1.0.0
     *
     * @param string $value The value.
     *
     * @throws \InvalidArgumentException If the $value parameter is not a string.
     */
    public function __construct($value)
    {
        parent::__construct($value);
    }
}
