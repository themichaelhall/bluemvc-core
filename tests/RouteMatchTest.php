<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests;

use BlueMvc\Core\RouteMatch;
use BlueMvc\Core\Tests\Helpers\TestControllers\BasicTestController;
use PHPUnit\Framework\TestCase;

/**
 * Test RouteMatchTest class.
 */
class RouteMatchTest extends TestCase
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
}
