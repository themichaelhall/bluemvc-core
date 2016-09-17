<?php

use BlueMvc\Core\DefaultRoute;
use BlueMvc\Core\Request;

require_once __DIR__ . '/Helpers/TestControllers/BasicTestController.php';

/**
 * Test DefaultRoute class.
 */
class DefaultRouteTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test getControllerClassName method.
     */
    public function testGetControllerClassName()
    {
        $route = new DefaultRoute(BasicTestController::class);
        $this->assertSame(BasicTestController::class, $route->getControllerClassName());
    }

    /**
     * Test that non existing controller class name is invalid.
     *
     * @expectedException BlueMvc\Core\Exceptions\InvalidControllerClassException
     * @expectedExceptionMessage Controller class "NonExistingClassName" does not exist.
     */
    public function testNonExistingControllerClassNameIsInvalid()
    {
        $route = new DefaultRoute('NonExistingClassName');
        $route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/', 'REQUEST_METHOD' => 'GET']));
    }

    /**
     * Test that class not implementing ControllerInterface is invalid.
     *
     * @expectedException BlueMvc\Core\Exceptions\InvalidControllerClassException
     * @expectedExceptionMessage Controller class "DefaultRouteTest" does not implement "BlueMvc\Core\Interfaces\ControllerInterface".
     */
    public function testControllerClassNotImplementingControllerInterfaceIsInvalid()
    {
        $route = new DefaultRoute(self::class);
        $route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/', 'REQUEST_METHOD' => 'GET']));
    }

    /**
     * Test that all url matches.
     */
    public function testAllUrlMatches()
    {
        $route = new DefaultRoute(BasicTestController::class);

        $this->assertNotNull($route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/', 'REQUEST_METHOD' => 'GET'])));
        $this->assertNotNull($route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo', 'REQUEST_METHOD' => 'GET'])));
        $this->assertNotNull($route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo/', 'REQUEST_METHOD' => 'GET'])));
        $this->assertNotNull($route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo/bar', 'REQUEST_METHOD' => 'GET'])));
        $this->assertNotNull($route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo/bar/', 'REQUEST_METHOD' => 'GET'])));
    }

    /**
     * Test route match result for index page.
     */
    public function testRouteMatchResultForIndexPage()
    {
        $route = new DefaultRoute(BasicTestController::class);
        $routeMatch = $route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/', 'REQUEST_METHOD' => 'GET']));

        $this->assertInstanceOf(BasicTestController::class, $routeMatch->getController());
        $this->assertSame('', $routeMatch->getAction());
        $this->assertSame([], $routeMatch->getParameters());
    }

    /**
     * Test route match result for root page.
     */
    public function testRouteMatchResultForRootPage()
    {
        $route = new DefaultRoute(BasicTestController::class);
        $routeMatch = $route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo', 'REQUEST_METHOD' => 'GET']));

        $this->assertInstanceOf(BasicTestController::class, $routeMatch->getController());
        $this->assertSame('foo', $routeMatch->getAction());
        $this->assertSame([], $routeMatch->getParameters());
    }

    /**
     * Test route match result for first level index.
     */
    public function testRouteMatchResultForFirstLevelIndex()
    {
        $route = new DefaultRoute(BasicTestController::class);
        $routeMatch = $route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo/', 'REQUEST_METHOD' => 'GET']));

        $this->assertInstanceOf(BasicTestController::class, $routeMatch->getController());
        $this->assertSame('foo', $routeMatch->getAction());
        $this->assertSame([''], $routeMatch->getParameters());
    }

    /**
     * Test route match result for first level page.
     */
    public function testRouteMatchResultForFirstLevelPage()
    {
        $route = new DefaultRoute(BasicTestController::class);
        $routeMatch = $route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo/bar', 'REQUEST_METHOD' => 'GET']));

        $this->assertInstanceOf(BasicTestController::class, $routeMatch->getController());
        $this->assertSame('foo', $routeMatch->getAction());
        $this->assertSame(['bar'], $routeMatch->getParameters());
    }

    /**
     * Test route match result for second level index.
     */
    public function testRouteMatchResultForSecondLevelIndexOnPathController()
    {
        $route = new DefaultRoute(BasicTestController::class);
        $routeMatch = $route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo/bar/', 'REQUEST_METHOD' => 'GET']));

        $this->assertInstanceOf(BasicTestController::class, $routeMatch->getController());
        $this->assertSame('foo', $routeMatch->getAction());
        $this->assertSame(['bar', ''], $routeMatch->getParameters());
    }

    /**
     * Test route match result for second level page.
     */
    public function testRouteMatchResultForSecondLevelPage()
    {
        $route = new DefaultRoute(BasicTestController::class);
        $routeMatch = $route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo/bar/baz', 'REQUEST_METHOD' => 'GET']));

        $this->assertInstanceOf(BasicTestController::class, $routeMatch->getController());
        $this->assertSame('foo', $routeMatch->getAction());
        $this->assertSame(['bar', 'baz'], $routeMatch->getParameters());
    }

    /**
     * Test route match result for third level index.
     */
    public function testRouteMatchResultForThirdLevelIndex()
    {
        $route = new DefaultRoute(BasicTestController::class);
        $routeMatch = $route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo/bar/baz/', 'REQUEST_METHOD' => 'GET']));

        $this->assertInstanceOf(BasicTestController::class, $routeMatch->getController());
        $this->assertSame('foo', $routeMatch->getAction());
        $this->assertSame(['bar', 'baz', ''], $routeMatch->getParameters());
    }
}
