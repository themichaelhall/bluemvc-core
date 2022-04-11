<?php

/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

declare(strict_types=1);

namespace BlueMvc\Core\Interfaces\Http;

use Stringable;

/**
 * Interface for Method class.
 *
 * @since 1.0.0
 */
interface MethodInterface extends Stringable
{
    /**
     * Returns the method name.
     *
     * @since 1.0.0
     *
     * @return string The method name.
     */
    public function getName(): string;

    /**
     * Returns true if this is a DELETE method, false otherwise.
     *
     * @since 1.0.0
     *
     * @return bool True if this is a DELETE method, false otherwise.
     */
    public function isDelete(): bool;

    /**
     * Returns true if this is a GET method, false otherwise.
     *
     * @since 1.0.0
     *
     * @return bool True if this is a GET method, false otherwise.
     */
    public function isGet(): bool;

    /**
     * Returns true if this is a POST method, false otherwise.
     *
     * @since 1.0.0
     *
     * @return bool True if this is a POST method, false otherwise.
     */
    public function isPost(): bool;

    /**
     * Returns true if this is a PUT method, false otherwise.
     *
     * @since 1.0.0
     *
     * @return bool True if this is a PUT method, false otherwise.
     */
    public function isPut(): bool;
}
