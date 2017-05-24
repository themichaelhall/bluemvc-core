<?php

namespace BlueMvc\Core\Tests;

use BlueMvc\Core\DefaultRoute;
use BlueMvc\Core\Request;
use BlueMvc\Core\Tests\Helpers\TestControllers\BasicTestController;

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
     * Test that all url matches.
     */
    public function testAllUrlMatches()
    {
        $route = new DefaultRoute(BasicTestController::class);

        self::assertNotNull($route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/', 'REQUEST_METHOD' => 'GET'])));
        self::assertNotNull($route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo', 'REQUEST_METHOD' => 'GET'])));
        self::assertNotNull($route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo/', 'REQUEST_METHOD' => 'GET'])));
        self::assertNotNull($route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo/bar', 'REQUEST_METHOD' => 'GET'])));
        self::assertNotNull($route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo/bar/', 'REQUEST_METHOD' => 'GET'])));
    }

    /**
     * Test route match result for index page.
     */
    public function testRouteMatchResultForIndexPage()
    {
        $route = new DefaultRoute(BasicTestController::class);
        $routeMatch = $route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/', 'REQUEST_METHOD' => 'GET']));

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
        $routeMatch = $route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo', 'REQUEST_METHOD' => 'GET']));

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
        $routeMatch = $route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo/', 'REQUEST_METHOD' => 'GET']));

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
        $routeMatch = $route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo/bar', 'REQUEST_METHOD' => 'GET']));

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
        $routeMatch = $route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo/bar/', 'REQUEST_METHOD' => 'GET']));

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
        $routeMatch = $route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo/bar/baz', 'REQUEST_METHOD' => 'GET']));

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
        $routeMatch = $route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo/bar/baz/', 'REQUEST_METHOD' => 'GET']));

        self::assertSame(BasicTestController::class, $routeMatch->getControllerClassName());
        self::assertSame('foo', $routeMatch->getAction());
        self::assertSame(['bar', 'baz', ''], $routeMatch->getParameters());
    }
}
