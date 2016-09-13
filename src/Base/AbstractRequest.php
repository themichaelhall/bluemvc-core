<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */
namespace BlueMvc\Core\Base;

use BlueMvc\Core\Interfaces\Http\MethodInterface;
use BlueMvc\Core\Interfaces\RequestInterface;
use DataTypes\Interfaces\UrlInterface;

/**
 * Abstract class representing a web request.
 *
 * @since 1.0.0
 */
abstract class AbstractRequest implements RequestInterface
{
    /**
     * Constructs the request.
     *
     * @since 1.0.0
     *
     * @param UrlInterface    $url    The url.
     * @param MethodInterface $method The method.
     */
    public function __construct(UrlInterface $url, MethodInterface $method)
    {
        $this->setUrl($url);
        $this->setMethod($method);
    }

    /**
     * Returns the http method or null if no http method is set.
     *
     * @since 1.0.0
     *
     * @return MethodInterface|null The http method or null if no http method is set.
     */
    public function getMethod()
    {
        return $this->myMethod;
    }

    /**
     * Returns the url or null of no url is set.
     *
     * @since 1.0.0
     *
     * @return UrlInterface|null The url or null of no url is set.
     */
    public function getUrl()
    {
        return $this->myUrl;
    }

    /**
     * Sets the http method.
     *
     * @since 1.0.0
     *
     * @param MethodInterface $method The http method.
     */
    protected function setMethod(MethodInterface $method)
    {
        $this->myMethod = $method;
    }

    /**
     * Sets the url.
     *
     * @since 1.0.0
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
