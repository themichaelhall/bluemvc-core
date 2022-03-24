<?php

/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

declare(strict_types=1);

namespace BlueMvc\Core\Interfaces;

use BlueMvc\Core\Collections\RequestCookieCollection;
use BlueMvc\Core\Interfaces\Collections\HeaderCollectionInterface;
use BlueMvc\Core\Interfaces\Collections\ParameterCollectionInterface;
use BlueMvc\Core\Interfaces\Collections\SessionItemCollectionInterface;
use BlueMvc\Core\Interfaces\Collections\UploadedFileCollectionInterface;
use BlueMvc\Core\Interfaces\Http\MethodInterface;
use DataTypes\Net\IPAddressInterface;
use DataTypes\Net\UrlInterface;

/**
 * Interface for Request class.
 *
 * @since 1.0.0
 */
interface RequestInterface
{
    /**
     * Returns the client IP address.
     *
     * @since 1.1.0
     *
     * @return IPAddressInterface The client IP address.
     */
    public function getClientIp(): IPAddressInterface;

    /**
     * Returns a cookie by cookie name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The cookie name.
     *
     * @return RequestCookieInterface|null The cookie by cookie name if it exists, null otherwise.
     */
    public function getCookie(string $name): ?RequestCookieInterface;

    /**
     * Returns the cookies.
     *
     * @since 1.0.0
     *
     * @return RequestCookieCollection The cookies.
     */
    public function getCookies(): RequestCookieCollection;

    /**
     * Returns the cookie value by cookie name if it exists, null otherwise.
     *
     * @since 1.1.0
     *
     * @param string $name The cookie name.
     *
     * @return string|null The cookie value by cookie name if it exists, false otherwise.
     */
    public function getCookieValue(string $name): ?string;

    /**
     * Returns a form parameter value by form parameter name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The form parameter name.
     *
     * @return string|null The form parameter value by form parameter name if it exists, null otherwise.
     */
    public function getFormParameter(string $name): ?string;

    /**
     * Returns the form parameters.
     *
     * @since 1.0.0
     *
     * @return ParameterCollectionInterface The form parameters.
     */
    public function getFormParameters(): ParameterCollectionInterface;

    /**
     * Returns a header value by header name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The header name.
     *
     * @return string|null The header value by header name if it exists, null otherwise.
     */
    public function getHeader(string $name): ?string;

    /**
     * Returns the headers.
     *
     * @since 1.0.0
     *
     * @return HeaderCollectionInterface The headers.
     */
    public function getHeaders(): HeaderCollectionInterface;

    /**
     * Returns the http method.
     *
     * @since 1.0.0
     *
     * @return MethodInterface The http method.
     */
    public function getMethod(): MethodInterface;

    /**
     * Returns a query parameter value by query parameter name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The query parameter name.
     *
     * @return string|null The query parameter value by query parameter name if it exists, null otherwise.
     */
    public function getQueryParameter(string $name): ?string;

    /**
     * Returns the query parameters.
     *
     * @since 1.0.0
     *
     * @return ParameterCollectionInterface The query parameters.
     */
    public function getQueryParameters(): ParameterCollectionInterface;

    /**
     * Returns the raw content from request.
     *
     * @since 1.0.0
     *
     * @return string The raw content from request.
     */
    public function getRawContent(): string;

    /**
     * Returns the referrer or null if request has no referrer or invalid referrer.
     *
     * @since 1.1.0
     *
     * @return UrlInterface|null The referrer or null if request has no referrer or invalid referrer.
     */
    public function getReferrer(): ?UrlInterface;

    /**
     * Returns a session item by name if it exists, null otherwise.
     *
     * @since 2.0.0
     *
     * @param string $name The session item name.
     *
     * @return mixed|null The session item if it exists, null otherwise.
     */
    public function getSessionItem(string $name);

    /**
     * Returns the session items.
     *
     * @since 2.0.0
     *
     * @return SessionItemCollectionInterface The session items.
     */
    public function getSessionItems(): SessionItemCollectionInterface;

    /**
     * Returns an uploaded file by name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The uploaded file name.
     *
     * @return UploadedFileInterface|null The uploaded file by name if it exists, null otherwise.
     */
    public function getUploadedFile(string $name): ?UploadedFileInterface;

    /**
     * Returns the uploaded files.
     *
     * @since 1.0.0
     *
     * @return UploadedFileCollectionInterface The uploaded files.
     */
    public function getUploadedFiles(): UploadedFileCollectionInterface;

    /**
     * Returns the user agent or empty string if no user agent exists.
     *
     * @since 1.0.0
     *
     * @return string The user agent or empty string if no user agent exists.
     */
    public function getUserAgent(): string;

    /**
     * Returns the url.
     *
     * @since 1.0.0
     *
     * @return UrlInterface The url.
     */
    public function getUrl(): UrlInterface;

    /**
     * Removes a session item by name.
     *
     * @since 2.0.0
     *
     * @param string $name The session item name.
     */
    public function removeSessionItem(string $name): void;

    /**
     * Sets a session item.
     *
     * @since 2.0.0
     *
     * @param string $name  The session item name.
     * @param mixed  $value The session item value.
     */
    public function setSessionItem(string $name, $value): void;
}
