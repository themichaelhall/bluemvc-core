<?php

namespace BlueMvc\Core;

use BlueMvc\Core\Interfaces\RequestInterface;
use BlueMvc\Core\Interfaces\ResponseInterface;

/**
 * Class representing a web response.
 */
class Response implements ResponseInterface
{
    /**
     * Constructs a response.
     *
     * @param RequestInterface $request The request.
     */
    public function __construct(RequestInterface $request)
    {
        $this->myRequest = $request;
    }

    /**
     * @return RequestInterface The request.
     */
    public function getRequest()
    {
        return $this->myRequest;
    }

    /**
     * @var RequestInterface My request.
     */
    private $myRequest;
}
