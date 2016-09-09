<?php

namespace BlueMvc\Core\Base;

use BlueMvc\Core\Interfaces\Http\MethodInterface;
use BlueMvc\Core\Interfaces\RequestInterface;
use DataTypes\Interfaces\UrlInterface;

/**
 * Abstract class representing a web request.
 */
abstract class AbstractRequest implements RequestInterface
{
    /**
     * Constructs the request.
     */
    public function __construct()
    {
        $this->myMethod = null;
        $this->myUrl = null;
    }

    /**
     * @return MethodInterface|null The http method.
     */
    public function getMethod()
    {
        return $this->myMethod;
    }

    /**
     * @return UrlInterface|null The url.
     */
    public function getUrl()
    {
        return $this->myUrl;
    }

    /**
     * Sets the method.
     *
     * @param MethodInterface $method The method.
     */
    protected function setMethod(MethodInterface $method)
    {
        $this->myMethod = $method;
    }

    /**
     * Sets the url.
     *
     * @param UrlInterface $url The url.
     */
    protected function setUrl(UrlInterface $url)
    {
        $this->myUrl = $url;
    }

    /**
     * @var MethodInterface|null My method.
     */
    private $myMethod;

    /**
     * @var UrlInterface|null My url.
     */
    private $myUrl;
}
