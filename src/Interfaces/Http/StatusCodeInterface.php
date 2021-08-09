<?php

/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

declare(strict_types=1);

namespace BlueMvc\Core\Interfaces\Http;

/**
 * Interface for StatusCode class.
 *
 * @since 1.0.0
 */
interface StatusCodeInterface
{
    /**
     * Returns the code.
     *
     * @since 1.0.0
     *
     * @return int The code.
     */
    public function getCode(): int;

    /**
     * Returns the description.
     *
     * @since 1.0.0
     *
     * @return string The description.
     */
    public function getDescription(): string;

    /**
     * Returns true if this is a 4xx client error code, false otherwise.
     *
     * @since 2.2.0
     *
     * @return bool True if this is a 4xx client error code, false otherwise.
     */
    public function isClientError(): bool;

    /**
     * Returns true if this is a 4xx or 5xx error code, false otherwise.
     *
     * @since 1.0.0
     *
     * @return bool True if this is a 4xx or 5xx error code, false otherwise.
     */
    public function isError(): bool;

    /**
     * Returns true if this is a 1xx informational code, false otherwise.
     *
     * @since 2.2.0
     *
     * @return bool True if this is a 1xx informational code, false otherwise.
     */
    public function isInformational(): bool;

    /**
     * Returns true if this is a 3xx redirection code, false otherwise.
     *
     * @since 2.2.0
     *
     * @return bool True if this is a 3xx redirection code, false otherwise.
     */
    public function isRedirection(): bool;

    /**
     * Returns true if this is a 5xx server error code, false otherwise.
     *
     * @since 2.2.0
     *
     * @return bool True if this is a 5xx server error code, false otherwise.
     */
    public function isServerError(): bool;

    /**
     * Returns true if this is a 2xx successful code, false otherwise.
     *
     * @since 2.2.0
     *
     * @return bool True if this is a 2xx successful code, false otherwise.
     */
    public function isSuccessful(): bool;

    /**
     * Returns the status code as string.
     *
     * @since 1.0.0
     *
     * @return string The status code as a string.
     */
    public function __toString(): string;
}
