<?php

namespace BlueMvc\Core\Tests;

use BlueMvc\Core\ResponseCookie;
use DataTypes\UrlPath;

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
     * Test getExpiry method with no expiry set.
     */
    public function testGetExpiryWithNoExpirySet()
    {
        $responseCookie = new ResponseCookie('Foo');

        self::assertNull($responseCookie->getExpiry());
    }

    /**
     * Test getExpiry method with expiry set.
     */
    public function testGetExpiryWithExpirySet()
    {
        $expiry = new \DateTimeImmutable();
        $responseCookie = new ResponseCookie('Foo', $expiry);

        self::assertSame($expiry, $responseCookie->getExpiry());
    }

    /**
     * Test getPath method with no path set.
     */
    public function testGetPathWithNoPathSet()
    {
        $responseCookie = new ResponseCookie('Foo');

        self::assertNull($responseCookie->getPath());
    }

    /**
     * Test getPath method with path set.
     */
    public function testGetPathWithPathSet()
    {
        $path = UrlPath::parse('/foo/');
        $responseCookie = new ResponseCookie('Foo', null, $path);

        self::assertSame($path, $responseCookie->getPath());
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
     * Test constructor with non-absolute path parameter.
     *
     * @expectedException \BlueMvc\Core\Exceptions\InvalidResponseCookiePathException
     * @expectedExceptionMessage Path "../foo/" is not an absolute path.
     */
    public function testConstructorWithNonAbsolutePathParameter()
    {
        new ResponseCookie('foo', null, UrlPath::parse('../foo/'));
    }

    /**
     * Test constructor with non-directory path parameter.
     *
     * @expectedException \BlueMvc\Core\Exceptions\InvalidResponseCookiePathException
     * @expectedExceptionMessage Path "/foo/bar" is not a directory.
     */
    public function testConstructorWithNonDirectoryPathParameter()
    {
        new ResponseCookie('foo', null, UrlPath::parse('/foo/bar'));
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
