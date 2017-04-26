<?php

use BlueMvc\Core\Base\AbstractRequest;
use BlueMvc\Core\Interfaces\Collections\HeaderCollectionInterface;
use BlueMvc\Core\Interfaces\Collections\ParameterCollectionInterface;
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
     * Adds a header.
     *
     * @param string $name  The name.
     * @param string $value The value.
     */
    public function addHeader($name, $value)
    {
        parent::addHeader($name, $value);
    }

    /**
     * Sets the form parameters.
     *
     * @param ParameterCollectionInterface $parameters The form parameters.
     */
    public function setFormParameters(ParameterCollectionInterface $parameters)
    {
        parent::setFormParameters($parameters);
    }

    /**
     * Sets a header.
     *
     * @param string $name  The name.
     * @param string $value The value.
     */
    public function setHeader($name, $value)
    {
        parent::setHeader($name, $value);
    }

    /**
     * Sets the headers.
     *
     * @param HeaderCollectionInterface $headers The headers.
     */
    public function setHeaders(HeaderCollectionInterface $headers)
    {
        parent::setHeaders($headers);
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
