<?php

use BlueMvc\Core\Route;

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
        $this->assertSame('', (new Route(''))->getPath());
        $this->assertSame('foo', (new Route('foo'))->getPath());
        $this->assertSame('foo-bar_baz.1', (new Route('foo-bar_baz.1'))->getPath());
    }
}
