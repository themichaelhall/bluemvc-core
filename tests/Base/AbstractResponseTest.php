<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Base;

use BlueMvc\Core\Collections\HeaderCollection;
use BlueMvc\Core\Collections\ResponseCookieCollection;
use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\ResponseCookie;
use BlueMvc\Core\Tests\Helpers\TestResponses\BasicTestResponse;
use DataTypes\Host;
use DataTypes\UrlPath;
use DateInterval;
use DateTimeImmutable;
use DateTimeZone;
use PHPUnit\Framework\TestCase;

/**
 * Test AbstractResponse class (via derived test class).
 */
class AbstractResponseTest extends TestCase
{
    /**
     * Test getContent method.
     */
    public function testGetContent()
    {
        $response = new BasicTestResponse();

        self::assertSame('', $response->getContent());
    }

    /**
     * Test setContent method.
     */
    public function testSetContent()
    {
        $response = new BasicTestResponse();
        $response->setContent('Hello world!');

        self::assertSame('Hello world!', $response->getContent());
    }

    /**
     * Test getStatusCode method.
     */
    public function testGetStatusCode()
    {
        $response = new BasicTestResponse();

        self::assertSame('200 OK', $response->getStatusCode()->__toString());
    }

    /**
     * Test setStatusCode method.
     */
    public function testSetStatusCode()
    {
        $response = new BasicTestResponse();
        $response->setStatusCode(new StatusCode(StatusCode::GONE));

        self::assertSame('410 Gone', $response->getStatusCode()->__toString());
    }

    /**
     * Test get headers for response with no additional headers.
     */
    public function testGetHeadersForResponseWithNoAdditionalHeaders()
    {
        $response = new BasicTestResponse();

        self::assertSame([], iterator_to_array($response->getHeaders()));
    }

    /**
     * Test get headers for response with additional headers.
     */
    public function testGetHeadersForResponseWithAdditionalHeaders()
    {
        $response = new BasicTestResponse();
        $response->setHeader('Content-Type', 'text/plain');

        self::assertSame(['Content-Type' => 'text/plain'], iterator_to_array($response->getHeaders()));
    }

    /**
     * Test setHeader method.
     */
    public function testSetHeader()
    {
        $response = new BasicTestResponse();
        $response->setHeader('allow', 'GET');
        $response->setHeader('Content-Type', 'text/plain');
        $response->setHeader('Allow', 'POST');

        self::assertSame(['Allow' => 'POST', 'Content-Type' => 'text/plain'], iterator_to_array($response->getHeaders()));
    }

    /**
     * Test getHeader method.
     */
    public function testGetHeader()
    {
        $response = new BasicTestResponse();
        $response->setHeader('Content-Type', 'text/plain');

        self::assertSame('text/plain', $response->getHeader('content-type'));
        self::assertNull($response->getHeader('Location'));
    }

    /**
     * Test addHeader method.
     */
    public function testAddHeader()
    {
        $response = new BasicTestResponse();
        $response->setHeader('allow', 'GET');
        $response->addHeader('Allow', 'POST');

        self::assertSame(['Allow' => 'GET, POST'], iterator_to_array($response->getHeaders()));
    }

    /**
     * Test setHeaders method.
     */
    public function testSetHeaders()
    {
        $response = new BasicTestResponse();
        $headers = new HeaderCollection();
        $headers->set('Content-type', 'text/html');
        $headers->set('Cache-Control', 'max-age=0, must-revalidate');
        $response->setHeaders($headers);

        self::assertSame(['Content-type' => 'text/html', 'Cache-Control' => 'max-age=0, must-revalidate'], iterator_to_array($response->getHeaders()));
    }

    /**
     * Test setExpiry method with null expiry time value.
     */
    public function testSetExpiryWithNullTime()
    {
        $response = new BasicTestResponse();
        $response->setExpiry(null);

        self::assertSame($response->getHeader('Date'), $response->getHeader('Expires'));
        self::assertSame('no-cache, no-store, must-revalidate, max-age=0', $response->getHeader('Cache-Control'));
    }

    /**
     * Test setExpiry method with past expiry time value.
     */
    public function testSetExpiryWithPastTime()
    {
        $response = new BasicTestResponse();

        $expiry = (new DateTimeImmutable())->sub(new DateInterval('PT24H'));
        $response->setExpiry($expiry);

        self::assertSame($expiry->setTimezone(new DateTimeZone('UTC'))->format('D, d M Y H:i:s \G\M\T'), $response->getHeader('Expires'));
        self::assertSame('no-cache, no-store, must-revalidate, max-age=0', $response->getHeader('Cache-Control'));
    }

    /**
     * Test setExpiry method with future expiry time value.
     */
    public function testSetExpiryWithFutureTime()
    {
        $response = new BasicTestResponse();

        $expiry = (new DateTimeImmutable())->add(new DateInterval('PT24H'));
        $response->setExpiry($expiry);

        self::assertSame($expiry->setTimezone(new DateTimeZone('UTC'))->format('D, d M Y H:i:s \G\M\T'), $response->getHeader('Expires'));
        self::assertSame('public, max-age=86400', $response->getHeader('Cache-Control'));
    }

    /**
     * Test getCookies method.
     */
    public function testGetCookies()
    {
        $response = new BasicTestResponse();

        self::assertSame([], iterator_to_array($response->getCookies()));
    }

    /**
     * Test setCookies method.
     */
    public function testSetCookies()
    {
        $response = new BasicTestResponse();
        $cookies = new ResponseCookieCollection();
        $fooCookie = new ResponseCookie('Foo', new DateTimeImmutable(), UrlPath::parse('/bar/'), Host::parse('example.com'), true, true);
        $barCookie = new ResponseCookie('Bar');
        $cookies->set('foo', $fooCookie);
        $cookies->set('bar', $barCookie);
        $response->setCookies($cookies);

        self::assertSame(['foo' => $fooCookie, 'bar' => $barCookie], iterator_to_array($response->getCookies()));
    }

    /**
     * Test setCookie method.
     */
    public function testSetCookie()
    {
        $response = new BasicTestResponse();
        $fooCookie = new ResponseCookie('Foo', new DateTimeImmutable(), UrlPath::parse('/bar/'), Host::parse('example.com'), true, true);
        $barCookie = new ResponseCookie('Bar');
        $response->setCookie('foo', $fooCookie);
        $response->setCookie('foo', $barCookie);

        self::assertSame(['foo' => $barCookie], iterator_to_array($response->getCookies()));
    }

    /**
     * Test getCookie method.
     */
    public function testGetCookie()
    {
        $response = new BasicTestResponse();
        $fooCookie = new ResponseCookie('Foo', new DateTimeImmutable(), UrlPath::parse('/bar/'), Host::parse('example.com'), true, true);
        $barCookie = new ResponseCookie('Bar');
        $response->setCookie('foo', $fooCookie);
        $response->setCookie('foo', $barCookie);

        self::assertSame($barCookie, $response->getCookie('foo'));
        self::assertNull($response->getCookie('bar'));
    }

    /**
     * Test setCookieValue method.
     */
    public function testSetCookieValue()
    {
        $response = new BasicTestResponse();
        $response->setCookieValue('foo', 'bar');
        $cookie = $response->getCookie('foo');

        self::assertSame('bar', $cookie->getValue());
        self::assertNull($cookie->getExpiry());
        self::assertNull($cookie->getPath());
        self::assertNull($cookie->getDomain());
        self::assertFalse($cookie->isSecure());
        self::assertFalse($cookie->isHttpOnly());
    }
}
