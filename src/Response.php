<?php

/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

declare(strict_types=1);

namespace BlueMvc\Core;

use BlueMvc\Core\Base\AbstractResponse;
use BlueMvc\Core\Interfaces\ResponseCookieInterface;

/**
 * Class representing a web response.
 *
 * @since 1.0.0
 */
class Response extends AbstractResponse
{
    /**
     * Constructs a response.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Outputs the content.
     *
     * @since 1.0.0
     */
    public function output(): void
    {
        header('HTTP/1.1 ' . $this->getStatusCode());

        $this->outputHeaders();
        $this->outputCookies();
        $this->destroySessionIfEmpty();

        echo $this->getContent();
    }

    /**
     * Output the headers.
     */
    private function outputHeaders(): void
    {
        foreach ($this->getHeaders() as $headerName => $headerValue) {
            header($headerName . ': ' . $headerValue);
        }
    }

    /**
     * Output the cookies.
     */
    private function outputCookies(): void
    {
        foreach ($this->getCookies() as $cookieName => $cookie) {
            /** @var ResponseCookieInterface $cookie */
            setcookie(
                $cookieName,
                $cookie->getValue(),
                $cookie->getExpiry() !== null ? $cookie->getExpiry()->getTimestamp() : 0,
                $cookie->getPath() !== null ? $cookie->getPath()->__toString() : '',
                $cookie->getDomain() !== null ? $cookie->getDomain()->__toString() : '',
                $cookie->isSecure(),
                $cookie->isHttpOnly()
            );
        }
    }

    /**
     * Destroys an active session if it is empty.
     */
    private function destroySessionIfEmpty(): void
    {
        if (empty($_COOKIE[session_name()])) {
            return;
        }

        if (!empty($_SESSION)) {
            return;
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            return;
        }

        session_destroy();

        $cookieParams = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            1,
            $cookieParams['path'],
            $cookieParams['domain'],
            $cookieParams['secure'],
            $cookieParams['httponly']
        );
    }
}
