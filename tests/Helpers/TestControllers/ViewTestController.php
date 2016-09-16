<?php

use BlueMvc\Core\Controller;
use BlueMvc\Core\View;

/**
 * View test controller class.
 */
class ViewTestController extends Controller
{
    /**
     * Index action.
     *
     * @return View The view.
     */
    public function indexAction()
    {
        return new View();
    }

    /**
     * Action with model.
     *
     * @return View The view.
     */
    public function withmodelAction()
    {
        return new View('This is the model.');
    }
}
