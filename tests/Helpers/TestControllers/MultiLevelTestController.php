<?php

namespace BlueMvc\Core\Tests\Helpers\TestControllers;

use BlueMvc\Core\Controller;

/**
 * Test controller for multi level paths.
 */
class MultiLevelTestController extends Controller
{
    /**
     * No parameters action.
     *
     * @return string The result.
     */
    public function noparamsAction()
    {
        return 'No Parameters';
    }

    /**
     * Action with one required parameter.
     *
     * @param mixed $foo The parameter.
     *
     * @return string The result.
     */
    public function fooAction($foo)
    {
        return 'FooAction: Foo=[' . $foo . ']';
    }

    /**
     * Action with two required parameters.
     *
     * @param mixed $foo The first parameter.
     * @param mixed $bar The second parameter.
     *
     * @return string The result.
     */
    public function foobarAction($foo, $bar)
    {
        return 'FooBarAction: Foo=[' . $foo . '], Bar=[' . $bar . ']';
    }

    /**
     * Action with two required parameters.
     *
     * @param mixed $foo The first parameter.
     * @param mixed $bar The second parameter.
     * @param mixed $baz The third parameter.
     *
     * @return string The result.
     */
    public function foobarbazAction($foo, $bar, $baz)
    {
        return 'FooBarBazAction: Foo=[' . $foo . '], Bar=[' . $bar . '], Baz=[' . $baz . ']';
    }
}
