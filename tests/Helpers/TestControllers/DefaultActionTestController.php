<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Helpers\TestControllers;

use BlueMvc\Core\Controller;

/**
 * Default action test controller class.
 */
class DefaultActionTestController extends Controller
{
    /**
     * Foo action.
     *
     * @return string The result
     */
    public function fooAction()
    {
        return 'Foo Action';
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
        return 'Default Action ' . $action;
    }
}
