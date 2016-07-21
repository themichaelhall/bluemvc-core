<?php

namespace BlueMvc\Core\Base;

use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Interfaces\Http\StatusCodeInterface;
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
        $this->myContent = '';
        $this->myStatusCode = new StatusCode(StatusCode::OK);
    }

    /**
     * @return string The content.
     */
    public function getContent()
    {
        return $this->myContent;
    }

    /**
     * @return RequestInterface The request.
     */
    public function getRequest()
    {
        return $this->myRequest;
    }

    /**
     * @return StatusCodeInterface The status code.
     */
    public function getStatusCode()
    {
        return $this->myStatusCode;
    }

    /**
     * Sets the content.
     *
     * @param string $content The content.
     */
    public function setContent($content)
    {
        $this->myContent = $content;
    }

    /**
     * Sets the status code.
     *
     * @param StatusCodeInterface $statusCode The status code.
     */
    public function setStatusCode(StatusCodeInterface $statusCode)
    {
        $this->myStatusCode = $statusCode;
    }

    /**
     * @var string My content.
     */
    private $myContent;

    /**
     * @var RequestInterface My request.
     */
    private $myRequest;

    /**
     * @var StatusCodeInterface My status code.
     */
    private $myStatusCode;
}
