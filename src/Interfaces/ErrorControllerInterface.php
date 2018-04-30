<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */
declare(strict_types=1);

namespace BlueMvc\Core\Interfaces;

/**
 * Interface for ErrorController class.
 *
 * @since 1.0.0
 */
interface ErrorControllerInterface extends ControllerInterface
{
    /**
     * Returns the exception or null if no exception.
     *
     * @since 1.0.0
     *
     * @return \Exception|null The exception or null if no exception.
     */
    public function getException(): ?\Exception;

    /**
     * Sets the exception.
     *
     * @since 1.0.0
     *
     * @param \Exception $exception The exception.
     */
    public function setException(\Exception $exception): void;
}
