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
     * Returns the throwable or null if no throwable.
     *
     * @since 2.0.0
     *
     * @return \Throwable|null The throwable or null if no throwable.
     */
    public function getThrowable(): ?\Throwable;

    /**
     * Sets the throwable.
     *
     * @since 2.0.0
     *
     * @param \Throwable $throwable The throwable.
     */
    public function setThrowable(\Throwable $throwable): void;
}
