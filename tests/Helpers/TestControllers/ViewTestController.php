<?php

namespace BlueMvc\Core\Tests\Helpers\TestControllers;

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

    /**
     * Action with view data.
     *
     * @return View The view.
     */
    public function withviewdataAction()
    {
        $this->setViewItem('Foo', 'This is the view data.');

        return new View('This is the model.');
    }

    /**
     * Action with no corresponding view file.
     *
     * @return View The view.
     */
    public function withnoviewfileAction()
    {
        return new View();
    }

    /**
     * Action with custom view file.
     *
     * @return View The view.
     */
    public function withcustomviewfileAction()
    {
        return new View('This is the model.', 'custom');
    }
}
