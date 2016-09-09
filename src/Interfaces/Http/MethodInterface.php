<?php

namespace BlueMvc\Core\Interfaces\Http;

/**
 * Interface for Method class.
 */
interface MethodInterface
{
    /**
     * @return string The method name.
     */
    public function getName();

    /**
     * @return true if this is a DELETE method, false otherwise.
     */
    public function isDelete();

    /**
     * @return true if this is a GET method, false otherwise.
     */
    public function isGet();

    /**
     * @return true if this is a POST method, false otherwise.
     */
    public function isPost();

    /**
     * @return true if this is a PUT method, false otherwise.
     */
    public function isPut();

    /**
     * @return string The method as a string.
     */
    public function __toString();
}
