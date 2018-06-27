<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Helpers\TestControllers;

use BlueMvc\Core\ActionResults\ActionResult;
use BlueMvc\Core\ActionResults\ActionResultException;
use BlueMvc\Core\ActionResults\CreatedResultException;
use BlueMvc\Core\ActionResults\ForbiddenResultException;
use BlueMvc\Core\ActionResults\JsonResultException;
use BlueMvc\Core\ActionResults\MethodNotAllowedResultException;
use BlueMvc\Core\ActionResults\NoContentResultException;
use BlueMvc\Core\ActionResults\NotFoundResultException;
use BlueMvc\Core\ActionResults\NotModifiedResultException;
use BlueMvc\Core\ActionResults\PermanentRedirectResultException;
use BlueMvc\Core\ActionResults\RedirectResultException;
use BlueMvc\Core\Controller;
use BlueMvc\Core\Http\StatusCode;

/**
 * Action result exception test controller.
 */
class ActionResultExceptionTestController extends Controller
{
    /**
     * Action throwing a "404 Not Found" action result exception.
     *
     * @throws NotFoundResultException
     */
    public function notFoundAction()
    {
        throw new NotFoundResultException('Page was not found');
    }

    /**
     * Action throwing a "302 Found" action result exception.
     *
     * @throws ActionResultException
     */
    public function redirectAction()
    {
        throw new RedirectResultException('/foo/bar');
    }

    /**
     * Action throwing a "301 Moved Permanently" action result exception.
     *
     * @throws PermanentRedirectResultException
     */
    public function permanentRedirectAction()
    {
        throw new PermanentRedirectResultException('https://domain.com/');
    }

    /**
     * Action throwing a "403 Forbidden" action result exception.
     *
     * @throws ForbiddenResultException
     */
    public function forbiddenAction()
    {
        throw new ForbiddenResultException('Page is forbidden');
    }

    /**
     * Action throwing a "204 No Content" action result exception.
     *
     * @throws NoContentResultException
     */
    public function noContentAction()
    {
        throw new NoContentResultException();
    }

    /**
     * Action throwing a "304 Not Modified" action result exception.
     *
     * @throws NotModifiedResultException
     */
    public function notModifiedAction()
    {
        throw new NotModifiedResultException();
    }

    /**
     * Action throwing a "405 Method Not Allowed" action result exception.
     *
     * @throws MethodNotAllowedResultException
     */
    public function methodNotAllowedAction()
    {
        throw new MethodNotAllowedResultException();
    }

    /**
     * Action throwing a JSON action result exception.
     *
     * @throws JsonResultException
     */
    public function jsonAction()
    {
        throw new JsonResultException(['Foo' => 1, 'Bar' => ['Baz' => 2]]);
    }

    /**
     * Action throwing a "201 Created" action result exception.
     *
     * @throws CreatedResultException
     */
    public function createdAction()
    {
        throw new CreatedResultException('https://example.com/created');
    }

    /**
     * Action returning a custom action result.
     *
     * @throws ActionResultException
     */
    public function customAction()
    {
        throw new ActionResultException(new ActionResult('Custom action result', new StatusCode(StatusCode::MULTI_STATUS)));
    }
}
