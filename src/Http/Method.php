<?php

namespace BlueMvc\Core\Http;

use BlueMvc\Core\Interfaces\Http\MethodInterface;

/**
 * Class representing a http method.
 */
class Method implements MethodInterface
{
    /**
     * Method constructor.
     *
     * @param string $name The method name.
     */
    public function __construct($name)
    {
        assert(is_string($name));

        $this->myName = $name;
        // fixme: handle invalid name
    }

    /**
     * @return string The method name.
     */
    public function getName()
    {
        return $this->myName;
    }

    /**
     * @return True if this is a DELETE method, false otherwise.
     */
    public function isDelete()
    {
        return $this->myName === 'DELETE';
    }

    /**
     * @return True if this is a GET method, false otherwise.
     */
    public function isGet()
    {
        return $this->myName === 'GET';
    }

    /**
     * @return True if this is a POST method, false otherwise.
     */
    public function isPost()
    {
        return $this->myName === 'POST';
    }

    /**
     * @return True if this is a PUT method, false otherwise.
     */
    public function isPut()
    {
        return $this->myName === 'PUT';
    }

    /**
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
