<?php

declare(strict_types=1);

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
    public function indexAction(): View
    {
        return new View();
    }

    /**
     * Alternate view type action.
     *
     * @return View The view;
     */
    public function alternateAction(): View
    {
        $this->setViewItem('Foo', 'Bar');

        return new View('This is the model.');
    }

    /**
     * Action with only a second choice view type.
     *
     * @return View The view.
     */
    public function onlyjsonAction(): View
    {
        return new View('This is the model.');
    }

    /**
     * Action with model.
     *
     * @return View The view.
     */
    public function withmodelAction(): View
    {
        return new View('This is the model.');
    }

    /**
     * Action with view data.
     *
     * @return View The view.
     */
    public function withviewdataAction(): View
    {
        $this->setViewItem('Foo', 'This is the view data.');

        return new View('This is the model.');
    }

    /**
     * Action with no corresponding view file.
     *
     * @return View The view.
     */
    public function withnoviewfileAction(): View
    {
        return new View();
    }

    /**
     * Action with custom view file.
     *
     * @return View The view.
     */
    public function withcustomviewfileAction(): View
    {
        return new View('This is the model.', 'custom');
    }
}
