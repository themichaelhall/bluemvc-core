<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Helpers\TestControllers;

use BlueMvc\Core\Controller;
use stdClass;

/**
 * Type hint action parameters test controller.
 */
class TypeHintActionParametersTestController extends Controller
{
    /**
     * No types action.
     *
     * @param mixed $foo    The first parameter.
     * @param mixed $bar    The second parameter.
     * @param mixed $baz    The third parameter.
     * @param mixed $fooBar The fourth parameter.
     *
     * @return string
     *
     * @noinspection PhpMissingParamTypeInspection
     */
    public function noTypesAction($foo, $bar, $baz = null, $fooBar = 1234): string
    {
        return 'NoTypesAction: Foo=[' . gettype($foo) . ':' . $foo . '], Bar=[' . gettype($bar) . ':' . $bar . '], Baz=[' . gettype($baz) . ':' . $baz . '], FooBar=[' . gettype($fooBar) . ':' . $fooBar . ']';
    }

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
     * Boolean types action.
     *
     * @param bool      $foo    The first parameter.
     * @param bool|null $bar    The second parameter.
     * @param bool|null $baz    The third parameter.
     * @param bool      $fooBar The fourth parameter.
     *
     * @return string The result.
     */
    public function boolTypesAction(bool $foo, ?bool $bar, ?bool $baz = null, bool $fooBar = true): string
    {
        return 'BoolTypesAction: Foo=[' . gettype($foo) . ':' . $foo . '], Bar=[' . gettype($bar) . ':' . $bar . '], Baz=[' . gettype($baz) . ':' . $baz . '], FooBar=[' . gettype($fooBar) . ':' . $fooBar . ']';
    }

    /**
     * Array types action.
     *
     * @param array $foo The array parameter.
     *
     * @return string The result.
     */
    public function arrayTypesAction(array $foo): string
    {
        return 'ArrayTypesAction: Foo=[' . gettype($foo) . ':' . implode(',', $foo) . ']';
    }

    /**
     * Object types action.
     *
     * @param stdClass $foo The object parameter.
     *
     * @return string The result.
     */
    public function objectTypesAction(stdClass $foo): string
    {
        return 'ObjectTypesAction: Foo=[' . gettype($foo) . ':' . print_r($foo, true) . ']';
    }

    /**
     * Mixed types action.
     *
     * @param mixed $foo    The first parameter.
     * @param mixed $bar    The second parameter.
     * @param mixed $fooBar The third parameter.
     *
     * @return string The result.
     */
    public function mixedTypesAction(mixed $foo, mixed $bar = false, mixed $fooBar = 2.5): string
    {
        return 'MixedTypesAction: Foo=[' . gettype($foo) . ':' . $foo . '], Bar=[' . gettype($bar) . ':' . $bar . '], FooBar=[' . gettype($fooBar) . ':' . $fooBar . ']';
    }

    /**
     * Blended types action.
     *
     * @param float  $typeFloat  The float parameter.
     * @param mixed  $typeNon    The non-typed parameter.
     * @param string $typeString The string parameter.
     * @param bool   $typeBool   The boolean parameter.
     *
     * @return string The result.
     *
     * @noinspection PhpMissingParamTypeInspection
     */
    public function blendedTypesAction(float $typeFloat, $typeNon, string $typeString, bool $typeBool = false): string
    {
        return 'BlendedTypesAction: TypeFloat=[' . gettype($typeFloat) . ':' . $typeFloat . '], TypeNon=[' . gettype($typeNon) . ':' . $typeNon . '], TypeString=[' . gettype($typeString) . ':' . $typeString . '], TypeBool=[' . gettype($typeBool) . ':' . $typeBool . ']';
    }

    /**
     * The default action.
     *
     * @param string $action The action.
     * @param int    $foo    The first parameter.
     *
     * @return string The result.
     */
    public function defaultAction(string $action, int $foo = -1): string
    {
        return 'DefaultAction: Action=[' . gettype($action) . ':' . $action . '], Foo=[' . gettype($foo) . ':' . $foo . ']';
    }
}
