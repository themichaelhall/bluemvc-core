<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Collections;

use BlueMvc\Core\Collections\ResponseCookieCollection;
use BlueMvc\Core\ResponseCookie;
use DataTypes\Host;
use DataTypes\UrlPath;
use PHPUnit\Framework\TestCase;

/**
 * Test ResponseCookieCollection class.
 */
class ResponseCookieCollectionTest extends TestCase
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
     * Test set method.
     */
    public function testSet()
    {
        $cookieFoo = new ResponseCookie('aaa', new \DateTimeImmutable(), UrlPath::parse('/foo/'), Host::parse('example.com'), true, true);
        $cookieBar = new ResponseCookie('bbb');
        $cookieBaz = new ResponseCookie('ccc');

        $responseCookieCollection = new ResponseCookieCollection();
        $responseCookieCollection->set('Foo', $cookieFoo);
        $responseCookieCollection->set('bar', $cookieBar);
        $responseCookieCollection->set('1', $cookieBaz);

        self::assertSame(3, count($responseCookieCollection));
        self::assertSame($cookieFoo, $responseCookieCollection->get('Foo'));
        self::assertSame($cookieBar, $responseCookieCollection->get('bar'));
        self::assertNull($responseCookieCollection->get('foo'));
        self::assertSame($cookieBaz, $responseCookieCollection->get('1'));
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
        $cookieBaz = new ResponseCookie('ccc');

        $responseCookieCollection = new ResponseCookieCollection();
        $responseCookieCollection->set('Foo', $cookieFoo);
        $responseCookieCollection->set('bar', $cookieBar);
        $responseCookieCollection->set('1', $cookieBaz);

        $responseCookieArray = iterator_to_array($responseCookieCollection, true);

        self::assertSame(['Foo' => $cookieFoo, 'bar' => $cookieBar, 1 => $cookieBaz], $responseCookieArray);
    }
}
