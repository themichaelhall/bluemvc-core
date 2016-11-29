<?php

use BlueMvc\Core\Controller;
use BlueMvc\Core\View;

/**
 * Default action with view test controller class.
 */
class DefaultActionWithViewTestController extends Controller
{
    /**
     * The default action.
     *
     * @param string $action The action.
     *
     * @return View The view.
     */
    public function defaultAction($action)
    {
        return new View($action);
    }
}
