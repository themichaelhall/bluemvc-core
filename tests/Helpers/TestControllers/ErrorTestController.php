<?php

use BlueMvc\Core\Controller;
use BlueMvc\Core\View;

/**
 * Test controller that handles errors.
 */
class ErrorTestController extends Controller
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
        return new View('Error: ' . $statusCode);
    }
}
