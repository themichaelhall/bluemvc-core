<?php

namespace BlueMvc\Core\Tests\Collections;

use BlueMvc\Core\Collections\ResponseCookieCollection;
use BlueMvc\Core\ResponseCookie;
use DataTypes\Host;
use DataTypes\UrlPath;

/**
 * Test ResponseCookieCollection class.
 */
class ResponseCookieCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test count for empty collection.
     */
    public function testCountForEmptyCollection()
    {
        $responseCookieCollection = new ResponseCookieCollection();

        self::assertSame(0, count($responseCookieCollection));
    }

    /**
     * Test get method.
     */
    public function testGet()
    {
        $responseCookieCollection = new ResponseCookieCollection();

        self::assertNull($responseCookieCollection->get('Foo'));
    }

    /**
     * Test get method with invalid name parameter type.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $name parameter is not a string.
     */
    public function testGetMethodWithInvalidNameParameterType()
    {
        $responseCookieCollection = new ResponseCookieCollection();

        $responseCookieCollection->get(null);
    }

    /**
     * Test set method.
     */
    public function testSet()
    {
        $cookieFoo = new ResponseCookie('aaa', new \DateTimeImmutable(), UrlPath::parse('/foo/'), Host::parse('example.com'), true, true);
        $cookieBar = new ResponseCookie('bbb');

        $responseCookieCollection = new ResponseCookieCollection();
        $responseCookieCollection->set('Foo', $cookieFoo);
        $responseCookieCollection->set('bar', $cookieBar);

        self::assertSame(2, count($responseCookieCollection));
        self::assertSame($cookieFoo, $responseCookieCollection->get('Foo'));
        self::assertSame($cookieBar, $responseCookieCollection->get('bar'));
        self::assertNull($responseCookieCollection->get('foo'));
    }

    /**
     * Test set method with invalid name parameter type.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $name parameter is not a string.
     */
    public function testSetMethodWithInvalidNameParameterType()
    {
        $responseCookieCollection = new ResponseCookieCollection();

        $responseCookieCollection->set(false, new ResponseCookie(''));
    }

    /**
     * Test iterator functionality for empty collection.
     */
    public function testIteratorForEmptyCollection()
    {
        $responseCookieCollection = new ResponseCookieCollection();

        $responseCookieArray = iterator_to_array($responseCookieCollection, true);

        self::assertSame([], $responseCookieArray);
    }

    /**
     * Test iterator functionality for non-empty collection.
     */
    public function testIteratorForNonEmptyCollection()
    {
        $cookieFoo = new ResponseCookie('aaa', new \DateTimeImmutable(), UrlPath::parse('/'), null, false, true);
        $cookieBar = new ResponseCookie('bbb');

        $responseCookieCollection = new ResponseCookieCollection();
        $responseCookieCollection->set('Foo', $cookieFoo);
        $responseCookieCollection->set('bar', $cookieBar);

        $responseCookieArray = iterator_to_array($responseCookieCollection, true);

        self::assertSame(['Foo' => $cookieFoo, 'bar' => $cookieBar], $responseCookieArray);
    }
}
