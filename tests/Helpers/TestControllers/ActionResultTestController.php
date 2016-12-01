<?php

use BlueMvc\Core\ActionResults\NotFoundResult;
use BlueMvc\Core\Controller;

/**
 * Action result test controller class.
 */
class ActionResultTestController extends Controller
{
    /**
     * Action returning a "404 Not Found" action result.
     */
    public function notfoundAction()
    {
        return new NotFoundResult('Page was not found');
    }
}
