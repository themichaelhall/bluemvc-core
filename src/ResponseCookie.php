<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */
declare(strict_types=1);

namespace BlueMvc\Core;

use BlueMvc\Core\Base\AbstractCookie;
use BlueMvc\Core\Exceptions\InvalidResponseCookiePathException;
use BlueMvc\Core\Interfaces\ResponseCookieInterface;
use DataTypes\Interfaces\HostInterface;
use DataTypes\Interfaces\UrlPathInterface;
use DateTimeInterface;

/**
 * Class representing a response cookie.
 *
 * @since 1.0.0
 */
class ResponseCookie extends AbstractCookie implements ResponseCookieInterface
{
    /**
     * Constructs a response cookie.
     *
     * @since 1.0.0
     *
     * @param string                 $value      The value.
     * @param DateTimeInterface|null $expiry     The expiry time or null if no expiry time.
     * @param UrlPathInterface|null  $path       The path or null if no path.
     * @param HostInterface|null     $domain     The domain or null if no domain.
     * @param bool                   $isSecure   True if cookie is secure, false otherwise.
     * @param bool                   $isHttpOnly True if cookie is http only, false otherwise.
     *
     * @throws InvalidResponseCookiePathException If the path is not a directory or an absolute path.
     */
    public function __construct(string $value, ?DateTimeInterface $expiry = null, ?UrlPathInterface $path = null, ?HostInterface $domain = null, bool $isSecure = false, bool $isHttpOnly = false)
    {
        parent::__construct($value);

        if ($path !== null) {
            if (!$path->isDirectory()) {
                throw new InvalidResponseCookiePathException('Path "' . $path . '" is not a directory.');
            }

            if (!$path->isAbsolute()) {
                throw new InvalidResponseCookiePathException('Path "' . $path . '" is not an absolute path.');
            }
        }

        $this->expiry = $expiry;
        $this->path = $path;
        $this->domain = $domain;
        $this->isSecure = $isSecure;
        $this->isHttpOnly = $isHttpOnly;
    }

    /**
     * Returns the domain or null if no domain.
     *
     * @since 1.0.0
     *
     * @return HostInterface|null The domain or null if no domain.
     */
    public function getDomain(): ?HostInterface
    {
        return $this->domain;
    }

    /**
     * Returns the expiry time or null if no expiry time.
     *
     * @since 1.0.0
     *
     * @return DateTimeInterface|null The expiry time or null if no expiry time.
     */
    public function getExpiry(): ?DateTimeInterface
    {
        return $this->expiry;
    }

    /**
     * Returns the path or null if no path.
     *
     * @since 1.0.0
     *
     * @return UrlPathInterface|null The path or null if no path.
     */
    public function getPath(): ?UrlPathInterface
    {
        return $this->path;
    }

    /**
     * Returns true if cookie is http only, false otherwise.
     *
     * @since 1.0.0
     *
     * @return bool True if cookie is http only, false otherwise.
     */
    public function isHttpOnly(): bool
    {
        return $this->isHttpOnly;
    }

    /**
     * Returns true if cookie is secure, false otherwise.
     *
     * @since 1.0.0
     *
     * @return bool True if cookie is secure, false otherwise.
     */
    public function isSecure(): bool
    {
        return $this->isSecure;
    }

    /**
     * @var DateTimeInterface|null My expiry time or null if no expiry time.
     */
    private $expiry;

    /**
     * @var UrlPathInterface|null My path or null if no path.
     */
    private $path;

    /**
     * @var HostInterface|null My domain or null if no domain.
     */
    private $domain;

    /**
     * @var bool True if cookie is secure, false otherwise.
     */
    private $isSecure;

    /**
     * @var bool True if cookie is http only, false otherwise.
     */
    private $isHttpOnly;
}
