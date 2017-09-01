<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

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
     * @throws \InvalidArgumentException  If the $name parameter is not a string.
     * @throws InvalidMethodNameException If the method name is invalid.
     */
    public function __construct($name)
    {
        if (!is_string($name)) {
            throw new \InvalidArgumentException('$name parameter is not a string.');
        }

        if (preg_match('/[^a-zA-Z0-9]/', $name, $matches)) {
            throw new InvalidMethodNameException('Method "' . $name . '" contains invalid character "' . $matches[0] . '".');
        }

        $this->myName = $name;
    }

    /**
     * Returns the method name.
     *
     * @since 1.0.0
     *
     * @return string The method name.
     */
    public function getName()
    {
        return $this->myName;
    }

    /**
     * Returns true if this is a DELETE method, false otherwise.
     *
     * @since 1.0.0
     *
     * @return bool True if this is a DELETE method, false otherwise.
     */
    public function isDelete()
    {
        return $this->myName === 'DELETE';
    }

    /**
     * Returns true if this is a GET method, false otherwise.
     *
     * @since 1.0.0
     *
     * @return bool True if this is a GET method, false otherwise.
     */
    public function isGet()
    {
        return $this->myName === 'GET';
    }

    /**
     * Returns true if this is a POST method, false otherwise.
     *
     * @since 1.0.0
     *
     * @return bool True if this is a POST method, false otherwise.
     */
    public function isPost()
    {
        return $this->myName === 'POST';
    }

    /**
     * Returns true if this is a PUT method, false otherwise.
     *
     * @since 1.0.0
     *
     * @return bool True if this is a PUT method, false otherwise.
     */
    public function isPut()
    {
        return $this->myName === 'PUT';
    }

    /**
     * Returns the method as a string.
     *
     * @since 1.0.0
     *
     * @return string The method as a string.
     */
    public function __toString()
    {
        return $this->myName;
    }

    /**
     * @var string My method name.
     */
    private $myName;
}
