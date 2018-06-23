<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Helpers\TestControllers;

use BlueMvc\Core\Controller;

/**
 * Type hint action parameters test controller.
 */
class TypeHintActionParametersTestController extends Controller
{
    /**
     * String types action.
     *
     * @param string      $foo    The first parameter.
     * @param null|string $bar    The second parameter.
     * @param null|string $baz    The third parameter.
     * @param string      $fooBar The fourth parameter.
     *
     * @return string The result.
     */
    public function stringTypesAction(string $foo, ?string $bar, ?string $baz = null, string $fooBar = 'Foo Bar'): string
    {
        return 'StringTypesAction: Foo=[' . gettype($foo) . ':' . $foo . '], Bar=[' . gettype($bar) . ':' . $bar . '], Baz=[' . gettype($baz) . ':' . $baz . '], FooBar=[' . gettype($fooBar) . ':' . $fooBar . ']';
    }

    /**
     * Integer types action.
     *
     * @param int      $foo    The first parameter.
     * @param int|null $bar    The second parameter.
     * @param int|null $baz    The third parameter.
     * @param int      $fooBar The fourth parameter.
     *
     * @return string The result.
     */
    public function intTypesAction(int $foo, ?int $bar, ?int $baz = null, int $fooBar = 42): string
    {
        return 'IntTypesAction: Foo=[' . gettype($foo) . ':' . $foo . '], Bar=[' . gettype($bar) . ':' . $bar . '], Baz=[' . gettype($baz) . ':' . $baz . '], FooBar=[' . gettype($fooBar) . ':' . $fooBar . ']';
    }

    /**
     * Float types action.
     *
     * @param float      $foo    The first parameter.
     * @param float|null $bar    The second parameter.
     * @param float|null $baz    The third parameter.
     * @param float      $fooBar The fourth parameter.
     *
     * @return string The result.
     */
    public function floatTypesAction(float $foo, ?float $bar, ?float $baz = null, float $fooBar = 0.5): string
    {
        return 'FloatTypesAction: Foo=[' . gettype($foo) . ':' . $foo . '], Bar=[' . gettype($bar) . ':' . $bar . '], Baz=[' . gettype($baz) . ':' . $baz . '], FooBar=[' . gettype($fooBar) . ':' . $fooBar . ']';
    }

    /**
     * Mixed types action.
     *
     * @param float  $typeFloat  The float parameter.
     * @param int    $typeInt    The integer parameter.
     * @param string $typeString The string parameter.
     *
     * @return string The result.
     */
    public function mixedTypesAction(float $typeFloat, int $typeInt, string $typeString): string
    {
        return 'MixedTypesAction: TypeFloat=[' . gettype($typeFloat) . ':' . $typeFloat . '], TypeInt=[' . gettype($typeInt) . ':' . $typeInt . '], TypeString=[' . gettype($typeString) . ':' . $typeString . ']';
    }

    /**
     * The default action.
     *
     * @param string $action The action.
     * @param string $foo    The first parameter.
     *
     * @return string The result.
     */
    public function defaultAction(string $action, string $foo): string
    {
        return 'DefaultAction: Action=[' . gettype($action) . ':' . $action . '], Foo=[' . gettype($foo) . ':' . $foo . ']';
    }
}
