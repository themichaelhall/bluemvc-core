<?php

use BlueMvc\Core\Controller;

/**
 * Pre-action event test controller class.
 */
class PreActionEventController extends Controller
{
    /**
     * Index action.
     *
     * @return string The result.
     */
    public function indexAction()
    {
        return 'Index action with pre-action event';
    }

    /**
     * Default action.
     *
     * @return string The result.
     */
    public function defaultAction()
    {
        return 'Default action with pre-action event';
    }

    /**
     * Pre-action event.
     *
     * @return null
     */
    protected function onPreActionEvent()
    {
        $this->getResponse()->addHeader('X-Pre-Action', 'true');

        return null;
    }
}
