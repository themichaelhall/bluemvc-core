<?php

/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

declare(strict_types=1);

namespace BlueMvc\Core\Base;

use BlueMvc\Core\Collections\HeaderCollection;
use BlueMvc\Core\Collections\ResponseCookieCollection;
use BlueMvc\Core\Exceptions\InvalidResponseCookiePathException;
use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Interfaces\Collections\HeaderCollectionInterface;
use BlueMvc\Core\Interfaces\Collections\ResponseCookieCollectionInterface;
use BlueMvc\Core\Interfaces\Http\StatusCodeInterface;
use BlueMvc\Core\Interfaces\ResponseCookieInterface;
use BlueMvc\Core\Interfaces\ResponseInterface;
use BlueMvc\Core\ResponseCookie;
use DataTypes\Net\HostInterface;
use DataTypes\Net\UrlPathInterface;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

/**
 * Abstract class representing a web response.
 *
 * @since 1.0.0
 */
abstract class AbstractResponse implements ResponseInterface
{
    /**
     * Constructs a response.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->setContent('');
        $this->setHeaders(new HeaderCollection());
        $this->setStatusCode(new StatusCode(StatusCode::OK));
        $this->setCookies(new ResponseCookieCollection());
    }

    /**
     * Adds a header.
     *
     * @since 1.0.0
     *
     * @param string $name  The header name.
     * @param string $value The header value.
     */
    public function addHeader(string $name, string $value): void
    {
        $this->headers->add($name, $value);
    }

    /**
     * Returns the content.
     *
     * @since 1.0.0
     *
     * @return string The content.
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Returns a cookie by cookie name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The cookie name.
     *
     * @return ResponseCookieInterface|null The cookie if it exists, null otherwise.
     */
    public function getCookie(string $name): ?ResponseCookieInterface
    {
        return $this->cookies->get($name);
    }

    /**
     * Returns the cookies.
     *
     * @since 1.0.0
     *
     * @return ResponseCookieCollectionInterface The cookies.
     */
    public function getCookies(): ResponseCookieCollectionInterface
    {
        return $this->cookies;
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
     * Returns the status code.
     *
     * @since 1.0.0
     *
     * @return StatusCodeInterface The status code.
     */
    public function getStatusCode(): StatusCodeInterface
    {
        return $this->statusCode;
    }

    /**
     * Sets the content.
     *
     * @since 1.0.0
     *
     * @param string $content The content.
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * Sets a cookie.
     *
     * @since 1.0.0
     *
     * @param string                  $name   The cookie name.
     * @param ResponseCookieInterface $cookie The cookie.
     */
    public function setCookie(string $name, ResponseCookieInterface $cookie): void
    {
        $this->cookies->set($name, $cookie);
    }

    /**
     * Sets the cookies.
     *
     * @since 1.0.0
     *
     * @param ResponseCookieCollectionInterface $cookies The cookies.
     */
    public function setCookies(ResponseCookieCollectionInterface $cookies): void
    {
        $this->cookies = $cookies;
    }

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
     *
     * @throws InvalidResponseCookiePathException If the path is not a directory or an absolute path.
     */
    public function setCookieValue(string $name, string $value, ?DateTimeInterface $expiry = null, ?UrlPathInterface $path = null, ?HostInterface $domain = null, bool $isSecure = false, bool $isHttpOnly = false): void
    {
        $this->cookies->set($name, new ResponseCookie($value, $expiry, $path, $domain, $isSecure, $isHttpOnly));
    }

    /**
     * Sets the expiry date time.
     *
     * @since 2.2.0
     *
     * @param DateTimeImmutable|null $expiryDateTime The expiry date time or null for immediate expiry.
     */
    public function setExpiryDateTime(?DateTimeImmutable $expiryDateTime): void
    {
        $now = new DateTimeImmutable();
        $expiryDateTime = $expiryDateTime ?: $now;

        $this->setHeader('Date', $now->setTimezone(new DateTimeZone('UTC'))->format('D, d M Y H:i:s \G\M\T'));
        $this->setHeader('Expires', $expiryDateTime->setTimezone(new DateTimeZone('UTC'))->format('D, d M Y H:i:s \G\M\T'));

        if ($expiryDateTime <= $now) {
            $this->setHeader('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0');

            return;
        }

        $maxAge = $expiryDateTime->getTimestamp() - $now->getTimestamp();
        $this->setHeader('Cache-Control', 'public, max-age=' . $maxAge);
    }

    /**
     * Sets a header.
     *
     * @since 1.0.0
     *
     * @param string $name  The header name.
     * @param string $value The header value.
     */
    public function setHeader(string $name, string $value): void
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
    public function setHeaders(HeaderCollectionInterface $headers): void
    {
        $this->headers = $headers;
    }

    /**
     * Sets the status code.
     *
     * @since 1.0.0
     *
     * @param StatusCodeInterface $statusCode The status code.
     */
    public function setStatusCode(StatusCodeInterface $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    /**
     * Outputs the content.
     *
     * @since 1.0.0
     */
    abstract public function output(): void;

    /**
     * @var string The content.
     */
    private string $content;

    /**
     * @var ResponseCookieCollectionInterface The cookies.
     */
    private ResponseCookieCollectionInterface $cookies;

    /**
     * @var HeaderCollectionInterface The headers.
     */
    private HeaderCollectionInterface $headers;

    /**
     * @var StatusCodeInterface The status code.
     */
    private StatusCodeInterface $statusCode;
}
