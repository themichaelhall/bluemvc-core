<?php

use BlueMvc\Core\ActionResults\NotFoundResult;
use BlueMvc\Core\ActionResults\PermanentRedirectResult;
use BlueMvc\Core\ActionResults\RedirectResult;
use BlueMvc\Core\Controller;

/**
 * Action result test controller class.
 */
class ActionResultTestController extends Controller
{
    /**
     * Action returning a "404 Not Found" action result.
     *
     * @return NotFoundResult The action result.
     */
    public function notfoundAction()
    {
        return new NotFoundResult('Page was not found');
    }

    /**
     * Action returning a "302 Found" action result.
     *
     * @return RedirectResult The action result.
     */
    public function redirectAction()
    {
        return new RedirectResult('/foo/bar');
    }

    /**
     * Action returning a "301 Moved Permanently" action result.
     *
     * @return PermanentRedirectResult The action result.
     */
    public function permanentRedirectAction()
    {
        return new PermanentRedirectResult('https://domain.com/');
    }
}
