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
     * Sets a form parameter.
     *
     * @param string $name  The form parameter name.
     * @param string $value The form parameter value.
     */
    public function setFormParameter($name, $value)
    {
        parent::setFormParameter($name, $value);
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
     * Sets a query parameter.
     *
     * @param string $name  The query parameter name.
     * @param string $value The query parameter value.
     */
    public function setQueryParameter($name, $value)
    {
        parent::setQueryParameter($name, $value);
    }

    /**
     * Sets the query parameters.
     *
     * @param ParameterCollectionInterface $parameters The query parameters.
     */
    public function setQueryParameters(ParameterCollectionInterface $parameters)
    {
        parent::setQueryParameters($parameters);
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
