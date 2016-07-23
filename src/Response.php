<?php

namespace BlueMvc\Core;

use BlueMvc\Core\Base\AbstractResponse;
use BlueMvc\Core\Interfaces\RequestInterface;

/**
 * Class representing a web response.
 */
class Response extends AbstractResponse
{
    /**
     * Constructs a response.
     *
     * @param RequestInterface $request The request.
     */
    public function __construct(RequestInterface $request)
    {
        parent::__construct($request);
    }

    /**
     * Outputs the content.
     */
    public function output()
    {
        header('HTTP/1.1 ' . $this->getStatusCode());
        echo $this->getContent();
    }
}
