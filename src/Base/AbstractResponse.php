<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\Base;

use BlueMvc\Core\Collections\HeaderCollection;
use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Interfaces\Collections\HeaderCollectionInterface;
use BlueMvc\Core\Interfaces\Http\StatusCodeInterface;
use BlueMvc\Core\Interfaces\RequestInterface;
use BlueMvc\Core\Interfaces\ResponseInterface;

/**
 * Abstract class representing a web response.
 *
 * @since 1.0.0
 */
abstract class AbstractResponse implements ResponseInterface
{
    /**
     * Constructs a response.
     *
     * @since 1.0.0
     *
     * @param RequestInterface $request The request.
     */
    public function __construct(RequestInterface $request)
    {
        $this->myContent = '';
        $this->myHeaders = new HeaderCollection();
        $this->myRequest = $request;
        $this->myStatusCode = new StatusCode(StatusCode::OK);
    }

    /**
     * Returns the content.
     *
     * @since 1.0.0
     *
     * @return string The content.
     */
    public function getContent()
    {
        return $this->myContent;
    }

    /**
     * Returns the headers.
     *
     * @since 1.0.0
     *
     * @return HeaderCollectionInterface The headers.
     */
    public function getHeaders()
    {
        return $this->myHeaders;
    }

    /**
     * Returns the request.
     *
     * @since 1.0.0
     *
     * @return RequestInterface The request.
     */
    public function getRequest()
    {
        return $this->myRequest;
    }

    /**
     * Returns the status code.
     *
     * @since 1.0.0
     *
     * @return StatusCodeInterface The status code.
     */
    public function getStatusCode()
    {
        return $this->myStatusCode;
    }

    /**
     * Sets the content.
     *
     * @since 1.0.0
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
     * @since 1.0.0
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
     * @var HeaderCollection My headers.
     */
    private $myHeaders;

    /**
     * @var RequestInterface My request.
     */
    private $myRequest;

    /**
     * @var StatusCodeInterface My status code.
     */
    private $myStatusCode;
}
