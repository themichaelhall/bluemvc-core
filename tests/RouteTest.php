<?php

namespace BlueMvc\Core\Tests;

use BlueMvc\Core\Request;
use BlueMvc\Core\Route;
use BlueMvc\Core\Tests\Helpers\TestControllers\BasicTestController;

/**
 * Test Route class.
 */
class RouteTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test getPath method.
     */
    public function testGetPath()
    {
        self::assertSame('', (new Route('', BasicTestController::class))->getPath());
        self::assertSame('foo', (new Route('foo', BasicTestController::class))->getPath());
        self::assertSame('foo-bar_baz.1', (new Route('foo-bar_baz.1', BasicTestController::class))->getPath());
    }

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
     * Test url matches for empty path.
     */
    public function testUrlMatchesForEmptyPath()
    {
        $route = new Route('', BasicTestController::class);

        self::assertNotNull($route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/', 'REQUEST_METHOD' => 'GET'])));
        self::assertNotNull($route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo', 'REQUEST_METHOD' => 'GET'])));
        self::assertNull($route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo/', 'REQUEST_METHOD' => 'GET'])));
        self::assertNull($route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo/bar', 'REQUEST_METHOD' => 'GET'])));
        self::assertNull($route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo/bar/', 'REQUEST_METHOD' => 'GET'])));
    }

    /**
     * Test url matches for non-empty path.
     */
    public function testUrlMatchesForNonEmptyPath()
    {
        $route = new Route('foo', BasicTestController::class);

        self::assertNull($route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/', 'REQUEST_METHOD' => 'GET'])));
        self::assertNull($route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo', 'REQUEST_METHOD' => 'GET'])));
        self::assertNotNull($route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo/', 'REQUEST_METHOD' => 'GET'])));
        self::assertNotNull($route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo/bar', 'REQUEST_METHOD' => 'GET'])));
        self::assertNotNull($route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo/bar/', 'REQUEST_METHOD' => 'GET'])));
    }

    /**
     * Test url matches for non-matching path.
     */
    public function testUrlMatchesForNonMatchingPath()
    {
        $route = new Route('bar', BasicTestController::class);

        self::assertNull($route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/', 'REQUEST_METHOD' => 'GET'])));
        self::assertNull($route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo', 'REQUEST_METHOD' => 'GET'])));
        self::assertNull($route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo/', 'REQUEST_METHOD' => 'GET'])));
        self::assertNull($route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo/bar', 'REQUEST_METHOD' => 'GET'])));
        self::assertNull($route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo/bar/', 'REQUEST_METHOD' => 'GET'])));
    }

    /**
     * Test route match result for index page on index controller.
     */
    public function testRouteMatchResultForIndexPageOnIndexController()
    {
        $route = new Route('', BasicTestController::class);
        $routeMatch = $route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/', 'REQUEST_METHOD' => 'GET']));

        self::assertSame(BasicTestController::class, $routeMatch->getControllerClassName());
        self::assertSame('', $routeMatch->getAction());
        self::assertSame([], $routeMatch->getParameters());
    }

    /**
     * Test route match result for root page on index controller.
     */
    public function testRouteMatchResultForRootPageOnIndexController()
    {
        $route = new Route('', BasicTestController::class);
        $routeMatch = $route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo', 'REQUEST_METHOD' => 'GET']));

        self::assertSame(BasicTestController::class, $routeMatch->getControllerClassName());
        self::assertSame('foo', $routeMatch->getAction());
        self::assertSame([], $routeMatch->getParameters());
    }

    /**
     * Test route match result for first level index on path controller.
     */
    public function testRouteMatchResultForFirstLevelIndexOnPathController()
    {
        $route = new Route('foo', BasicTestController::class);
        $routeMatch = $route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo/', 'REQUEST_METHOD' => 'GET']));

        self::assertSame(BasicTestController::class, $routeMatch->getControllerClassName());
        self::assertSame('', $routeMatch->getAction());
        self::assertSame([], $routeMatch->getParameters());
    }

    /**
     * Test route match result for first level page on path controller.
     */
    public function testRouteMatchResultForFirstLevelPageOnPathController()
    {
        $route = new Route('foo', BasicTestController::class);
        $routeMatch = $route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo/bar', 'REQUEST_METHOD' => 'GET']));

        self::assertSame(BasicTestController::class, $routeMatch->getControllerClassName());
        self::assertSame('bar', $routeMatch->getAction());
        self::assertSame([], $routeMatch->getParameters());
    }

    /**
     * Test route match result for second level index on path controller.
     */
    public function testRouteMatchResultForSecondLevelIndexOnPathController()
    {
        $route = new Route('foo', BasicTestController::class);
        $routeMatch = $route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo/bar/', 'REQUEST_METHOD' => 'GET']));

        self::assertSame(BasicTestController::class, $routeMatch->getControllerClassName());
        self::assertSame('bar', $routeMatch->getAction());
        self::assertSame([''], $routeMatch->getParameters());
    }

    /**
     * Test route match result for second level page on path controller.
     */
    public function testRouteMatchResultForSecondLevelPageOnPathController()
    {
        $route = new Route('foo', BasicTestController::class);
        $routeMatch = $route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo/bar/baz', 'REQUEST_METHOD' => 'GET']));

        self::assertSame(BasicTestController::class, $routeMatch->getControllerClassName());
        self::assertSame('bar', $routeMatch->getAction());
        self::assertSame(['baz'], $routeMatch->getParameters());
    }

    /**
     * Test route match result for third level index on path controller.
     */
    public function testRouteMatchResultForThirdLevelIndexOnPathController()
    {
        $route = new Route('foo', BasicTestController::class);
        $routeMatch = $route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo/bar/baz/', 'REQUEST_METHOD' => 'GET']));

        self::assertSame(BasicTestController::class, $routeMatch->getControllerClassName());
        self::assertSame('bar', $routeMatch->getAction());
        self::assertSame(['baz', ''], $routeMatch->getParameters());
    }
}
