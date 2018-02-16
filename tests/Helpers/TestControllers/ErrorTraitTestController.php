<?php

namespace BlueMvc\Core\Tests\Helpers\TestControllers;

use BlueMvc\Core\Controller;
use BlueMvc\Core\Interfaces\ErrorControllerInterface;
use BlueMvc\Core\Traits\ErrorControllerTrait;
use BlueMvc\Core\View;

/**
 * Test controller that handles errors via ErrorControllerTrait.
 */
class ErrorTraitTestController extends Controller implements ErrorControllerInterface
{
    use ErrorControllerTrait;

    /**
     * 403 Forbidden action.
     */
    public function _403Action()
    {
        // This emulates the case when there is a bug in the error controller.
        throw new \RuntimeException('Exception thrown from 403 action.');
    }

    /**
     * Default action.
     *
     * @param string $statusCode The status code as a string.
     *
     * @return View The result.
     */
    public function defaultAction($statusCode)
    {
        $errorText = 'Error: ' . $statusCode;
        $exception = $this->getException();
        if ($exception !== null) {
            // Use standard error page for DomainException.
            if ($exception instanceof \DomainException) {
                return null;
            }

            $errorText .= ', Exception: ' . get_class($exception) . ', ExceptionMessage: ' . $exception->getMessage();
        }

        return new View($errorText);
    }
}
