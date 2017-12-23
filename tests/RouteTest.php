<?php

namespace BlueMvc\Core\Tests;

use BlueMvc\Core\Http\Method;
use BlueMvc\Core\Route;
use BlueMvc\Core\Tests\Helpers\TestControllers\BasicTestController;
use BlueMvc\Core\Tests\Helpers\TestRequests\BasicTestRequest;
use DataTypes\Url;

/**
 * Test Route class.
 */
class RouteTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that invalid path is invalid.
     *
     * @expectedException \BlueMvc\Core\Exceptions\InvalidRoutePathException
     * @expectedExceptionMessage Path "Foo/Bar" contains invalid character "/".
     */
    public function testInvalidPathIsInvalid()
    {
        new Route('Foo/Bar', BasicTestController::class);
    }

    /**
     * Test that setting an undefined controller class name throws exception.
     *
     * @expectedException \BlueMvc\Core\Exceptions\InvalidControllerClassException
     * @expectedExceptionMessage "BlueMvc\Core\FooBar" is not a valid controller class.
     */
    public function testSetUndefinedControllerClassThrowsException()
    {
        new Route('', 'BlueMvc\\Core\\FooBar');
    }

    /**
     * Test that setting an invalid controller class name throws exception.
     *
     * @expectedException \BlueMvc\Core\Exceptions\InvalidControllerClassException
     * @expectedExceptionMessage "BlueMvc\Core\Request" is not a valid controller class.
     */
    public function testSetInvalidControllerClassThrowsException()
    {
        new Route('', 'BlueMvc\\Core\\Request');
    }

    /**
     * Test getControllerClassName method.
     */
    public function testGetControllerClassName()
    {
        $route = new Route('', BasicTestController::class);
        self::assertSame(BasicTestController::class, $route->getControllerClassName());
    }

    /**
     * @dataProvider matchesDataProvider
     *
     * Test matches method.
     *
     * @param string      $path               The route path.
     * @param string      $urlPath            The url path.
     * @param bool        $expectedMatch      True if expected match, false otherwise.
     * @param string|null $expectedAction     The expected action or null if no match.
     * @param array|null  $expectedParameters The expected parameters or null if no match.
     */
    public function testMatches($path, $urlPath, $expectedMatch, $expectedAction = null, array $expectedParameters = null)
    {
        $route = new Route($path, BasicTestController::class);
        $routeMatch = $route->matches(new BasicTestRequest(Url::parse('https://example.com' . $urlPath), new Method('GET')));

        self::assertSame($expectedMatch, $routeMatch !== null);
        self::assertSame($expectedAction, $routeMatch !== null ? $routeMatch->getAction() : null);
        self::assertSame($expectedParameters, $routeMatch !== null ? $routeMatch->getParameters() : null);
    }

    /**
     * Data provider for matches test.
     *
     * @return array The data.
     */
    public function matchesDataProvider()
    {
        return [
            ['', '/', true, '', []],
            ['', '/foo', true, 'foo', []],
            ['', '/foo/', false],
            ['', '/foo/bar', false],
            ['', '/foo/bar/', false],
            ['', '/foo/bar/baz', false],
            ['', '/foo/bar/baz/', false],
            ['foo', '/', false],
            ['foo', '/foo', false],
            ['foo', '/foo/', true, '', []],
            ['foo', '/foo/bar', true, 'bar', []],
            ['foo', '/foo/bar/', true, 'bar', ['']],
            ['foo', '/foo/bar/baz', true, 'bar', ['baz']],
            ['foo', '/foo/bar/baz/', true, 'bar', ['baz', '']],
            ['bar', '/', false],
            ['bar', '/foo', false],
            ['bar', '/foo/', false],
            ['bar', '/foo/bar', false],
            ['bar', '/foo/bar/', false],
            ['bar', '/foo/bar/baz', false],
            ['bar', '/foo/bar/baz/', false],
        ];
    }

    /**
     * Test create route with invalid path parameter type.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $path parameter is not a string.
     */
    public function testCreateWithInvalidPathParameterType()
    {
        new Route(null, BasicTestController::class);
    }

    /**
     * Test create route with invalid controller class name parameter type.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $controllerClassName parameter is not a string.
     */
    public function testCreateWithInvalidControllerClassNameParameterType()
    {
        new Route('', true);
    }
}
