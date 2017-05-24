<?php

namespace BlueMvc\Core\Tests\Helpers\TestControllers;

use BlueMvc\Core\ErrorController;
use BlueMvc\Core\View;

/**
 * Test controller that handles errors.
 */
class ErrorTestController extends ErrorController
{
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
            $errorText .= ', Exception: ' . get_class($exception) . ', ExceptionMessage: ' . $exception->getMessage();
        }

        return new View($errorText);
    }
}
