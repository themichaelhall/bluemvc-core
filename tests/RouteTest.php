<?php

use BlueMvc\Core\Route;
use BlueMvc\Fakes\FakeRequest;
use DataTypes\Url;

require_once __DIR__ . '/Helpers/TestControllers/BasicTestController.php';

/**
 * Test Route class.
 */
class RouteTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test getPath method.
     */
    public function testGetPath()
    {
        $this->assertSame('', (new Route('', BasicTestController::class))->getPath());
        $this->assertSame('foo', (new Route('foo', BasicTestController::class))->getPath());
        $this->assertSame('foo-bar_baz.1', (new Route('foo-bar_baz.1', BasicTestController::class))->getPath());
    }

    /**
     * Test that invalid path is invalid.
     *
     * @expectedException BlueMvc\Core\Exceptions\RouteInvalidArgumentException
     * @expectedExceptionMessage Path "Foo/Bar" contains invalid character "/".
     */
    public function testInvalidPathIsInvalid()
    {
        new Route('Foo/Bar', BasicTestController::class);
    }

    /**
     * Test getControllerClassName method.
     */
    public function testGetControllerClassName()
    {
        $route = new Route('', BasicTestController::class);
        $this->assertSame(BasicTestController::class, $route->getControllerClassName());
    }

    /**
     * Test that non existing controller class name is invalid.
     *
     * @expectedException BlueMvc\Core\Exceptions\RouteInvalidArgumentException
     * @expectedExceptionMessage Controller class "NonExistingClassName" does not exist.
     */
    public function testNonExistingControllerClassNameIsInvalid()
    {
        $route = new Route('', 'NonExistingClassName');
        $route->matches(new FakeRequest(Url::parse('http://www.domain.com/')));
    }

    /**
     * Test that class not implementing ControllerInterface is invalid.
     *
     * @expectedException BlueMvc\Core\Exceptions\RouteInvalidArgumentException
     * @expectedExceptionMessage Controller class "RouteTest" does not implement "BlueMvc\Core\Interfaces\ControllerInterface".
     */
    public function testControllerClassNotImplementingControllerInterfaceIsInvalid()
    {
        $route = new Route('', self::class);
        $route->matches(new FakeRequest(Url::parse('http://www.domain.com/')));
    }

    /**
     * Test url matches for empty path.
     */
    public function testUrlMatchesForEmptyPath()
    {
        $route = new Route('', BasicTestController::class);

        $this->assertNotNull($route->matches(new FakeRequest(Url::parse('http://www.domain.com/'))));
        $this->assertNotNull($route->matches(new FakeRequest(Url::parse('http://www.domain.com/foo'))));
        $this->assertNull($route->matches(new FakeRequest(Url::parse('http://www.domain.com/foo/'))));
        $this->assertNull($route->matches(new FakeRequest(Url::parse('http://www.domain.com/foo/bar'))));
        $this->assertNull($route->matches(new FakeRequest(Url::parse('http://www.domain.com/foo/bar/'))));
    }

    /**
     * Test url matches for non-empty path.
     */
    public function testUrlMatchesForNonEmptyPath()
    {
        $route = new Route('foo', BasicTestController::class);

        $this->assertNull($route->matches(new FakeRequest(Url::parse('http://www.domain.com/'))));
        $this->assertNull($route->matches(new FakeRequest(Url::parse('http://www.domain.com/foo'))));
        $this->assertNotNull($route->matches(new FakeRequest(Url::parse('http://www.domain.com/foo/'))));
        $this->assertNotNull($route->matches(new FakeRequest(Url::parse('http://www.domain.com/foo/bar'))));
        $this->assertNotNull($route->matches(new FakeRequest(Url::parse('http://www.domain.com/foo/bar/'))));
    }

    /**
     * Test url matches for non-matching path.
     */
    public function testUrlMatchesForNonMatchingPath()
    {
        $route = new Route('bar', BasicTestController::class);

        $this->assertNull($route->matches(new FakeRequest(Url::parse('http://www.domain.com/'))));
        $this->assertNull($route->matches(new FakeRequest(Url::parse('http://www.domain.com/foo'))));
        $this->assertNull($route->matches(new FakeRequest(Url::parse('http://www.domain.com/foo/'))));
        $this->assertNull($route->matches(new FakeRequest(Url::parse('http://www.domain.com/foo/bar'))));
        $this->assertNull($route->matches(new FakeRequest(Url::parse('http://www.domain.com/foo/bar/'))));
    }

    /**
     * Test route match result for index page on index controller.
     */
    public function testRouteMatchResultForIndexPageOnIndexController()
    {
        $route = new Route('', BasicTestController::class);
        $routeMatch = $route->matches(new FakeRequest(Url::parse('http://www.domain.com/')));

        $this->assertInstanceOf(BasicTestController::class, $routeMatch->getController());
        $this->assertSame('', $routeMatch->getAction());
        $this->assertSame([], $routeMatch->getParameters());
    }

    /**
     * Test route match result for root page on index controller.
     */
    public function testRouteMatchResultForRootPageOnIndexController()
    {
        $route = new Route('', BasicTestController::class);
        $routeMatch = $route->matches(new FakeRequest(Url::parse('http://www.domain.com/foo')));

        $this->assertInstanceOf(BasicTestController::class, $routeMatch->getController());
        $this->assertSame('foo', $routeMatch->getAction());
        $this->assertSame([], $routeMatch->getParameters());
    }

    /**
     * Test route match result for first level index on path controller.
     */
    public function testRouteMatchResultForFirstLevelIndexOnPathController()
    {
        $route = new Route('foo', BasicTestController::class);
        $routeMatch = $route->matches(new FakeRequest(Url::parse('http://www.domain.com/foo/')));

        $this->assertInstanceOf(BasicTestController::class, $routeMatch->getController());
        $this->assertSame('', $routeMatch->getAction());
        $this->assertSame([], $routeMatch->getParameters());
    }

    /**
     * Test route match result for first level page on path controller.
     */
    public function testRouteMatchResultForFirstLevelPageOnPathController()
    {
        $route = new Route('foo', BasicTestController::class);
        $routeMatch = $route->matches(new FakeRequest(Url::parse('http://www.domain.com/foo/bar')));

        $this->assertInstanceOf(BasicTestController::class, $routeMatch->getController());
        $this->assertSame('bar', $routeMatch->getAction());
        $this->assertSame([], $routeMatch->getParameters());
    }

    /**
     * Test route match result for second level index on path controller.
     */
    public function testRouteMatchResultForSecondLevelIndexOnPathController()
    {
        $route = new Route('foo', BasicTestController::class);
        $routeMatch = $route->matches(new FakeRequest(Url::parse('http://www.domain.com/foo/bar/')));

        $this->assertInstanceOf(BasicTestController::class, $routeMatch->getController());
        $this->assertSame('bar', $routeMatch->getAction());
        $this->assertSame([''], $routeMatch->getParameters());
    }

    /**
     * Test route match result for second level page on path controller.
     */
    public function testRouteMatchResultForSecondLevelPageOnPathController()
    {
        $route = new Route('foo', BasicTestController::class);
        $routeMatch = $route->matches(new FakeRequest(Url::parse('http://www.domain.com/foo/bar/baz')));

        $this->assertInstanceOf(BasicTestController::class, $routeMatch->getController());
        $this->assertSame('bar', $routeMatch->getAction());
        $this->assertSame(['baz'], $routeMatch->getParameters());
    }

    /**
     * Test route match result for third level index on path controller.
     */
    public function testRouteMatchResultForThirdLevelIndexOnPathController()
    {
        $route = new Route('foo', BasicTestController::class);
        $routeMatch = $route->matches(new FakeRequest(Url::parse('http://www.domain.com/foo/bar/baz/')));

        $this->assertInstanceOf(BasicTestController::class, $routeMatch->getController());
        $this->assertSame('bar', $routeMatch->getAction());
        $this->assertSame(['baz', ''], $routeMatch->getParameters());
    }
}
