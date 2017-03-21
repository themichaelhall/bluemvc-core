<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\Base;

use BlueMvc\Core\Collections\HeaderCollection;
use BlueMvc\Core\Interfaces\Collections\HeaderCollectionInterface;
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
        $this->setHeaders(new HeaderCollection());
    }

    /**
     * Returns a header value by header name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The header name.
     *
     * @throws \InvalidArgumentException If the $name parameter is not a string.
     *
     * @return string|null The header value by header name if it exists, null otherwise.
     */
    public function getHeader($name)
    {
        return $this->myHeaders->get($name);
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
     * Returns the http method.
     *
     * @since 1.0.0
     *
     * @return MethodInterface The http method.
     */
    public function getMethod()
    {
        return $this->myMethod;
    }

    /**
     * Returns the user agent or empty string if no user agent exists.
     *
     * @since 1.0.0
     *
     * @return string The user agent or empty string if no user agent exists.
     */
    public function getUserAgent()
    {
        return $this->getHeader('User-Agent') ?: '';
    }

    /**
     * Returns the url.
     *
     * @since 1.0.0
     *
     * @return UrlInterface The url.
     */
    public function getUrl()
    {
        return $this->myUrl;
    }

    /**
     * Adds a header.
     *
     * @since 1.0.0
     *
     * @param string $name  The header name.
     * @param string $value The header value.
     *
     * @throws \InvalidArgumentException If any of the parameters are of invalid type.
     */
    protected function addHeader($name, $value)
    {
        $this->myHeaders->add($name, $value);
    }

    /**
     * Sets a header.
     *
     * @since 1.0.0
     *
     * @param string $name  The header name.
     * @param string $value The header value.
     *
     * @throws \InvalidArgumentException If any of the parameters are of invalid type.
     */
    protected function setHeader($name, $value)
    {
        $this->myHeaders->set($name, $value);
    }

    /**
     * Sets the headers.
     *
     * @since 1.0.0
     *
     * @param HeaderCollectionInterface $headers The headers.
     */
    protected function setHeaders(HeaderCollectionInterface $headers)
    {
        $this->myHeaders = $headers;
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
     * @var HeaderCollectionInterface My headers.
     */
    private $myHeaders;

    /**
     * @var MethodInterface My method.
     */
    private $myMethod;

    /**
     * @var UrlInterface My url.
     */
    private $myUrl;
}
