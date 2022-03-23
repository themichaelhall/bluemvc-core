<?php

/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

declare(strict_types=1);

namespace BlueMvc\Core\Interfaces;

use BlueMvc\Core\Interfaces\Collections\HeaderCollectionInterface;
use BlueMvc\Core\Interfaces\Collections\ResponseCookieCollectionInterface;
use BlueMvc\Core\Interfaces\Http\StatusCodeInterface;
use DataTypes\Net\HostInterface;
use DataTypes\Net\UrlPathInterface;
use DateTimeImmutable;
use DateTimeInterface;

/**
 * Interface for Response class.
 *
 * @since 1.0.0
 */
interface ResponseInterface
{
    /**
     * Adds a header.
     *
     * @since 1.0.0
     *
     * @param string $name  The header name.
     * @param string $value The header value.
     */
    public function addHeader(string $name, string $value): void;

    /**
     * Returns the content.
     *
     * @since 1.0.0
     *
     * @return string The content.
     */
    public function getContent(): string;

    /**
     * Returns a cookie by cookie name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The cookie name.
     *
     * @return ResponseCookieInterface|null The cookie if it exists, null otherwise.
     */
    public function getCookie(string $name): ?ResponseCookieInterface;

    /**
     * Returns the cookies.
     *
     * @since 1.0.0
     *
     * @return ResponseCookieCollectionInterface The cookies.
     */
    public function getCookies(): ResponseCookieCollectionInterface;

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
     * Returns the status code.
     *
     * @since 1.0.0
     *
     * @return StatusCodeInterface The status code.
     */
    public function getStatusCode(): StatusCodeInterface;

    /**
     * Sets the content.
     *
     * @since 1.0.0
     *
     * @param string $content The content.
     */
    public function setContent(string $content): void;

    /**
     * Sets a cookie.
     *
     * @since 1.0.0
     *
     * @param string                  $name   The cookie name.
     * @param ResponseCookieInterface $cookie The cookie.
     */
    public function setCookie(string $name, ResponseCookieInterface $cookie): void;

    /**
     * Sets the cookies.
     *
     * @since 1.0.0
     *
     * @param ResponseCookieCollectionInterface $cookies The cookies.
     */
    public function setCookies(ResponseCookieCollectionInterface $cookies): void;

    /**
     * Sets a cookie value.
     *
     * @since 1.1.0
     *
     * @param string                 $name       The name.
     * @param string                 $value      The value.
     * @param DateTimeInterface|null $expiry     The expiry time or null if no expiry time.
     * @param UrlPathInterface|null  $path       The path or null if no path.
     * @param HostInterface|null     $domain     The domain or null if no domain.
     * @param bool                   $isSecure   True if cookie is secure, false otherwise.
     * @param bool                   $isHttpOnly True if cookie is http only, false otherwise.
     */
    public function setCookieValue(string $name, string $value, ?DateTimeInterface $expiry = null, ?UrlPathInterface $path = null, ?HostInterface $domain = null, bool $isSecure = false, bool $isHttpOnly = false): void;

    /**
     * Sets the expiry date time.
     *
     * @since 2.2.0
     *
     * @param DateTimeImmutable|null $expiryDateTime The expiry date time or null for immediate expiry.
     */
    public function setExpiryDateTime(?DateTimeImmutable $expiryDateTime): void;

    /**
     * Sets a header.
     *
     * @since 1.0.0
     *
     * @param string $name  The header name.
     * @param string $value The header value.
     */
    public function setHeader(string $name, string $value): void;

    /**
     * Sets the headers.
     *
     * @since 1.0.0
     *
     * @param HeaderCollectionInterface $headers The headers.
     */
    public function setHeaders(HeaderCollectionInterface $headers): void;

    /**
     * Sets the status code.
     *
     * @since 1.0.0
     *
     * @param StatusCodeInterface $statusCode The status code.
     */
    public function setStatusCode(StatusCodeInterface $statusCode): void;

    /**
     * Outputs the content.
     *
     * @since 1.0.0
     */
    public function output(): void;
}
