<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Helpers\TestControllers;

use BlueMvc\Core\ActionResults\ActionResult;
use BlueMvc\Core\ActionResults\BadRequestResult;
use BlueMvc\Core\ActionResults\MethodNotAllowedResultException;
use BlueMvc\Core\ActionResults\NoContentResultException;
use BlueMvc\Core\ActionResults\NotFoundResultException;
use BlueMvc\Core\ActionResults\NotModifiedResult;
use BlueMvc\Core\Controller;
use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Interfaces\ActionResults\ActionResultInterface;

/**
 * Controller testing action result handling in combination with post action event.
 */
class ActionResultWithPostActionEventTestController extends Controller
{
    /**
     * @throws NoContentResultException
     */
    public function indexAction(): void
    {
        throw new NoContentResultException();
    }

    /**
     * @return ActionResultInterface
     */
    public function fooAction(): ActionResultInterface
    {
        return new NotModifiedResult();
    }

    /**
     * @throws MethodNotAllowedResultException
     */
    public function barAction(): void
    {
        throw new MethodNotAllowedResultException('Bar Action');
    }

    /**
     * @return ActionResultInterface
     */
    public function bazAction(): ActionResultInterface
    {
        return new BadRequestResult('Baz Action');
    }

    /**
     * @throws NotFoundResultException
     *
     * @return ActionResultInterface
     */
    protected function onPostActionEvent(): ActionResultInterface
    {
        parent::onPostActionEvent();

        if ($this->getResponse()->getStatusCode()->isError()) {
            throw new NotFoundResultException('Failed with status: ' . $this->getResponse()->getStatusCode());
        }

        return new ActionResult('Ok', new StatusCode(StatusCode::OK));
    }
}
