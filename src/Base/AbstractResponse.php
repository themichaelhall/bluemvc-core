<?php

namespace BlueMvc\Core\Base;

use BlueMvc\Core\Interfaces\RequestInterface;
use BlueMvc\Core\Interfaces\ResponseInterface;

/**
 * Abstract class representing a web response.
 */
abstract class AbstractResponse implements ResponseInterface
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
