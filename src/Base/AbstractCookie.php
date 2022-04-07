<?php

/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

declare(strict_types=1);

namespace BlueMvc\Core\Base;

/**
 * Abstract class representing a cookie.
 *
 * @since 1.0.0
 */
abstract class AbstractCookie
{
    /**
     * Constructs a cookie.
     *
     * @since 1.0.0
     *
     * @param string $value The value.
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * Returns the value.
     *
     * @since 1.0.0
     *
     * @return string The value.
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Returns the cookie value.
     *
     * @since 1.0.0
     *
     * @return string The cookie value.
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * @var string The value.
     */
    private string $value;
}
