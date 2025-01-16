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
     * @param string $foo The parameter.
     *
     * @return string The result.
     */
    public function fooAction(string $foo): string
    {
        return 'FooAction: Foo=[' . $foo . ']';
    }

    /**
     * Action with one optional parameter.
     *
     * @param string|null $foo The parameter.
     *
     * @return string The result.
     */
    public function foonullAction(?string $foo = null): string
    {
        return 'FooNullAction: Foo=[' . ($foo !== null ? $foo : '*null*') . ']';
    }

    /**
     * Action with two required parameters.
     *
     * @param string $foo The first parameter.
     * @param string $bar The second parameter.
     *
     * @return string The result.
     */
    public function foobarAction(string $foo, string $bar): string
    {
        return 'FooBarAction: Foo=[' . $foo . '], Bar=[' . $bar . ']';
    }

    /**
     * Action with one required and one optional parameter.
     *
     * @param string      $foo The first parameter.
     * @param string|null $bar The second parameter.
     *
     * @return string The result.
     */
    public function foobarnullAction(string $foo, ?string $bar = null): string
    {
        return 'FooBarNullAction: Foo=[' . $foo . '], Bar=[' . ($bar !== null ? $bar : '*null*') . ']';
    }

    /**
     * Action with one required and two optional parameters.
     *
     * @param string      $foo The first parameter.
     * @param string|null $bar The second parameter.
     * @param string|null $baz The third parameter.
     *
     * @return string The result.
     */
    public function foobarnullbaznullAction(string $foo, ?string $bar = null, ?string $baz = null): string
    {
        return 'FooBarNullBazNullAction: Foo=[' . $foo . '], Bar=[' . ($bar !== null ? $bar : '*null*') . '], Baz=[' . ($baz !== null ? $baz : '*null*') . ']';
    }

    /**
     * Action with two optional parameters.
     *
     * @param string|null $foo The first parameter.
     * @param string|null $bar The second parameter.
     *
     * @return string The result.
     */
    public function foonullbarnullAction(?string $foo = null, ?string $bar = null): string
    {
        return 'FooNullBarNullAction: Foo=[' . ($foo !== null ? $foo : '*null*') . '], Bar=[' . ($bar !== null ? $bar : '*null*') . ']';
    }

    /**
     * Action with three required parameters.
     *
     * @param string $foo The first parameter.
     * @param string $bar The second parameter.
     * @param string $baz The third parameter.
     *
     * @return string The result.
     */
    public function foobarbazAction(string $foo, string $bar, string $baz): string
    {
        return 'FooBarBazAction: Foo=[' . $foo . '], Bar=[' . $bar . '], Baz=[' . $baz . ']';
    }

    /**
     * Action with two required parameters and one optional string default parameter.
     *
     * @param string $foo The first parameter.
     * @param string $bar The second parameter.
     * @param string $baz The third parameter.
     *
     * @return string The result.
     */
    public function foobarbazstringAction(string $foo, string $bar, string $baz = 'default string'): string
    {
        return 'FooBarBazStringAction: Foo=[' . $foo . '], Bar=[' . $bar . '], Baz=[' . $baz . ']';
    }

    /**
     * Action with three optional parameters.
     *
     * @param string|null $foo The first parameter.
     * @param string|null $bar The second parameter.
     * @param string|null $baz The third parameter.
     *
     * @return string The result.
     */
    public function foonullbarnullbaznullAction(?string $foo = null, ?string $bar = null, ?string $baz = null): string
    {
        return 'FooNullBarNullBazNullAction: Foo=[' . ($foo !== null ? $foo : '*null*') . '], Bar=[' . ($bar !== null ? $bar : '*null*') . '], Baz=[' . ($baz !== null ? $baz : '*null*') . ']';
    }

    /**
     * The default action.
     *
     * @param string      $foo The first parameter.
     * @param string      $bar The second parameter.
     * @param string|null $baz The third parameter.
     *
     * @return string The result.
     */
    public function defaultAction(string $foo, string $bar, ?string $baz = null): string
    {
        return 'DefaultAction: Foo=[' . $foo . '], Bar=[' . $bar . '], Baz=[' . ($baz !== null ? $baz : '*null*') . ']';
    }
}
