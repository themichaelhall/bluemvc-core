<?php

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
        return "Foo Action";
    }

    /**
     * Default action.
     *
     * @return string The result.
     */
    public function defaultAction()
    {
        return "Default Action";
    }
}
