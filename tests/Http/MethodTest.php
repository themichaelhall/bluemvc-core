<?php

use BlueMvc\Core\Http\Method;

/**
 * Test Method class.
 */
class MethodTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test __toString method.
     */
    public function testToString()
    {
        $method = new Method('PUT');

        $this->assertSame('PUT', $method->__toString());
    }

    /**
     * Test getName method.
     */
    public function testGetName()
    {
        $method = new Method('POST');

        $this->assertSame('POST', $method->getName());
    }

    /**
     * Test isGet method.
     */
    public function testIsGet()
    {
        $this->assertTrue((new Method('GET'))->isGet());
        $this->assertFalse((new Method('POST'))->isGet());
        $this->assertFalse((new Method('PUT'))->isGet());
        $this->assertFalse((new Method('DELETE'))->isGet());
        $this->assertFalse((new Method('get'))->isGet());
    }

    /**
     * Test isPost method.
     */
    public function testIsPost()
    {
        $this->assertFalse((new Method('GET'))->isPost());
        $this->assertTrue((new Method('POST'))->isPost());
        $this->assertFalse((new Method('PUT'))->isPost());
        $this->assertFalse((new Method('DELETE'))->isPost());
        $this->assertFalse((new Method('post'))->isPost());
    }

    /**
     * Test isPut method.
     */
    public function testIsPut()
    {
        $this->assertFalse((new Method('GET'))->isPut());
        $this->assertFalse((new Method('POST'))->isPut());
        $this->assertTrue((new Method('PUT'))->isPut());
        $this->assertFalse((new Method('DELETE'))->isPut());
        $this->assertFalse((new Method('put'))->isPut());
    }

    /**
     * Test isDelete method.
     */
    public function testIsDelete()
    {
        $this->assertFalse((new Method('GET'))->isDelete());
        $this->assertFalse((new Method('POST'))->isDelete());
        $this->assertFalse((new Method('PUT'))->isDelete());
        $this->assertTrue((new Method('DELETE'))->isDelete());
        $this->assertFalse((new Method('delete'))->isDelete());
    }
}
