<?php

namespace BlueMvc\Core\Tests;

use BlueMvc\Core\ResponseCookie;

/**
 * Test ResponseCookie class.
 */
class ResponseCookieTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test getValue method.
     */
    public function testGetValue()
    {
        $responseCookie = new ResponseCookie('Foo');

        self::assertSame('Foo', $responseCookie->getValue());
    }

    /**
     * Test constructor with invalid value parameter type.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $value parameter is not a string.
     */
    public function testConstructorWithInvalidValueParameter()
    {
        new ResponseCookie(100);
    }

    /**
     * Test __toString method.
     */
    public function testToString()
    {
        $responseCookie = new ResponseCookie('Foo');

        self::assertSame('Foo', $responseCookie->__toString());
    }
}
