<?php

namespace BlueMvc\Core\Tests\Helpers\TestControllers;

use BlueMvc\Core\Controller;

/**
 * Uppercase action test controller.
 */
class UppercaseActionTestController extends Controller
{
    /**
     * Index action.
     *
     * @return string The result.
     */
    public function INDEXACTION()
    {
        return 'INDEX action';
    }

    /**
     * Foo action.
     *
     * @return string The result.
     */
    public function FOOACTION()
    {
        return 'FOO action';
    }

    /**
     * Default action.
     *
     * @param string $action The action.
     *
     * @return string The result.
     */
    public function DEFAULTACTION($action)
    {
        return 'DEFAULT action "' . $action . '"';
    }
}
