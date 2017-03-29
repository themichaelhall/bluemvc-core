<?php

use BlueMvc\Core\Controller;

/**
 * Test controller that throws an exception.
 */
class ExceptionTestController extends Controller
{
    /**
     * Index action.
     *
     * @throws \LogicException
     */
    public function indexAction()
    {
        throw new \LogicException('Exception was thrown.');
    }
}
