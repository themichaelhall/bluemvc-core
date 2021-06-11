<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Helpers\TestRequests;

use BlueMvc\Core\Base\AbstractRequest;
use BlueMvc\Core\Collections\HeaderCollection;
use BlueMvc\Core\Collections\ParameterCollection;
use BlueMvc\Core\Collections\RequestCookieCollection;
use BlueMvc\Core\Collections\UploadedFileCollection;
use BlueMvc\Core\Interfaces\Collections\HeaderCollectionInterface;
use BlueMvc\Core\Interfaces\Collections\ParameterCollectionInterface;
use BlueMvc\Core\Interfaces\Collections\RequestCookieCollectionInterface;
use BlueMvc\Core\Interfaces\Collections\SessionItemCollectionInterface;
use BlueMvc\Core\Interfaces\Collections\UploadedFileCollectionInterface;
use BlueMvc\Core\Interfaces\Http\MethodInterface;
use BlueMvc\Core\Interfaces\RequestCookieInterface;
use BlueMvc\Core\Interfaces\UploadedFileInterface;
use BlueMvc\Core\Tests\Helpers\TestCollections\BasicTestSessionItemCollection;
use DataTypes\Net\IPAddressInterface;
use DataTypes\Net\UrlInterface;

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
            new RequestCookieCollection(),
            new BasicTestSessionItemCollection()
        );
    }

    /**
     * Adds a header.
     *
     * @param string $name  The name.
     * @param string $value The value.
     */
    public function addHeader(string $name, string $value): void
    {
        parent::addHeader($name, $value);
    }

    /**
     * Sets the client IP address.
     *
     * @param IPAddressInterface $clientIp The client IP address.
     */
    public function setClientIp(IPAddressInterface $clientIp): void
    {
        parent::setClientIp($clientIp);
    }

    /**
     * Sets a cookie.
     *
     * @param string                 $name   The cookie name.
     * @param RequestCookieInterface $cookie The cookie.
     */
    public function setCookie(string $name, RequestCookieInterface $cookie): void
    {
        parent::setCookie($name, $cookie);
    }

    /**
     * Sets the cookies.
     *
     * @param RequestCookieCollectionInterface $cookies The cookies.
     */
    public function setCookies(RequestCookieCollectionInterface $cookies): void
    {
        parent::setCookies($cookies);
    }

    /**
     * Sets a form parameter.
     *
     * @param string $name  The form parameter name.
     * @param string $value The form parameter value.
     */
    public function setFormParameter(string $name, string $value): void
    {
        parent::setFormParameter($name, $value);
    }

    /**
     * Sets the form parameters.
     *
     * @param ParameterCollectionInterface $parameters The form parameters.
     */
    public function setFormParameters(ParameterCollectionInterface $parameters): void
    {
        parent::setFormParameters($parameters);
    }

    /**
     * Sets a header.
     *
     * @param string $name  The name.
     * @param string $value The value.
     */
    public function setHeader(string $name, string $value): void
    {
        parent::setHeader($name, $value);
    }

    /**
     * Sets the headers.
     *
     * @param HeaderCollectionInterface $headers The headers.
     */
    public function setHeaders(HeaderCollectionInterface $headers): void
    {
        parent::setHeaders($headers);
    }

    /**
     * Sets the http method.
     *
     * @param MethodInterface $method The http method.
     */
    public function setMethod(MethodInterface $method): void
    {
        parent::setMethod($method);
    }

    /**
     * Sets a query parameter.
     *
     * @param string $name  The query parameter name.
     * @param string $value The query parameter value.
     */
    public function setQueryParameter(string $name, string $value): void
    {
        parent::setQueryParameter($name, $value);
    }

    /**
     * Sets the query parameters.
     *
     * @param ParameterCollectionInterface $parameters The query parameters.
     */
    public function setQueryParameters(ParameterCollectionInterface $parameters): void
    {
        parent::setQueryParameters($parameters);
    }

    /**
     * Sets the raw content.
     *
     * @param string $content The raw content.
     */
    public function setRawContent(string $content): void
    {
        parent::setRawContent($content);
    }

    /**
     * Sets the session items.
     *
     * @param SessionItemCollectionInterface $sessionItems The session items.
     */
    public function setSessionItems(SessionItemCollectionInterface $sessionItems): void
    {
        parent::setSessionItems($sessionItems);
    }

    /**
     * Sets the uploaded file.
     *
     * @param string                $name         The uploaded file name.
     * @param UploadedFileInterface $uploadedFile The uploaded file.
     */
    public function setUploadedFile(string $name, UploadedFileInterface $uploadedFile): void
    {
        parent::setUploadedFile($name, $uploadedFile);
    }

    /**
     * Sets the uploaded files.
     *
     * @param UploadedFileCollectionInterface $uploadedFiles The uploaded files.
     */
    public function setUploadedFiles(UploadedFileCollectionInterface $uploadedFiles): void
    {
        parent::setUploadedFiles($uploadedFiles);
    }

    /**
     * Sets the url.
     *
     * @param UrlInterface $url The url.
     */
    public function setUrl(UrlInterface $url): void
    {
        parent::setUrl($url);
    }
}
