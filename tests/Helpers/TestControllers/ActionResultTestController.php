<?php

use BlueMvc\Core\ActionResults\NotFoundResult;
use BlueMvc\Core\ActionResults\RedirectResult;
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

    /**
     * Action returning a "302 Found" action result.
     */
    public function redirectAction()
    {
        return new RedirectResult('/foo/bar');
    }
}
