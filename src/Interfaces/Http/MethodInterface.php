<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\Interfaces\Http;

/**
 * Interface for Method class.
 *
 * @since 1.0.0
 */
interface MethodInterface
{
    /**
     * Returns the method name.
     *
     * @since 1.0.0
     *
     * @return string The method name.
     */
    public function getName();

    /**
     * Returns true if this is a DELETE method, false otherwise.
     *
     * @since 1.0.0
     *
     * @return bool True if this is a DELETE method, false otherwise.
     */
    public function isDelete();

    /**
     * Returns true if this is a GET method, false otherwise.
     *
     * @since 1.0.0
     *
     * @return bool True if this is a GET method, false otherwise.
     */
    public function isGet();

    /**
     * Returns true if this is a POST method, false otherwise.
     *
     * @since 1.0.0
     *
     * @return bool True if this is a POST method, false otherwise.
     */
    public function isPost();

    /**
     * Returns true if this is a PUT method, false otherwise.
     *
     * @since 1.0.0
     *
     * @return bool True if this is a PUT method, false otherwise.
     */
    public function isPut();

    /**
     * Returns the method as a string.
     *
     * @since 1.0.0
     *
     * @return string The method as a string.
     */
    public function __toString();
}
