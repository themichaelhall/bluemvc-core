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

        // Output headers.
        foreach ($this->getHeaders() as $headerName => $headerValue) {
            header($headerName . ': ' . $headerValue);
        }

        // Set cookies.
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

        // Output content.
        echo $this->getContent();
    }
}
