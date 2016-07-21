<?php

namespace BlueMvc\Core\Interfaces\Http;

/**
 * Interface for StatusCode class.
 */
interface StatusCodeInterface
{
    /**
     * @return int The code.
     */
    public function getCode();

    /**
     * @return string The description.
     */
    public function getDescription();

    /**
     * @return string The status code as a string.
     */
    public function __toString();
}
