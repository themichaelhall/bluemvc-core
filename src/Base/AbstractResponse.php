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
        $this->myContent = '';
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
     * Sets the content.
     *
     * @param string $content The content.
     */
    public function setContent($content)
    {
        $this->myContent = $content;
    }

    /**
     * @var string My content.
     */
    private $myContent;

    /**
     * @var RequestInterface My request.
     */
    private $myRequest;
}
