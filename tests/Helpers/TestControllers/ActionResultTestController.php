<?php

namespace BlueMvc\Core\Tests\Helpers\TestControllers;

use BlueMvc\Core\ActionResults\ForbiddenResult;
use BlueMvc\Core\ActionResults\JsonResult;
use BlueMvc\Core\ActionResults\MethodNotAllowedResult;
use BlueMvc\Core\ActionResults\NoContentResult;
use BlueMvc\Core\ActionResults\NotFoundResult;
use BlueMvc\Core\ActionResults\NotModifiedResult;
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

    /**
     * Action returning a "403 Forbidden" action result.
     *
     * @return ForbiddenResult The action result.
     */
    public function forbiddenAction()
    {
        return new ForbiddenResult('Page is forbidden');
    }

    /**
     * Action returning a "204 No Content" action result.
     *
     * @return NoContentResult The action result.
     */
    public function nocontentAction()
    {
        return new NoContentResult();
    }

    /**
     * Action returning a "304 Not Modified" action result.
     *
     * @return NotModifiedResult The action result.
     */
    public function notmodifiedAction()
    {
        return new NotModifiedResult();
    }

    /**
     * Action returning a "405 Method Not Allowed" action result.
     *
     * @return MethodNotAllowedResult The action result.
     */
    public function methodnotallowedAction()
    {
        return new MethodNotAllowedResult();
    }

    /**
     * Action returning a JSON action result.
     *
     * @return JsonResult The action result.
     */
    public function jsonAction()
    {
        return new JsonResult(['Foo' => 1, 'Bar' => ['Baz' => 2]]);
    }
}
