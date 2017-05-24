<?php

namespace BlueMvc\Core\Tests\Http;

use BlueMvc\Core\Http\Method;

/**
 * Test Method class.
 */
class MethodTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test __toString method.
     */
    public function testToString()
    {
        $method = new Method('PUT');

        self::assertSame('PUT', $method->__toString());
    }

    /**
     * Test getName method.
     */
    public function testGetName()
    {
        $method = new Method('POST');

        self::assertSame('POST', $method->getName());
    }

    /**
     * Test isGet method.
     */
    public function testIsGet()
    {
        self::assertTrue((new Method('GET'))->isGet());
        self::assertFalse((new Method('POST'))->isGet());
        self::assertFalse((new Method('PUT'))->isGet());
        self::assertFalse((new Method('DELETE'))->isGet());
        self::assertFalse((new Method('get'))->isGet());
    }

    /**
     * Test isPost method.
     */
    public function testIsPost()
    {
        self::assertFalse((new Method('GET'))->isPost());
        self::assertTrue((new Method('POST'))->isPost());
        self::assertFalse((new Method('PUT'))->isPost());
        self::assertFalse((new Method('DELETE'))->isPost());
        self::assertFalse((new Method('post'))->isPost());
    }

    /**
     * Test isPut method.
     */
    public function testIsPut()
    {
        self::assertFalse((new Method('GET'))->isPut());
        self::assertFalse((new Method('POST'))->isPut());
        self::assertTrue((new Method('PUT'))->isPut());
        self::assertFalse((new Method('DELETE'))->isPut());
        self::assertFalse((new Method('put'))->isPut());
    }

    /**
     * Test isDelete method.
     */
    public function testIsDelete()
    {
        self::assertFalse((new Method('GET'))->isDelete());
        self::assertFalse((new Method('POST'))->isDelete());
        self::assertFalse((new Method('PUT'))->isDelete());
        self::assertTrue((new Method('DELETE'))->isDelete());
        self::assertFalse((new Method('delete'))->isDelete());
    }

    /**
     * Test that invalid character in method name is invalid.
     *
     * @expectedException \BlueMvc\Core\Exceptions\Http\InvalidMethodNameException
     * @expectedExceptionMessage Method "FOO{BAR" contains invalid character "{".
     */
    public function testInvalidCharacterInMethodNameIsInvalid()
    {
        new Method('FOO{BAR');
    }
}
