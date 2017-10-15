<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core;

use BlueMvc\Core\Base\AbstractCookie;
use BlueMvc\Core\Exceptions\InvalidResponseCookiePathException;
use BlueMvc\Core\Interfaces\ResponseCookieInterface;
use DataTypes\Interfaces\HostInterface;
use DataTypes\Interfaces\UrlPathInterface;

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
     * @param string                  $value      The value.
     * @param \DateTimeInterface|null $expiry     The expiry time or null if no expiry time.
     * @param UrlPathInterface|null   $path       The path or null if no path.
     * @param HostInterface|null      $domain     The domain or null if no domain.
     * @param bool                    $isSecure   True if cookie is secure, false otherwise.
     * @param bool                    $isHttpOnly True if cookie is http only, false otherwise.
     *
     * @throws InvalidResponseCookiePathException If the path is not a directory or an absolute path.
     * @throws \InvalidArgumentException          If the $isSecure or $isHttpOnly parameter is not a boolean.
     */
    public function __construct($value, \DateTimeInterface $expiry = null, UrlPathInterface $path = null, HostInterface $domain = null, $isSecure = false, $isHttpOnly = false)
    {
        if (!is_bool($isSecure)) {
            throw new \InvalidArgumentException('$isSecure parameter is not a boolean.');
        }

        if (!is_bool($isHttpOnly)) {
            throw new \InvalidArgumentException('$isHttpOnly parameter is not a boolean.');
        }

        parent::__construct($value);

        if ($path !== null) {
            if (!$path->isDirectory()) {
                throw new InvalidResponseCookiePathException('Path "' . $path . '" is not a directory.');
            }

            if (!$path->isAbsolute()) {
                throw new InvalidResponseCookiePathException('Path "' . $path . '" is not an absolute path.');
            }
        }

        $this->myExpiry = $expiry;
        $this->myPath = $path;
        $this->myDomain = $domain;
        $this->myIsSecure = $isSecure;
        $this->myIsHttpOnly = $isHttpOnly;
    }

    /**
     * Returns the domain or null if no domain.
     *
     * @since 1.0.0
     *
     * @return HostInterface|null The domain or null if no domain.
     */
    public function getDomain()
    {
        return $this->myDomain;
    }

    /**
     * Returns the expiry time or null if no expiry time.
     *
     * @since 1.0.0
     *
     * @return \DateTimeInterface|null The expiry time or null if no expiry time.
     */
    public function getExpiry()
    {
        return $this->myExpiry;
    }

    /**
     * Returns the path or null if no path.
     *
     * @since 1.0.0
     *
     * @return UrlPathInterface|null The path or null if no path.
     */
    public function getPath()
    {
        return $this->myPath;
    }

    /**
     * Returns true if cookie is http only, false otherwise.
     *
     * @since 1.0.0
     *
     * @return bool True if cookie is http only, false otherwise.
     */
    public function isHttpOnly()
    {
        return $this->myIsHttpOnly;
    }

    /**
     * Returns true if cookie is secure, false otherwise.
     *
     * @since 1.0.0
     *
     * @return bool True if cookie is secure, false otherwise.
     */
    public function isSecure()
    {
        return $this->myIsSecure;
    }

    /**
     * @var \DateTimeInterface|null My expiry time or null if no expiry time.
     */
    private $myExpiry;

    /**
     * @var UrlPathInterface|null My path or null if no path.
     */
    private $myPath;

    /**
     * @var HostInterface|null My domain or null if no domain.
     */
    private $myDomain;

    /**
     * @var bool True if cookie is secure, false otherwise.
     */
    private $myIsSecure;

    /**
     * @var bool True if cookie is http only, false otherwise.
     */
    private $myIsHttpOnly;
}
