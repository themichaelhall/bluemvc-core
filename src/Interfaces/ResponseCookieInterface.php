<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */
declare(strict_types=1);

namespace BlueMvc\Core\Interfaces;

use DataTypes\Interfaces\HostInterface;
use DataTypes\Interfaces\UrlPathInterface;

/**
 * Interface for ResponseCookie class.
 *
 * @since 1.0.0
 */
interface ResponseCookieInterface
{
    /**
     * Returns the domain or null if no domain.
     *
     * @since 1.0.0
     *
     * @return HostInterface|null The domain or null if no domain.
     */
    public function getDomain(): ?HostInterface;

    /**
     * Returns the expiry time or null if no expiry time.
     *
     * @since 1.0.0
     *
     * @return \DateTimeInterface|null The expiry time or null if no expiry time.
     */
    public function getExpiry(): ?\DateTimeInterface;

    /**
     * Returns the path or null if no path.
     *
     * @since 1.0.0
     *
     * @return UrlPathInterface|null The path or null if no path.
     */
    public function getPath(): ?UrlPathInterface;

    /**
     * Returns the value.
     *
     * @since 1.0.0
     *
     * @return string The value.
     */
    public function getValue(): string;

    /**
     * Returns true if cookie is http only, false otherwise.
     *
     * @since 1.0.0
     *
     * @return bool True if cookie is http only, false otherwise.
     */
    public function isHttpOnly(): bool;

    /**
     * Returns true if cookie is secure, false otherwise.
     *
     * @since 1.0.0
     *
     * @return bool True if cookie is secure, false otherwise.
     */
    public function isSecure(): bool;

    /**
     * Returns the value.
     *
     * @since 1.0.0
     *
     * @return string The value.
     */
    public function __toString(): string;
}
