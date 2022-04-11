<?php

/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

declare(strict_types=1);

namespace BlueMvc\Core\Interfaces;

use Stringable;

/**
 * Interface for RequestCookie class.
 *
 * @since 1.0.0
 */
interface RequestCookieInterface extends Stringable
{
    /**
     * Returns the cookie value.
     *
     * @since 1.0.0
     *
     * @return string The cookie value.
     */
    public function getValue(): string;
}
