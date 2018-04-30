<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Collections;

use BlueMvc\Core\Collections\RequestCookieCollection;
use BlueMvc\Core\RequestCookie;
use PHPUnit\Framework\TestCase;

/**
 * Test RequestCookieCollection class.
 */
class RequestCookieCollectionTest extends TestCase
{
    /**
     * Test count for empty collection.
     */
    public function testCountForEmptyCollection()
    {
        $requestCookieCollection = new RequestCookieCollection();

        self::assertSame(0, count($requestCookieCollection));
    }

    /**
     * Test get method.
     */
    public function testGet()
    {
        $requestCookieCollection = new RequestCookieCollection();

        self::assertNull($requestCookieCollection->get('Foo'));
    }

    /**
     * Test set method.
     */
    public function testSet()
    {
        $cookieFoo = new RequestCookie('aaa');
        $cookieBar = new RequestCookie('bbb');

        $requestCookieCollection = new RequestCookieCollection();
        $requestCookieCollection->set('Foo', $cookieFoo);
        $requestCookieCollection->set('bar', $cookieBar);

        self::assertSame(2, count($requestCookieCollection));
        self::assertSame($cookieFoo, $requestCookieCollection->get('Foo'));
        self::assertSame($cookieBar, $requestCookieCollection->get('bar'));
        self::assertNull($requestCookieCollection->get('foo'));
    }

    /**
     * Test iterator functionality for empty collection.
     */
    public function testIteratorForEmptyCollection()
    {
        $requestCookieCollection = new RequestCookieCollection();

        $requestCookieArray = iterator_to_array($requestCookieCollection, true);

        self::assertSame([], $requestCookieArray);
    }

    /**
     * Test iterator functionality for non-empty collection.
     */
    public function testIteratorForNonEmptyCollection()
    {
        $cookieFoo = new RequestCookie('aaa');
        $cookieBar = new RequestCookie('bbb');

        $requestCookieCollection = new RequestCookieCollection();
        $requestCookieCollection->set('Foo', $cookieFoo);
        $requestCookieCollection->set('bar', $cookieBar);

        $requestCookieArray = iterator_to_array($requestCookieCollection, true);

        self::assertSame(['Foo' => $cookieFoo, 'bar' => $cookieBar], $requestCookieArray);
    }
}
