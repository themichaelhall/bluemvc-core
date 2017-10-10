<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core;

use BlueMvc\Core\Base\AbstractCookie;
use BlueMvc\Core\Interfaces\ResponseCookieInterface;

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
     */
    public function __construct($value, \DateTimeImmutable $expiry = null)
    {
        parent::__construct($value);

        $this->myExpiry = $expiry;
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
     * @var \DateTimeImmutable|null My expiry time or null if no expiry time.
     */
    private $myExpiry;
}
