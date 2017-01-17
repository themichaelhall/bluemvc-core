<?php

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
    public function indexAction()
    {
        return 'Index action with pre- and post-action event';
    }

    /**
     * Default action.
     *
     * @return string The result.
     */
    public function defaultAction()
    {
        return 'Default action with pre- and post-action event';
    }

    /**
     * Pre-action event.
     *
     * @return null
     */
    protected function onPreActionEvent()
    {
        if ($this->getRequest()->getUrl()->getPort() === 81) {
            return new NotFoundResult('This is a pre-action result');
        }

        $this->getResponse()->addHeader('X-Pre-Action', 'true');

        return null;
    }

    /**
     * Post-action event.
     *
     * @return null
     */
    protected function onPostActionEvent()
    {
        $this->getResponse()->addHeader('X-Post-Action', 'true');

        return null;
    }
}
