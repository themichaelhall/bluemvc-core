<?php

namespace BlueMvc\Core\Tests;

use BlueMvc\Core\DefaultRoute;
use BlueMvc\Core\Http\Method;
use BlueMvc\Core\Tests\Helpers\TestControllers\BasicTestController;
use BlueMvc\Core\Tests\Helpers\TestRequests\BasicTestRequest;
use DataTypes\Url;

/**
 * Test DefaultRoute class.
 */
class DefaultRouteTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test getControllerClassName method.
     */
    public function testGetControllerClassName()
    {
        $route = new DefaultRoute(BasicTestController::class);
        self::assertSame(BasicTestController::class, $route->getControllerClassName());
    }

    /**
     * Test that setting an undefined controller class name throws exception.
     *
     * @expectedException \BlueMvc\Core\Exceptions\InvalidControllerClassException
     * @expectedExceptionMessage "BlueMvc\Core\FooBar" is not a valid controller class.
     */
    public function testSetUndefinedControllerClassThrowsException()
    {
        new DefaultRoute('BlueMvc\\Core\\FooBar');
    }

    /**
     * Test that setting an invalid controller class name throws exception.
     *
     * @expectedException \BlueMvc\Core\Exceptions\InvalidControllerClassException
     * @expectedExceptionMessage "BlueMvc\Core\Request" is not a valid controller class.
     */
    public function testSetInvalidControllerClassThrowsException()
    {
        new DefaultRoute('BlueMvc\\Core\\Request');
    }

    /**
     * Test that all url matches.
     */
    public function testAllUrlMatches()
    {
        $route = new DefaultRoute(BasicTestController::class);

        self::assertNotNull($route->matches(new BasicTestRequest(Url::parse('https://www.example.com/'), new Method('GET'))));
        self::assertNotNull($route->matches(new BasicTestRequest(Url::parse('https://www.example.com/foo'), new Method('GET'))));
        self::assertNotNull($route->matches(new BasicTestRequest(Url::parse('https://www.example.com/foo/'), new Method('GET'))));
        self::assertNotNull($route->matches(new BasicTestRequest(Url::parse('https://www.example.com/foo/bar'), new Method('GET'))));
        self::assertNotNull($route->matches(new BasicTestRequest(Url::parse('https://www.example.com/foo/bar/'), new Method('GET'))));
    }

    /**
     * Test route match result for index page.
     */
    public function testRouteMatchResultForIndexPage()
    {
        $route = new DefaultRoute(BasicTestController::class);
        $routeMatch = $route->matches(new BasicTestRequest(Url::parse('https://www.example.com/'), new Method('GET')));

        self::assertSame(BasicTestController::class, $routeMatch->getControllerClassName());
        self::assertSame('', $routeMatch->getAction());
        self::assertSame([], $routeMatch->getParameters());
    }

    /**
     * Test route match result for root page.
     */
    public function testRouteMatchResultForRootPage()
    {
        $route = new DefaultRoute(BasicTestController::class);
        $routeMatch = $route->matches(new BasicTestRequest(Url::parse('https://www.example.com/foo'), new Method('GET')));

        self::assertSame(BasicTestController::class, $routeMatch->getControllerClassName());
        self::assertSame('foo', $routeMatch->getAction());
        self::assertSame([], $routeMatch->getParameters());
    }

    /**
     * Test route match result for first level index.
     */
    public function testRouteMatchResultForFirstLevelIndex()
    {
        $route = new DefaultRoute(BasicTestController::class);
        $routeMatch = $route->matches(new BasicTestRequest(Url::parse('https://www.example.com/foo/'), new Method('GET')));

        self::assertSame(BasicTestController::class, $routeMatch->getControllerClassName());
        self::assertSame('foo', $routeMatch->getAction());
        self::assertSame([''], $routeMatch->getParameters());
    }

    /**
     * Test route match result for first level page.
     */
    public function testRouteMatchResultForFirstLevelPage()
    {
        $route = new DefaultRoute(BasicTestController::class);
        $routeMatch = $route->matches(new BasicTestRequest(Url::parse('https://www.example.com/foo/bar'), new Method('GET')));

        self::assertSame(BasicTestController::class, $routeMatch->getControllerClassName());
        self::assertSame('foo', $routeMatch->getAction());
        self::assertSame(['bar'], $routeMatch->getParameters());
    }

    /**
     * Test route match result for second level index.
     */
    public function testRouteMatchResultForSecondLevelIndexOnPathController()
    {
        $route = new DefaultRoute(BasicTestController::class);
        $routeMatch = $route->matches(new BasicTestRequest(Url::parse('https://www.example.com/foo/bar/'), new Method('GET')));

        self::assertSame(BasicTestController::class, $routeMatch->getControllerClassName());
        self::assertSame('foo', $routeMatch->getAction());
        self::assertSame(['bar', ''], $routeMatch->getParameters());
    }

    /**
     * Test route match result for second level page.
     */
    public function testRouteMatchResultForSecondLevelPage()
    {
        $route = new DefaultRoute(BasicTestController::class);
        $routeMatch = $route->matches(new BasicTestRequest(Url::parse('https://www.example.com/foo/bar/baz'), new Method('GET')));

        self::assertSame(BasicTestController::class, $routeMatch->getControllerClassName());
        self::assertSame('foo', $routeMatch->getAction());
        self::assertSame(['bar', 'baz'], $routeMatch->getParameters());
    }

    /**
     * Test route match result for third level index.
     */
    public function testRouteMatchResultForThirdLevelIndex()
    {
        $route = new DefaultRoute(BasicTestController::class);
        $routeMatch = $route->matches(new BasicTestRequest(Url::parse('https://www.example.com/foo/bar/baz/'), new Method('GET')));

        self::assertSame(BasicTestController::class, $routeMatch->getControllerClassName());
        self::assertSame('foo', $routeMatch->getAction());
        self::assertSame(['bar', 'baz', ''], $routeMatch->getParameters());
    }

    /**
     * Test create route with invalid controller class name parameter type.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $controllerClassName parameter is not a string.
     */
    public function testCreateWithInvalidControllerClassNameParameterType()
    {
        new DefaultRoute(10);
    }
}
