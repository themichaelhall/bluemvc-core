<?php

use BlueMvc\Core\Controller;
use BlueMvc\Core\View;

/**
 * Test controller that handles errors.
 */
class ErrorTestController extends Controller
{
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
