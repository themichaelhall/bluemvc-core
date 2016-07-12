<?php

use BlueMvc\Core\Request;
use BlueMvc\Core\Route;

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
     * Test getControllerClass method.
     */
    public function testGetControllerClass()
    {
        $route = new Route('', BasicTestController::class);
        $this->assertSame(BasicTestController::class, $route->getControllerClass()->getName());
    }

    /**
     * Test that non existing controller class name is invalid.
     *
     * @expectedException BlueMvc\Core\Exceptions\RouteInvalidArgumentException
     * @expectedExceptionMessage Controller class "NonExistingClassName" does not exist.
     */
    public function testNonExistingControllerClassNameIsInvalid()
    {
        new Route('', 'NonExistingClassName');
    }

    /**
     * Test that class not implementing ControllerInterface is invalid.
     *
     * @expectedException BlueMvc\Core\Exceptions\RouteInvalidArgumentException
     * @expectedExceptionMessage Controller class "RouteTest" does not implement "BlueMvc\Core\Interfaces\ControllerInterface".
     */
    public function testControllerClassNotImplementingControllerInterfaceIsInvalid()
    {
        new Route('', self::class);
    }

    /**
     * Test url matches for empty path.
     */
    public function testUrlMatchesForEmptyPath()
    {
        $route = new Route('', BasicTestController::class);

        $this->assertNotNull($route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/'])));
        $this->assertNotNull($route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo'])));
        $this->assertNull($route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo/'])));
        $this->assertNull($route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo/bar'])));
        $this->assertNull($route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo/bar/'])));
    }

    /**
     * Test url matches for non-empty path.
     */
    public function testUrlMatchesForNonEmptyPath()
    {
        $route = new Route('foo', BasicTestController::class);

        $this->assertNull($route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/'])));
        $this->assertNull($route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo'])));
        $this->assertNotNull($route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo/'])));
        $this->assertNotNull($route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo/bar'])));
        $this->assertNotNull($route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo/bar/'])));
    }

    /**
     * Test url matches for non-matching path.
     */
    public function testUrlMatchesForNonMatchingPath()
    {
        $route = new Route('bar', BasicTestController::class);

        $this->assertNull($route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/'])));
        $this->assertNull($route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo'])));
        $this->assertNull($route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo/'])));
        $this->assertNull($route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo/bar'])));
        $this->assertNull($route->matches(new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo/bar/'])));
    }
}
