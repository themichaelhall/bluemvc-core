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
use DataTypes\UrlPath;

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
     * @param string                  $value  The value.
     * @param \DateTimeImmutable|null $expiry The expiry time or null if no expiry time.
     * @param UrlPath|null            $path   The path or null if no path.
     *
     * @throws InvalidResponseCookiePathException If the path is not a directory or an absolute path.
     */
    public function __construct($value, \DateTimeImmutable $expiry = null, UrlPath $path = null)
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

        $this->myExpiry = $expiry;
        $this->myPath = $path;
    }

    /**
     * Returns the expiry time or null if no expiry time.
     *
     * @since 1.0.0
     *
     * @return \DateTimeImmutable|null The expiry time or null if no expiry time.
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
     * @return UrlPath|null The path or null if no path.
     */
    public function getPath()
    {
        return $this->myPath;
    }

    /**
     * @var \DateTimeImmutable|null My expiry time or null if no expiry time.
     */
    private $myExpiry;

    /**
     * @var UrlPath|null My path or null if no path.
     */
    private $myPath;
}
