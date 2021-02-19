<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Helpers\TestControllers;

use BlueMvc\Core\ActionResults\NotFoundResult;
use BlueMvc\Core\Controller;

/**
 * Pre- and post-action event test controller class.
 */
class PreAndPostActionEventController extends Controller
{
    /**
     * Index action.
     *
     * @return string The result.
     */
    public function indexAction(): string
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
    public function defaultAction($action): string
    {
        return 'Default action "' . $action . '" with pre- and post-action event';
    }

    /**
     * Pre-action event.
     *
     * @return NotFoundResult|null The result.
     */
    protected function onPreActionEvent(): ?NotFoundResult
    {
        parent::onPreActionEvent();

        if ($this->getRequest()->getUrl()->getPort() === 81) {
            return new NotFoundResult('This is a pre-action result');
        }

        $this->getResponse()->addHeader('X-Pre-Action', 'true');

        return null;
    }

    /**
     * Post-action event.
     *
     * @return string|null The result.
     */
    protected function onPostActionEvent(): ?string
    {
        parent::onPostActionEvent();

        if ($this->getRequest()->getUrl()->getPort() === 82) {
            return 'This is a post-action result';
        }

        $this->getResponse()->addHeader('X-Post-Action', 'true');

        return null;
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
