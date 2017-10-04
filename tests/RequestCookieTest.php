<?php

namespace BlueMvc\Core\Tests;

use BlueMvc\Core\RequestCookie;

/**
 * Test RequestCookie class.
 */
class RequestCookieTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test getValue method.
     */
    public function testGetValue()
    {
        $requestCookie = new RequestCookie('Foo');

        self::assertSame('Foo', $requestCookie->getValue());
    }

    /**
     * Test constructor with invalid value parameter type.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $value parameter is not a string.
     */
    public function testConstructorWithInvalidValueParameter()
    {
        new RequestCookie(100);
    }

    /**
     * Test __toString method.
     */
    public function testToString()
    {
        $requestCookie = new RequestCookie('Foo');

        self::assertSame('Foo', $requestCookie->__toString());
    }
}
