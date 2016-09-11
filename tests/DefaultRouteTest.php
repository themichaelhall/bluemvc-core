<?php

use BlueMvc\Core\DefaultRoute;
use BlueMvc\Fakes\FakeRequest;
use DataTypes\Url;

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
     * @expectedException BlueMvc\Core\Exceptions\RouteInvalidArgumentException
     * @expectedExceptionMessage Controller class "NonExistingClassName" does not exist.
     */
    public function testNonExistingControllerClassNameIsInvalid()
    {
        $route = new DefaultRoute('NonExistingClassName');
        $route->matches(new FakeRequest(Url::parse('http://www.domain.com/')));
    }

    /**
     * Test that class not implementing ControllerInterface is invalid.
     *
     * @expectedException BlueMvc\Core\Exceptions\RouteInvalidArgumentException
     * @expectedExceptionMessage Controller class "DefaultRouteTest" does not implement "BlueMvc\Core\Interfaces\ControllerInterface".
     */
    public function testControllerClassNotImplementingControllerInterfaceIsInvalid()
    {
        $route = new DefaultRoute(self::class);
        $route->matches(new FakeRequest(Url::parse('http://www.domain.com/')));
    }

    /**
     * Test that all url matches.
     */
    public function testAllUrlMatches()
    {
        $route = new DefaultRoute(BasicTestController::class);

        $this->assertNotNull($route->matches(new FakeRequest(Url::parse('http://www.domain.com/'))));
        $this->assertNotNull($route->matches(new FakeRequest(Url::parse('http://www.domain.com/foo'))));
        $this->assertNotNull($route->matches(new FakeRequest(Url::parse('http://www.domain.com/foo/'))));
        $this->assertNotNull($route->matches(new FakeRequest(Url::parse('http://www.domain.com/foo/bar'))));
        $this->assertNotNull($route->matches(new FakeRequest(Url::parse('http://www.domain.com/foo/bar/'))));
    }

    /**
     * Test route match result for index page.
     */
    public function testRouteMatchResultForIndexPage()
    {
        $route = new DefaultRoute(BasicTestController::class);
        $routeMatch = $route->matches(new FakeRequest(Url::parse('http://www.domain.com/')));

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
        $routeMatch = $route->matches(new FakeRequest(Url::parse('http://www.domain.com/foo')));

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
        $routeMatch = $route->matches(new FakeRequest(Url::parse('http://www.domain.com/foo/')));

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
        $routeMatch = $route->matches(new FakeRequest(Url::parse('http://www.domain.com/foo/bar')));

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
        $routeMatch = $route->matches(new FakeRequest(Url::parse('http://www.domain.com/foo/bar/')));

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
        $routeMatch = $route->matches(new FakeRequest(Url::parse('http://www.domain.com/foo/bar/baz')));

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
        $routeMatch = $route->matches(new FakeRequest(Url::parse('http://www.domain.com/foo/bar/baz/')));

        $this->assertInstanceOf(BasicTestController::class, $routeMatch->getController());
        $this->assertSame('foo', $routeMatch->getAction());
        $this->assertSame(['bar', 'baz', ''], $routeMatch->getParameters());
    }
}
