<?php

namespace BlueMvc\Core\Interfaces;

/**
 * Interface for Response class.
 */
interface ResponseInterface
{
    /**
     * @return RequestInterface The request.
     */
    public function getRequest();
}
