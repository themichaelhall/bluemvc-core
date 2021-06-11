<?php

/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

declare(strict_types=1);

namespace BlueMvc\Core\Http;

use BlueMvc\Core\Exceptions\Http\InvalidMethodNameException;
use BlueMvc\Core\Interfaces\Http\MethodInterface;

/**
 * Class representing a http method.
 *
 * @since 1.0.0
 */
class Method implements MethodInterface
{
    /**
     * Method constructor.
     *
     * @since 1.0.0
     *
     * @param string $name The method name.
     *
     * @throws InvalidMethodNameException If the method name is invalid.
     */
    public function __construct(string $name)
    {
        if (preg_match('/[^a-zA-Z0-9]/', $name, $matches)) {
            throw new InvalidMethodNameException('Method "' . $name . '" contains invalid character "' . $matches[0] . '".');
        }

        $this->name = $name;
    }

    /**
     * Returns the method name.
     *
     * @since 1.0.0
     *
     * @return string The method name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns true if this is a DELETE method, false otherwise.
     *
     * @since 1.0.0
     *
     * @return bool True if this is a DELETE method, false otherwise.
     */
    public function isDelete(): bool
    {
        return $this->name === 'DELETE';
    }

    /**
     * Returns true if this is a GET method, false otherwise.
     *
     * @since 1.0.0
     *
     * @return bool True if this is a GET method, false otherwise.
     */
    public function isGet(): bool
    {
        return $this->name === 'GET';
    }

    /**
     * Returns true if this is a POST method, false otherwise.
     *
     * @since 1.0.0
     *
     * @return bool True if this is a POST method, false otherwise.
     */
    public function isPost(): bool
    {
        return $this->name === 'POST';
    }

    /**
     * Returns true if this is a PUT method, false otherwise.
     *
     * @since 1.0.0
     *
     * @return bool True if this is a PUT method, false otherwise.
     */
    public function isPut(): bool
    {
        return $this->name === 'PUT';
    }

    /**
     * Returns the method as a string.
     *
     * @since 1.0.0
     *
     * @return string The method as a string.
     */
    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * @var string My method name.
     */
    private $name;
}
