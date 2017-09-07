<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\Base;

use BlueMvc\Core\Collections\HeaderCollection;
use BlueMvc\Core\Collections\ParameterCollection;
use BlueMvc\Core\Collections\UploadedFileCollection;
use BlueMvc\Core\Interfaces\Collections\HeaderCollectionInterface;
use BlueMvc\Core\Interfaces\Collections\ParameterCollectionInterface;
use BlueMvc\Core\Interfaces\Collections\UploadedFileCollectionInterface;
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
        $this->setFormParameters(new ParameterCollection());
        $this->setQueryParameters(new ParameterCollection());
        $this->myUploadedFiles = new UploadedFileCollection(); // fixme: setUploadedFiles()
    }

    /**
     * Returns a form parameter value by form parameter name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The form parameter name.
     *
     * @throws \InvalidArgumentException If the $name parameter is not a string.
     *
     * @return string|null The form parameter value by form parameter name if it exists, null otherwise.
     */
    public function getFormParameter($name)
    {
        return $this->myFormParameters->get($name);
    }

    /**
     * Returns the form parameters.
     *
     * @since 1.0.0
     *
     * @return ParameterCollectionInterface The form parameters.
     */
    public function getFormParameters()
    {
        return $this->myFormParameters;
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
     * Returns a query parameter value by query parameter name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The query parameter name.
     *
     * @throws \InvalidArgumentException If the $name parameter is not a string.
     *
     * @return string|null The query parameter value by query parameter name if it exists, null otherwise.
     */
    public function getQueryParameter($name)
    {
        return $this->myQueryParameters->get($name);
    }

    /**
     * Returns the query parameters.
     *
     * @since 1.0.0
     *
     * @return ParameterCollectionInterface The query parameters.
     */
    public function getQueryParameters()
    {
        return $this->myQueryParameters;
    }

    /**
     * Returns the uploaded files.
     *
     * @since 1.0.0
     *
     * @return UploadedFileCollectionInterface The uploaded files.
     */
    public function getUploadedFiles()
    {
        return $this->myUploadedFiles;
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
     * Sets a form parameter.
     *
     * @since 1.0.0
     *
     * @param string $name  The form parameter name.
     * @param string $value The form parameter value.
     *
     * @throws \InvalidArgumentException If any of the parameters are of invalid type.
     */
    protected function setFormParameter($name, $value)
    {
        $this->myFormParameters->set($name, $value);
    }

    /**
     * Sets the form parameters.
     *
     * @since 1.0.0
     *
     * @param ParameterCollectionInterface $parameters The form parameters.
     */
    protected function setFormParameters(ParameterCollectionInterface $parameters)
    {
        $this->myFormParameters = $parameters;
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
     * Sets a query parameter.
     *
     * @since 1.0.0
     *
     * @param string $name  The query parameter name.
     * @param string $value The query parameter value.
     *
     * @throws \InvalidArgumentException If any of the parameters are of invalid type.
     */
    protected function setQueryParameter($name, $value)
    {
        $this->myQueryParameters->set($name, $value);
    }

    /**
     * Sets the query parameters.
     *
     * @since 1.0.0
     *
     * @param ParameterCollectionInterface $parameters The query parameters.
     */
    protected function setQueryParameters(ParameterCollectionInterface $parameters)
    {
        $this->myQueryParameters = $parameters;
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
     * @var ParameterCollectionInterface My form parameters.
     */
    private $myFormParameters;

    /**
     * @var HeaderCollectionInterface My headers.
     */
    private $myHeaders;

    /**
     * @var MethodInterface My method.
     */
    private $myMethod;

    /**
     * @var ParameterCollectionInterface My query parameters.
     */
    private $myQueryParameters;

    /**
     * @var UploadedFileCollectionInterface My uploaded files.
     */
    private $myUploadedFiles;

    /**
     * @var UrlInterface My url.
     */
    private $myUrl;
}
