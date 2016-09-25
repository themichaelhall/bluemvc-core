<?php

use BlueMvc\Core\Base\AbstractRequest;
use BlueMvc\Core\Interfaces\Http\MethodInterface;
use DataTypes\Interfaces\UrlInterface;

/**
 * Basic test request class.
 */
class BasicTestRequest extends AbstractRequest
{
    /**
     * Constructs the request.
     *
     * @param UrlInterface    $url
     * @param MethodInterface $method
     */
    public function __construct(UrlInterface $url, MethodInterface $method)
    {
        parent::__construct($url, $method);
    }

    /**
     * Sets the http method.
     *
     * @param MethodInterface $method The http method.
     */
    public function setMethod(MethodInterface $method)
    {
        parent::setMethod($method);
    }

    /**
     * Sets the url.
     *
     * @param UrlInterface $url The url.
     */
    public function setUrl(UrlInterface $url)
    {
        parent::setUrl($url);
    }
}
