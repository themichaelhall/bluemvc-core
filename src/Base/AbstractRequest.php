<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\Base;

use BlueMvc\Core\Collections\RequestCookieCollection;
use BlueMvc\Core\Interfaces\Collections\HeaderCollectionInterface;
use BlueMvc\Core\Interfaces\Collections\ParameterCollectionInterface;
use BlueMvc\Core\Interfaces\Collections\RequestCookieCollectionInterface;
use BlueMvc\Core\Interfaces\Collections\UploadedFileCollectionInterface;
use BlueMvc\Core\Interfaces\Http\MethodInterface;
use BlueMvc\Core\Interfaces\RequestCookieInterface;
use BlueMvc\Core\Interfaces\RequestInterface;
use BlueMvc\Core\Interfaces\UploadedFileInterface;
use DataTypes\Interfaces\IPAddressInterface;
use DataTypes\Interfaces\UrlInterface;
use DataTypes\IPAddress;

/**
 * Abstract class representing a web request.
 *
 * @since 1.0.0
 */
abstract class AbstractRequest implements RequestInterface
{
    /**
     * Returns the client IP address.
     *
     * @since 1.1.0
     *
     * @return IPAddressInterface The client IP address.
     */
    public function getClientIp()
    {
        return $this->myClientIp;
    }

    /**
     * Returns a cookie by cookie name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The cookie name.
     *
     * @throws \InvalidArgumentException If the $name parameter is not a string.
     *
     * @return RequestCookieInterface|null The cookie by cookie name if it exists, null otherwise.
     */
    public function getCookie($name)
    {
        return $this->myCookies->get($name);
    }

    /**
     * Returns the cookies.
     *
     * @since 1.0.0
     *
     * @return RequestCookieCollection The cookies.
     */
    public function getCookies()
    {
        return $this->myCookies;
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
     * Returns the raw content from request.
     *
     * @since 1.0.0
     *
     * @return string The raw content from request.
     */
    public function getRawContent()
    {
        return $this->myRawContent;
    }

    /**
     * Returns a uploaded file by name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The uploaded file name.
     *
     * @throws \InvalidArgumentException If the $name parameter is not a string.
     *
     * @return UploadedFileInterface|null The uploaded file by name if it exists, null otherwise.
     */
    public function getUploadedFile($name)
    {
        return $this->myUploadedFiles->get($name);
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
     * Constructs the request.
     *
     * @since 1.0.0
     *
     * @param UrlInterface                     $url             The url.
     * @param MethodInterface                  $method          The method.
     * @param HeaderCollectionInterface        $headers         The headers.
     * @param ParameterCollectionInterface     $queryParameters The query parameters.
     * @param ParameterCollectionInterface     $formParameters  The form parameters.
     * @param UploadedFileCollectionInterface  $uploadedFiles   The uploaded files.
     * @param RequestCookieCollectionInterface $cookies         The cookies.
     */
    protected function __construct(UrlInterface $url, MethodInterface $method, HeaderCollectionInterface $headers, ParameterCollectionInterface $queryParameters, ParameterCollectionInterface $formParameters, UploadedFileCollectionInterface $uploadedFiles, RequestCookieCollectionInterface $cookies)
    {
        $this->setUrl($url);
        $this->setMethod($method);
        $this->setHeaders($headers);
        $this->setQueryParameters($queryParameters);
        $this->setFormParameters($formParameters);
        $this->setUploadedFiles($uploadedFiles);
        $this->setCookies($cookies);
        $this->setRawContent('');
        $this->setClientIp(IPAddress::fromParts([0, 0, 0, 0]));
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
     * Sets the client IP address.
     *
     * @since 1.1.0
     *
     * @param IPAddressInterface $clientIp The client IP address.
     */
    protected function setClientIp(IPAddressInterface $clientIp)
    {
        $this->myClientIp = $clientIp;
    }

    /**
     * Sets a cookie.
     *
     * @since 1.0.0
     *
     * @param string                 $name   The cookie name.
     * @param RequestCookieInterface $cookie The cookie.
     *
     * @throws \InvalidArgumentException If the $name parameter is not a string.
     */
    protected function setCookie($name, RequestCookieInterface $cookie)
    {
        $this->myCookies->set($name, $cookie);
    }

    /**
     * Sets the cookies.
     *
     * @since 1.0.0
     *
     * @param RequestCookieCollectionInterface $cookies The cookies.
     */
    protected function setCookies(RequestCookieCollectionInterface $cookies)
    {
        $this->myCookies = $cookies;
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
     * Sets the raw content.
     *
     * @since 1.0.0
     *
     * @param string $content The raw content.
     *
     * @throws \InvalidArgumentException If the $content parameter is not a string.
     */
    protected function setRawContent($content)
    {
        if (!is_string($content)) {
            throw new \InvalidArgumentException('$content parameter is not a string.');
        }

        $this->myRawContent = $content;
    }

    /**
     * Sets an uploaded file.
     *
     * @since 1.0.0
     *
     * @param string                $name         The uploaded file name.
     * @param UploadedFileInterface $uploadedFile The uploaded file.
     *
     * @throws \InvalidArgumentException If the $name parameter is not a string.
     */
    protected function setUploadedFile($name, UploadedFileInterface $uploadedFile)
    {
        $this->myUploadedFiles->set($name, $uploadedFile);
    }

    /**
     * Sets the uploaded files.
     *
     * @since 1.0.0
     *
     * @param UploadedFileCollectionInterface $uploadedFiles The uploaded files.
     */
    protected function setUploadedFiles(UploadedFileCollectionInterface $uploadedFiles)
    {
        $this->myUploadedFiles = $uploadedFiles;
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
     * @var RequestCookieCollection My cookies.
     */
    private $myCookies;

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
     * @var string My raw content.
     */
    private $myRawContent;

    /**
     * @var UploadedFileCollectionInterface My uploaded files.
     */
    private $myUploadedFiles;

    /**
     * @var UrlInterface My url.
     */
    private $myUrl;

    /**
     * @var IPAddressInterface My client ip address.
     */
    private $myClientIp;
}
