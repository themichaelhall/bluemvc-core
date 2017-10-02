<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core;

use BlueMvc\Core\Interfaces\RequestCookieInterface;

/**
 * Class representing a request cookie.
 *
 * @since 1.0.0
 */
class RequestCookie implements RequestCookieInterface
{
    /**
     * Constructs a request cookie.
     *
     * @since 1.0.0
     *
     * @param string $value The value.
     *
     * @throws \InvalidArgumentException If the $value parameter is not a string.
     */
    public function __construct($value)
    {
        if (!is_string($value)) {
            throw new \InvalidArgumentException('$value parameter is not a string.');
        }

        $this->myValue = $value;
    }

    /**
     * Returns the value.
     *
     * @since 1.0.0
     *
     * @return string The value.
     */
    public function getValue()
    {
        return $this->myValue;
    }

    /**
     * @var string My value.
     */
    private $myValue;
}
