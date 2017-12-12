<?php

namespace BlueMvc\Core\Tests\Helpers\TestRequests;

use BlueMvc\Core\Base\AbstractRequest;
use BlueMvc\Core\Collections\HeaderCollection;
use BlueMvc\Core\Collections\ParameterCollection;
use BlueMvc\Core\Collections\RequestCookieCollection;
use BlueMvc\Core\Collections\UploadedFileCollection;
use BlueMvc\Core\Interfaces\Collections\HeaderCollectionInterface;
use BlueMvc\Core\Interfaces\Collections\ParameterCollectionInterface;
use BlueMvc\Core\Interfaces\Collections\RequestCookieCollectionInterface;
use BlueMvc\Core\Interfaces\Collections\UploadedFileCollectionInterface;
use BlueMvc\Core\Interfaces\Http\MethodInterface;
use BlueMvc\Core\Interfaces\RequestCookieInterface;
use BlueMvc\Core\Interfaces\UploadedFileInterface;
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
        parent::__construct(
            $url,
            $method,
            new HeaderCollection(),
            new ParameterCollection(),
            new ParameterCollection(),
            new UploadedFileCollection(),
            new RequestCookieCollection()
        );
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
     * Sets a cookie.
     *
     * @param string                 $name   The cookie name.
     * @param RequestCookieInterface $cookie The cookie.
     */
    public function setCookie($name, RequestCookieInterface $cookie)
    {
        parent::setCookie($name, $cookie);
    }

    /**
     * Sets the cookies.
     *
     * @param RequestCookieCollectionInterface $cookies The cookies.
     */
    public function setCookies(RequestCookieCollectionInterface $cookies)
    {
        parent::setCookies($cookies);
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
     * Sets the raw content.
     *
     * @param string $content The raw content.
     *
     * @throws \InvalidArgumentException If the $content parameter is not a string.
     */
    public function setRawContent($content)
    {
        parent::setRawContent($content);
    }

    /**
     * Sets the uploaded file.
     *
     * @param string                $name         The uploaded file name.
     * @param UploadedFileInterface $uploadedFile The uploaded file.
     */
    public function setUploadedFile($name, UploadedFileInterface $uploadedFile)
    {
        parent::setUploadedFile($name, $uploadedFile);
    }

    /**
     * Sets the uploaded files.
     *
     * @param UploadedFileCollectionInterface $uploadedFiles The uploaded files.
     */
    public function setUploadedFiles(UploadedFileCollectionInterface $uploadedFiles)
    {
        parent::setUploadedFiles($uploadedFiles);
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
