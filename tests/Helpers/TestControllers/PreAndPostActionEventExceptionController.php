<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Helpers\TestControllers;

use BlueMvc\Core\ActionResults\ActionResult;
use BlueMvc\Core\ActionResults\ActionResultException;
use BlueMvc\Core\ActionResults\NotFoundResultException;
use BlueMvc\Core\Controller;
use BlueMvc\Core\Http\StatusCode;

/**
 * Pre- and post-action event returning action result exception test controller class.
 */
class PreAndPostActionEventExceptionController extends Controller
{
    /**
     * Index action.
     *
     * @return string The result.
     */
    public function indexAction()
    {
        return 'Index action with pre- and post-action event';
    }

    /**
     * Default action.
     *
     * @param string $action The action.
     *
     * @return string The result.
     */
    public function defaultAction($action)
    {
        return 'Default action "' . $action . '" with pre- and post-action event';
    }

    /**
     * Pre-action event.
     *
     * @throws NotFoundResultException
     */
    protected function onPreActionEvent()
    {
        parent::onPreActionEvent();

        if ($this->getRequest()->getUrl()->getPort() === 81) {
            throw new NotFoundResultException('This is a pre-action result');
        }

        $this->getResponse()->addHeader('X-Pre-Action', 'true');
    }

    /**
     * Post-action event.
     *
     * @throws ActionResultException
     */
    protected function onPostActionEvent()
    {
        parent::onPostActionEvent();

        if ($this->getRequest()->getUrl()->getPort() === 82) {
            throw new ActionResultException(new ActionResult('This is a post-action result', new StatusCode(StatusCode::OK)));
        }

        $this->getResponse()->addHeader('X-Post-Action', 'true');
    }

    /** @noinspection PhpMissingParentCallCommonInspection */

    /**
     * Returns true if pre-action event is enabled, false otherwise.
     *
     * @return bool True if pre-action event is enabled, false otherwise.
     */
    protected function isPreActionEventEnabled(): bool
    {
        return !in_array($this->getRequest()->getUrl()->getPort(), [83, 85]);
    }

    /** @noinspection PhpMissingParentCallCommonInspection */

    /**
     * Returns true if post-action event is enabled, false otherwise.
     *
     * @return bool True if post-action event is enabled, false otherwise.
     */
    protected function isPostActionEventEnabled(): bool
    {
        return !in_array($this->getRequest()->getUrl()->getPort(), [84, 85]);
    }
}
