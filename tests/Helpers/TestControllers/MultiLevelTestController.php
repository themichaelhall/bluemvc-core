<?php

declare(strict_types=1);

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
    public function noparamsAction(): string
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
    public function fooAction($foo): string
    {
        return 'FooAction: Foo=[' . $foo . ']';
    }

    /**
     * Action with one optional parameter.
     *
     * @param mixed $foo The parameter.
     *
     * @return string The result.
     */
    public function foonullAction($foo = null): string
    {
        return 'FooNullAction: Foo=[' . ($foo !== null ? $foo : '*null*') . ']';
    }

    /**
     * Action with two required parameters.
     *
     * @param mixed $foo The first parameter.
     * @param mixed $bar The second parameter.
     *
     * @return string The result.
     */
    public function foobarAction($foo, $bar): string
    {
        return 'FooBarAction: Foo=[' . $foo . '], Bar=[' . $bar . ']';
    }

    /**
     * Action with one required and one optional parameter.
     *
     * @param mixed $foo The first parameter.
     * @param mixed $bar The second parameter.
     *
     * @return string The result.
     */
    public function foobarnullAction($foo, $bar = null): string
    {
        return 'FooBarNullAction: Foo=[' . $foo . '], Bar=[' . ($bar !== null ? $bar : '*null*') . ']';
    }

    /**
     * Action with one required and two optional parameters.
     *
     * @param mixed $foo The first parameter.
     * @param mixed $bar The second parameter.
     * @param mixed $baz The third parameter.
     *
     * @return string The result.
     */
    public function foobarnullbaznullAction($foo, $bar = null, $baz = null): string
    {
        return 'FooBarNullBazNullAction: Foo=[' . $foo . '], Bar=[' . ($bar !== null ? $bar : '*null*') . '], Baz=[' . ($baz !== null ? $baz : '*null*') . ']';
    }

    /**
     * Action with two optional parameters.
     *
     * @param mixed $foo The first parameter.
     * @param mixed $bar The second parameter.
     *
     * @return string The result.
     */
    public function foonullbarnullAction($foo = null, $bar = null): string
    {
        return 'FooNullBarNullAction: Foo=[' . ($foo !== null ? $foo : '*null*') . '], Bar=[' . ($bar !== null ? $bar : '*null*') . ']';
    }

    /**
     * Action with three required parameters.
     *
     * @param mixed $foo The first parameter.
     * @param mixed $bar The second parameter.
     * @param mixed $baz The third parameter.
     *
     * @return string The result.
     */
    public function foobarbazAction($foo, $bar, $baz): string
    {
        return 'FooBarBazAction: Foo=[' . $foo . '], Bar=[' . $bar . '], Baz=[' . $baz . ']';
    }

    /**
     * Action with two required parameters and one optional string default parameter.
     *
     * @param mixed $foo The first parameter.
     * @param mixed $bar The second parameter.
     * @param mixed $baz The third parameter.
     *
     * @return string The result.
     */
    public function foobarbazstringAction($foo, $bar, $baz = 'default string'): string
    {
        return 'FooBarBazStringAction: Foo=[' . $foo . '], Bar=[' . $bar . '], Baz=[' . $baz . ']';
    }

    /**
     * Action with three optional parameters.
     *
     * @param mixed $foo The first parameter.
     * @param mixed $bar The second parameter.
     * @param mixed $baz The third parameter.
     *
     * @return string The result.
     */
    public function foonullbarnullbaznullAction($foo = null, $bar = null, $baz = null): string
    {
        return 'FooNullBarNullBazNullAction: Foo=[' . ($foo !== null ? $foo : '*null*') . '], Bar=[' . ($bar !== null ? $bar : '*null*') . '], Baz=[' . ($baz !== null ? $baz : '*null*') . ']';
    }

    /**
     * The default action.
     *
     * @param mixed $foo The first parameter.
     * @param mixed $bar The second parameter.
     * @param mixed $baz The third parameter.
     *
     * @return string The result.
     */
    public function defaultAction($foo, $bar, $baz = null): string
    {
        return 'DefaultAction: Foo=[' . $foo . '], Bar=[' . $bar . '], Baz=[' . ($baz !== null ? $baz : '*null*') . ']';
    }
}
