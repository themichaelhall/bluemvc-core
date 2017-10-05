<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\Base;

/**
 * Abstract class representing a cookie.
 *
 * @since 1.0.0
 */
abstract class AbstractCookie
{
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
     * Returns the cookie value.
     *
     * @since 1.0.0
     *
     * @return string The cookie value.
     */
    public function __toString()
    {
        return $this->myValue;
    }

    /**
     * Constructs a cookie.
     *
     * @since 1.0.0
     *
     * @param string $value The value.
     *
     * @throws \InvalidArgumentException If the $value parameter is not a string.
     */
    protected function __construct($value)
    {
        if (!is_string($value)) {
            throw new \InvalidArgumentException('$value parameter is not a string.');
        }

        $this->myValue = $value;
    }

    /**
     * @var string My value.
     */
    private $myValue;
}
