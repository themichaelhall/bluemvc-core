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
     * Returns true if this is an error code, false otherwise.
     *
     * @since 1.0.0
     *
     * @return bool True if this is an error code, false otherwise.
     */
    public function isError(): bool;

    /**
     * Returns the status code as as string.
     *
     * @since 1.0.0
     *
     * @return string The status code as a string.
     */
    public function __toString(): string;
}
