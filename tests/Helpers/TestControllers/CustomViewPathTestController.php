<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Helpers\TestControllers;

use BlueMvc\Core\Controller;
use BlueMvc\Core\View;

/**
 * Custom view path test controller.
 */
class CustomViewPathTestController extends Controller
{
    /**
     * Index action.
     *
     * @return View The result.
     */
    public function indexAction()
    {
        return new View();
    }

    /** @noinspection PhpMissingParentCallCommonInspection */

    /**
     * Returns the view path.
     *
     * @return string The view path.
     */
    protected function getViewPath(): string
    {
        return 'ViewTest/CustomViewPath';
    }
}
