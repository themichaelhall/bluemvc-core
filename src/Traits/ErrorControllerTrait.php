<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */
declare(strict_types=1);

namespace BlueMvc\Core\Traits;

/**
 * Trait for error controller functionality.
 *
 * @since 1.1.0
 */
trait ErrorControllerTrait
{
    /**
     * Returns the exception or null if no exception.
     *
     * @since 1.0.0
     *
     * @return \Exception|null The exception or null if no exception.
     */
    public function getException(): ?\Exception
    {
        return $this->exception;
    }

    /**
     * Sets the exception.
     *
     * @since 1.0.0
     *
     * @param \Exception $exception The exception.
     */
    public function setException(\Exception $exception): void
    {
        $this->exception = $exception;
    }

    /**
     * Returns true if post-action event is enabled, false otherwise.
     *
     * @since 1.1.0
     *
     * @return bool True if post-action event is enabled, false otherwise.
     */
    protected function isPostActionEventEnabled(): bool
    {
        return $this->exception === null;
    }

    /**
     * Returns true if pre-action event is enabled, false otherwise.
     *
     * @since 1.1.0
     *
     * @return bool True if pre-action event is enabled, false otherwise.
     */
    protected function isPreActionEventEnabled(): bool
    {
        return $this->exception === null;
    }

    /**
     * @var \Exception|null My exception or null if no exception.
     */
    private $exception = null;
}
