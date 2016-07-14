<?php

use BlueMvc\Core\RouteMatch;

require_once __DIR__ . '/Helpers/TestControllers/BasicTestController.php';

/**
 * Test RouteMatchTest class.
 */
class RouteMatchTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test getController method.
     */
    public function testGetController()
    {
        $routeMatch = new RouteMatch(new BasicTestController());

        $this->assertInstanceOf(BasicTestController::class, $routeMatch->getController());
    }

    /**
     * Test getAction method.
     */
    public function testGetAction()
    {
        $routeMatch = new RouteMatch(new BasicTestController(), 'foo');

        $this->assertSame('foo', $routeMatch->getAction());
    }
}
