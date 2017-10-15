<?php

namespace BlueMvc\Core\Tests;

use BlueMvc\Core\ResponseCookie;
use DataTypes\Host;
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
     * Test getDomain method with no domain set.
     */
    public function testGetDomainWithNoDomainSet()
    {
        $responseCookie = new ResponseCookie('Foo');

        self::assertNull($responseCookie->getDomain());
    }

    /**
     * Test getDomain method with domain set.
     */
    public function testGetDomainWithDomainSet()
    {
        $domain = Host::parse('www.example.com');
        $responseCookie = new ResponseCookie('Foo', null, null, $domain);

        self::assertSame($domain, $responseCookie->getDomain());
    }

    /**
     * Test isSecure method with no is secure set.
     */
    public function testIsSecureWithNoIsSecureSet()
    {
        $responseCookie = new ResponseCookie('Foo');

        self::assertFalse($responseCookie->isSecure());
    }

    /**
     * Test isSecure method with is secure set.
     */
    public function testIsSecureWithIsSecureSet()
    {
        $responseCookie = new ResponseCookie('Foo', null, null, null, true);

        self::assertTrue($responseCookie->isSecure());
    }

    /**
     * Test isHttpOnly method with no http only set.
     */
    public function testIsHttpOnlyWithNoHttpOnlySet()
    {
        $responseCookie = new ResponseCookie('Foo');

        self::assertFalse($responseCookie->isHttpOnly());
    }

    /**
     * Test isHttpOnly method with http only set.
     */
    public function testIsHttpOnlyWithHttpOnlySet()
    {
        $responseCookie = new ResponseCookie('Foo', null, null, null, false, true);

        self::assertTrue($responseCookie->isHttpOnly());
    }

    /**
     * Test constructor with invalid value parameter type.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $value parameter is not a string.
     */
    public function testConstructorWithInvalidValueParameterType()
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
     * Test constructor with invalid is secure parameter type.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $isSecure parameter is not a boolean.
     */
    public function testConstructorWithInvalidIsSecureParameterType()
    {
        new ResponseCookie('Foo', null, null, null, 10);
    }

    /**
     * Test constructor with invalid is http only parameter type.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $isHttpOnly parameter is not a boolean.
     */
    public function testConstructorWithInvalidIsHttpOnlyParameterType()
    {
        new ResponseCookie('Foo', null, null, null, false, null);
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
