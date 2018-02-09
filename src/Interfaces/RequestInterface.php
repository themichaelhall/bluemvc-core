<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\Interfaces;

use BlueMvc\Core\Collections\RequestCookieCollection;
use BlueMvc\Core\Interfaces\Collections\HeaderCollectionInterface;
use BlueMvc\Core\Interfaces\Collections\ParameterCollectionInterface;
use BlueMvc\Core\Interfaces\Collections\UploadedFileCollectionInterface;
use BlueMvc\Core\Interfaces\Http\MethodInterface;
use DataTypes\Interfaces\IPAddressInterface;
use DataTypes\Interfaces\UrlInterface;

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
    public function getClientIp();

    /**
     * Returns a cookie by cookie name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The cookie name.
     *
     * @return RequestCookieInterface|null The cookie by cookie name if it exists, null otherwise.
     */
    public function getCookie($name);

    /**
     * Returns the cookies.
     *
     * @since 1.0.0
     *
     * @return RequestCookieCollection The cookies.
     */
    public function getCookies();

    /**
     * Returns a form parameter value by form parameter name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The form parameter name.
     *
     * @return string|null The form parameter value by form parameter name if it exists, null otherwise.
     */
    public function getFormParameter($name);

    /**
     * Returns the form parameters.
     *
     * @since 1.0.0
     *
     * @return ParameterCollectionInterface The form parameters.
     */
    public function getFormParameters();

    /**
     * Returns a header value by header name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The header name.
     *
     * @return string|null The header value by header name if it exists, null otherwise.
     */
    public function getHeader($name);

    /**
     * Returns the headers.
     *
     * @since 1.0.0
     *
     * @return HeaderCollectionInterface The headers.
     */
    public function getHeaders();

    /**
     * Returns the http method.
     *
     * @since 1.0.0
     *
     * @return MethodInterface The http method.
     */
    public function getMethod();

    /**
     * Returns a query parameter value by query parameter name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The query parameter name.
     *
     * @return string|null The query parameter value by query parameter name if it exists, null otherwise.
     */
    public function getQueryParameter($name);

    /**
     * Returns the query parameters.
     *
     * @since 1.0.0
     *
     * @return ParameterCollectionInterface The query parameters.
     */
    public function getQueryParameters();

    /**
     * Returns the raw content from request.
     *
     * @since 1.0.0
     *
     * @return string The raw content from request.
     */
    public function getRawContent();

    /**
     * Returns a uploaded file by name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The uploaded file name.
     *
     * @return UploadedFileInterface|null The uploaded file by name if it exists, null otherwise.
     */
    public function getUploadedFile($name);

    /**
     * Returns the uploaded files.
     *
     * @since 1.0.0
     *
     * @return UploadedFileCollectionInterface The uploaded files.
     */
    public function getUploadedFiles();

    /**
     * Returns the user agent or empty string if no user agent exists.
     *
     * @since 1.0.0
     *
     * @return string The user agent or empty string if no user agent exists.
     */
    public function getUserAgent();

    /**
     * Returns the url.
     *
     * @since 1.0.0
     *
     * @return UrlInterface The url.
     */
    public function getUrl();
}
