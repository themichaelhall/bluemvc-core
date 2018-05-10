<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */
declare(strict_types=1);

namespace BlueMvc\Core\Base;

use BlueMvc\Core\Collections\RequestCookieCollection;
use BlueMvc\Core\Interfaces\Collections\HeaderCollectionInterface;
use BlueMvc\Core\Interfaces\Collections\ParameterCollectionInterface;
use BlueMvc\Core\Interfaces\Collections\RequestCookieCollectionInterface;
use BlueMvc\Core\Interfaces\Collections\SessionItemCollectionInterface;
use BlueMvc\Core\Interfaces\Collections\UploadedFileCollectionInterface;
use BlueMvc\Core\Interfaces\Http\MethodInterface;
use BlueMvc\Core\Interfaces\RequestCookieInterface;
use BlueMvc\Core\Interfaces\RequestInterface;
use BlueMvc\Core\Interfaces\UploadedFileInterface;
use DataTypes\Interfaces\IPAddressInterface;
use DataTypes\Interfaces\UrlInterface;
use DataTypes\IPAddress;
use DataTypes\Url;

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
    public function getClientIp(): IPAddressInterface
    {
        return $this->clientIp;
    }

    /**
     * Returns a cookie by cookie name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The cookie name.
     *
     * @return RequestCookieInterface|null The cookie by cookie name if it exists, null otherwise.
     */
    public function getCookie(string $name): ?RequestCookieInterface
    {
        return $this->cookies->get($name);
    }

    /**
     * Returns the cookies.
     *
     * @since 1.0.0
     *
     * @return RequestCookieCollection The cookies.
     */
    public function getCookies(): RequestCookieCollection
    {
        return $this->cookies;
    }

    /**
     * Returns the cookie value by cookie name if it exists, null otherwise.
     *
     * @since 1.1.0
     *
     * @param string $name The cookie name.
     *
     * @return string|null The cookie value by cookie name if it exists, false otherwise.
     */
    public function getCookieValue(string $name): ?string
    {
        $cookie = $this->cookies->get($name);

        return $cookie !== null ? $cookie->getValue() : null;
    }

    /**
     * Returns a form parameter value by form parameter name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The form parameter name.
     *
     * @return string|null The form parameter value by form parameter name if it exists, null otherwise.
     */
    public function getFormParameter(string $name): ?string
    {
        return $this->formParameters->get($name);
    }

    /**
     * Returns the form parameters.
     *
     * @since 1.0.0
     *
     * @return ParameterCollectionInterface The form parameters.
     */
    public function getFormParameters(): ParameterCollectionInterface
    {
        return $this->formParameters;
    }

    /**
     * Returns a header value by header name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The header name.
     *
     * @return string|null The header value by header name if it exists, null otherwise.
     */
    public function getHeader(string $name): ?string
    {
        return $this->headers->get($name);
    }

    /**
     * Returns the headers.
     *
     * @since 1.0.0
     *
     * @return HeaderCollectionInterface The headers.
     */
    public function getHeaders(): HeaderCollectionInterface
    {
        return $this->headers;
    }

    /**
     * Returns the http method.
     *
     * @since 1.0.0
     *
     * @return MethodInterface The http method.
     */
    public function getMethod(): MethodInterface
    {
        return $this->method;
    }

    /**
     * Returns a query parameter value by query parameter name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The query parameter name.
     *
     * @return string|null The query parameter value by query parameter name if it exists, null otherwise.
     */
    public function getQueryParameter(string $name): ?string
    {
        return $this->queryParameters->get($name);
    }

    /**
     * Returns the query parameters.
     *
     * @since 1.0.0
     *
     * @return ParameterCollectionInterface The query parameters.
     */
    public function getQueryParameters(): ParameterCollectionInterface
    {
        return $this->queryParameters;
    }

    /**
     * Returns the raw content from request.
     *
     * @since 1.0.0
     *
     * @return string The raw content from request.
     */
    public function getRawContent(): string
    {
        return $this->rawContent;
    }

    /**
     * Returns the referrer or null if request has no or invalid referrer.
     *
     * @since 1.1.0
     *
     * @return UrlInterface|null The referrer or null if request has no or invalid referrer.
     */
    public function getReferrer(): ?UrlInterface
    {
        $referrerHeader = $this->getHeader('Referer');
        if ($referrerHeader === null) {
            return null;
        }

        return Url::tryParse($referrerHeader);
    }

    /**
     * Returns a session item by name if it exists, null otherwise.
     *
     * @since 2.0.0
     *
     * @param string $name The name.
     *
     * @return mixed|null The session item if it exists, null otherwise.
     */
    public function getSessionItem(string $name)
    {
        return $this->sessionItems->get($name);
    }

    /**
     * Returns the session items.
     *
     * @since 2.0.0
     *
     * @return SessionItemCollectionInterface The session items.
     */
    public function getSessionItems(): SessionItemCollectionInterface
    {
        return $this->sessionItems;
    }

    /**
     * Returns a uploaded file by name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The uploaded file name.
     *
     * @return UploadedFileInterface|null The uploaded file by name if it exists, null otherwise.
     */
    public function getUploadedFile(string $name): ?UploadedFileInterface
    {
        return $this->uploadedFiles->get($name);
    }

    /**
     * Returns the uploaded files.
     *
     * @since 1.0.0
     *
     * @return UploadedFileCollectionInterface The uploaded files.
     */
    public function getUploadedFiles(): UploadedFileCollectionInterface
    {
        return $this->uploadedFiles;
    }

    /**
     * Returns the user agent or empty string if no user agent exists.
     *
     * @since 1.0.0
     *
     * @return string The user agent or empty string if no user agent exists.
     */
    public function getUserAgent(): string
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
    public function getUrl(): UrlInterface
    {
        return $this->url;
    }

    /**
     * Sets a session item.
     *
     * @since 2.0.0
     *
     * @param string $name  The session item name.
     * @param mixed  $value The session item value.
     */
    public function setSessionItem(string $name, $value): void
    {
        $this->sessionItems->set($name, $value);
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
     * @param SessionItemCollectionInterface   $sessionItems    The session items.
     */
    protected function __construct(
        UrlInterface $url,
        MethodInterface $method,
        HeaderCollectionInterface $headers,
        ParameterCollectionInterface $queryParameters,
        ParameterCollectionInterface $formParameters,
        UploadedFileCollectionInterface $uploadedFiles,
        RequestCookieCollectionInterface $cookies,
        SessionItemCollectionInterface $sessionItems
    ) {
        $this->setUrl($url);
        $this->setMethod($method);
        $this->setHeaders($headers);
        $this->setQueryParameters($queryParameters);
        $this->setFormParameters($formParameters);
        $this->setUploadedFiles($uploadedFiles);
        $this->setCookies($cookies);
        $this->setSessionItems($sessionItems);
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
     */
    protected function addHeader(string $name, string $value): void
    {
        $this->headers->add($name, $value);
    }

    /**
     * Sets the client IP address.
     *
     * @since 1.1.0
     *
     * @param IPAddressInterface $clientIp The client IP address.
     */
    protected function setClientIp(IPAddressInterface $clientIp): void
    {
        $this->clientIp = $clientIp;
    }

    /**
     * Sets a cookie.
     *
     * @since 1.0.0
     *
     * @param string                 $name   The cookie name.
     * @param RequestCookieInterface $cookie The cookie.
     */
    protected function setCookie(string $name, RequestCookieInterface $cookie): void
    {
        $this->cookies->set($name, $cookie);
    }

    /**
     * Sets the cookies.
     *
     * @since 1.0.0
     *
     * @param RequestCookieCollectionInterface $cookies The cookies.
     */
    protected function setCookies(RequestCookieCollectionInterface $cookies): void
    {
        $this->cookies = $cookies;
    }

    /**
     * Sets a form parameter.
     *
     * @since 1.0.0
     *
     * @param string $name  The form parameter name.
     * @param string $value The form parameter value.
     */
    protected function setFormParameter(string $name, string $value): void
    {
        $this->formParameters->set($name, $value);
    }

    /**
     * Sets the form parameters.
     *
     * @since 1.0.0
     *
     * @param ParameterCollectionInterface $parameters The form parameters.
     */
    protected function setFormParameters(ParameterCollectionInterface $parameters): void
    {
        $this->formParameters = $parameters;
    }

    /**
     * Sets a header.
     *
     * @since 1.0.0
     *
     * @param string $name  The header name.
     * @param string $value The header value.
     */
    protected function setHeader(string $name, string $value): void
    {
        $this->headers->set($name, $value);
    }

    /**
     * Sets the headers.
     *
     * @since 1.0.0
     *
     * @param HeaderCollectionInterface $headers The headers.
     */
    protected function setHeaders(HeaderCollectionInterface $headers): void
    {
        $this->headers = $headers;
    }

    /**
     * Sets the http method.
     *
     * @since 1.0.0
     *
     * @param MethodInterface $method The http method.
     */
    protected function setMethod(MethodInterface $method): void
    {
        $this->method = $method;
    }

    /**
     * Sets a query parameter.
     *
     * @since 1.0.0
     *
     * @param string $name  The query parameter name.
     * @param string $value The query parameter value.
     */
    protected function setQueryParameter(string $name, string $value): void
    {
        $this->queryParameters->set($name, $value);
    }

    /**
     * Sets the query parameters.
     *
     * @since 1.0.0
     *
     * @param ParameterCollectionInterface $parameters The query parameters.
     */
    protected function setQueryParameters(ParameterCollectionInterface $parameters): void
    {
        $this->queryParameters = $parameters;
    }

    /**
     * Sets the raw content.
     *
     * @since 1.0.0
     *
     * @param string $content The raw content.
     */
    protected function setRawContent(string $content): void
    {
        $this->rawContent = $content;
    }

    /**
     * Sets the session items.
     *
     * @since 2.0.0
     *
     * @param SessionItemCollectionInterface $sessionItems The session items.
     */
    protected function setSessionItems(SessionItemCollectionInterface $sessionItems): void
    {
        $this->sessionItems = $sessionItems;
    }

    /**
     * Sets an uploaded file.
     *
     * @since 1.0.0
     *
     * @param string                $name         The uploaded file name.
     * @param UploadedFileInterface $uploadedFile The uploaded file.
     */
    protected function setUploadedFile(string $name, UploadedFileInterface $uploadedFile): void
    {
        $this->uploadedFiles->set($name, $uploadedFile);
    }

    /**
     * Sets the uploaded files.
     *
     * @since 1.0.0
     *
     * @param UploadedFileCollectionInterface $uploadedFiles The uploaded files.
     */
    protected function setUploadedFiles(UploadedFileCollectionInterface $uploadedFiles): void
    {
        $this->uploadedFiles = $uploadedFiles;
    }

    /**
     * Sets the url.
     *
     * @since 1.0.0
     *
     * @param UrlInterface $url The url.
     */
    protected function setUrl(UrlInterface $url): void
    {
        $this->url = $url;
    }

    /**
     * @var RequestCookieCollection My cookies.
     */
    private $cookies;

    /**
     * @var ParameterCollectionInterface My form parameters.
     */
    private $formParameters;

    /**
     * @var HeaderCollectionInterface My headers.
     */
    private $headers;

    /**
     * @var MethodInterface My method.
     */
    private $method;

    /**
     * @var ParameterCollectionInterface My query parameters.
     */
    private $queryParameters;

    /**
     * @var string My raw content.
     */
    private $rawContent;

    /**
     * @var UploadedFileCollectionInterface My uploaded files.
     */
    private $uploadedFiles;

    /**
     * @var UrlInterface My url.
     */
    private $url;

    /**
     * @var IPAddressInterface My client ip address.
     */
    private $clientIp;

    /**
     * @var SessionItemCollectionInterface My session items.
     */
    private $sessionItems;
}
