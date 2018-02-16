<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

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
    public function getException()
    {
        return $this->myException;
    }

    /**
     * Sets the exception.
     *
     * @since 1.0.0
     *
     * @param \Exception $exception The exception.
     */
    public function setException(\Exception $exception)
    {
        $this->myException = $exception;
    }

    /**
     * @var \Exception|null My exception or null if no exception.
     */
    private $myException = null;
}
