<?php

/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

declare(strict_types=1);

namespace BlueMvc\Core;

use BlueMvc\Core\Base\AbstractCookie;
use BlueMvc\Core\Interfaces\RequestCookieInterface;

/**
 * Class representing a request cookie.
 *
 * @since 1.0.0
 */
class RequestCookie extends AbstractCookie implements RequestCookieInterface
{
    /**
     * Constructs a request cookie.
     *
     * @since 1.0.0
     *
     * @param string $value The value.
     */
    public function __construct(string $value)
    {
        parent::__construct($value);
    }
}
