<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core;

use BlueMvc\Core\Interfaces\ErrorControllerInterface;

/**
 * Class representing an error handling controller.
 *
 * @since 1.0.0
 */
abstract class ErrorController extends Controller implements ErrorControllerInterface
{
    /**
     * Constructs the controller.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();

        $this->myException = null;
    }

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
    private $myException;
}
