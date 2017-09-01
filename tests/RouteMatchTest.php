<?php

namespace BlueMvc\Core\Tests;

use BlueMvc\Core\RouteMatch;
use BlueMvc\Core\Tests\Helpers\TestControllers\BasicTestController;

/**
 * Test RouteMatchTest class.
 */
class RouteMatchTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test getControllerClassName method.
     */
    public function testGetControllerClassName()
    {
        $routeMatch = new RouteMatch(BasicTestController::class);

        self::assertSame(BasicTestController::class, $routeMatch->getControllerClassName());
    }

    /**
     * Test getAction method.
     */
    public function testGetAction()
    {
        $routeMatch = new RouteMatch(BasicTestController::class, 'foo');

        self::assertSame('foo', $routeMatch->getAction());
    }

    /**
     * Test getParameters method.
     */
    public function testGetParameters()
    {
        $routeMatch = new RouteMatch(BasicTestController::class, '', ['foo', 'bar']);

        self::assertSame(['foo', 'bar'], $routeMatch->getParameters());
    }

    /**
     * Test create route match with invalid controller class name parameter type.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $controllerClassName parameter is not a string.
     */
    public function testCreateWithInvalidControllerClassNameParameterType()
    {
        new RouteMatch(false);
    }

    /**
     * Test create route match with invalid action parameter type.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $action parameter is not a string.
     */
    public function testCreateWithInvalidActionParameterType()
    {
        new RouteMatch(BasicTestController::class, -42);
    }
}
