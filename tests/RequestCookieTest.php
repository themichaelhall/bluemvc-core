<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests;

use BlueMvc\Core\RequestCookie;
use PHPUnit\Framework\TestCase;

/**
 * Test RequestCookie class.
 */
class RequestCookieTest extends TestCase
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
     * Test __toString method.
     */
    public function testToString()
    {
        $requestCookie = new RequestCookie('Foo');

        self::assertSame('Foo', $requestCookie->__toString());
    }
}
