<?php

use BlueMvc\Core\RouteMatch;

require_once __DIR__ . '/Helpers/TestControllers/BasicTestController.php';

/**
 * Test RouteMatchTest class.
 */
class RouteMatchTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test getControllerClassName method.
     */
    public function testGetControllerClassName()
    {
        $routeMatch = new RouteMatch(BasicTestController::class);

        $this->assertSame(BasicTestController::class, $routeMatch->getControllerClassName());
    }

    /**
     * Test getAction method.
     */
    public function testGetAction()
    {
        $routeMatch = new RouteMatch(BasicTestController::class, 'foo');

        $this->assertSame('foo', $routeMatch->getAction());
    }

    /**
     * Test getParameters method.
     */
    public function testGetParameters()
    {
        $routeMatch = new RouteMatch(BasicTestController::class, '', ['foo', 'bar']);

        $this->assertSame(['foo', 'bar'], $routeMatch->getParameters());
    }
}
