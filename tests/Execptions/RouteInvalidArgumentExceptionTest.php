<?php

use BlueMvc\Core\Exceptions\RouteInvalidArgumentException;

/**
 * Test RouteInvalidArgumentException class.
 */
class RouteInvalidArgumentExceptionTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test that RouteInvalidArgumentException is subclass of InvalidArgumentException.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage This is a RouteInvalidArgumentException.
     */
    public function testRouteInvalidArgumentExceptionIsInvalidArgumentException()
    {
        throw new RouteInvalidArgumentException('This is a RouteInvalidArgumentException.');
    }
}
