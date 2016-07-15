<?php

namespace BlueMvc\Core\Interfaces;

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
     * Sets the content.
     *
     * @param string $content The content.
     */
    public function setContent($content);
}
