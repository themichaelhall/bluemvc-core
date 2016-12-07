<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\Interfaces;

use BlueMvc\Core\Interfaces\Collections\HeaderCollectionInterface;
use BlueMvc\Core\Interfaces\Http\StatusCodeInterface;

/**
 * Interface for Response class.
 *
 * @since 1.0.0
 */
interface ResponseInterface
{
    /**
     * Returns the content.
     *
     * @since 1.0.0
     *
     * @return string The content.
     */
    public function getContent();

    /**
     * Returns a header value by header name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The header name.
     *
     * @return string|null The header value by header name if it exists, null otherwise.
     */
    public function getHeader($name);

    /**
     * Returns the headers.
     *
     * @since 1.0.0
     *
     * @return HeaderCollectionInterface The headers.
     */
    public function getHeaders();

    /**
     * Returns the request.
     *
     * @since 1.0.0
     *
     * @return RequestInterface The request.
     */
    public function getRequest();

    /**
     * Returns the status code.
     *
     * @since 1.0.0
     *
     * @return StatusCodeInterface The status code.
     */
    public function getStatusCode();

    /**
     * Sets the content.
     *
     * @since 1.0.0
     *
     * @param string $content The content.
     */
    public function setContent($content);

    /**
     * Sets a header.
     *
     * @since 1.0.0
     *
     * @param string $name  The header name.
     * @param string $value The header value.
     */
    public function setHeader($name, $value);

    /**
     * Sets the status code.
     *
     * @since 1.0.0
     *
     * @param StatusCodeInterface $statusCode The status code.
     */
    public function setStatusCode(StatusCodeInterface $statusCode);

    /**
     * Outputs the content.
     *
     * @since 1.0.0
     */
    public function output();
}
