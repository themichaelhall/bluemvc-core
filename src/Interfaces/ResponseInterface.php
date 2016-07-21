<?php

namespace BlueMvc\Core\Interfaces;

use BlueMvc\Core\Interfaces\Http\StatusCodeInterface;

/**
 * Interface for Response class.
 */
interface ResponseInterface
{
    /**
     * @return string The content.
     */
    public function getContent();

    /**
     * @return RequestInterface The request.
     */
    public function getRequest();

    /**
     * @return StatusCodeInterface The status code.
     */
    public function getStatusCode();

    /**
     * Sets the content.
     *
     * @param string $content The content.
     */
    public function setContent($content);

    /**
     * Sets the status code.
     *
     * @param StatusCodeInterface $statusCode The status code.
     */
    public function setStatusCode(StatusCodeInterface $statusCode);

    /**
     * Outputs the content.
     */
    public function output();
}
