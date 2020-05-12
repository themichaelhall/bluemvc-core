<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests;

use BlueMvc\Core\Collections\HeaderCollection;
use BlueMvc\Core\Collections\ResponseCookieCollection;
use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Response;
use BlueMvc\Core\ResponseCookie;
use BlueMvc\Core\Tests\Helpers\Fakes\FakeCookies;
use BlueMvc\Core\Tests\Helpers\Fakes\FakeHeaders;
use BlueMvc\Core\Tests\Helpers\Fakes\FakeSession;
use DataTypes\Host;
use DataTypes\UrlPath;
use DateInterval;
use DateTimeImmutable;
use DateTimeZone;
use PHPUnit\Framework\TestCase;

/**
 * Test Response class.
 */
class ResponseTest extends TestCase
{
    /**
     * Test getContent method.
     */
    public function testGetContent()
    {
        $response = new Response();

        self::assertSame('', $response->getContent());
    }

    /**
     * Test setContent method.
     */
    public function testSetContent()
    {
        $response = new Response();
        $response->setContent('Hello world!');

        self::assertSame('Hello world!', $response->getContent());
    }

    /**
     * Test getStatusCode method.
     */
    public function testGetStatusCode()
    {
        $response = new Response();

        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test setStatusCode method.
     */
    public function testSetStatusCode()
    {
        $response = new Response();
        $response->setStatusCode(new StatusCode(StatusCode::INTERNAL_SERVER_ERROR));

        self::assertSame(StatusCode::INTERNAL_SERVER_ERROR, $response->getStatusCode()->getCode());
    }

    /**
     * Test output method for valid request.
     */
    public function testOutputForValidRequest()
    {
        $response = new Response();
        $response->setContent('Hello world!');

        ob_start();
        $response->output();
        $responseOutput = ob_get_contents();
        ob_end_clean();

        self::assertSame(['HTTP/1.1 200 OK'], FakeHeaders::get());
        self::assertSame('Hello world!', $responseOutput);
    }

    /**
     * Test output method for invalid request.
     */
    public function testOutputForInvalidRequest()
    {
        $response = new Response();
        $response->setContent('Hello world!');
        $response->setStatusCode(new StatusCode(StatusCode::CONFLICT));

        ob_start();
        $response->output();
        $responseOutput = ob_get_contents();
        ob_end_clean();

        self::assertSame(['HTTP/1.1 409 Conflict'], FakeHeaders::get());
        self::assertSame('Hello world!', $responseOutput);
    }

    /**
     * Test get headers for response with no additional headers.
     */
    public function testGetHeadersForResponseWithNoAdditionalHeaders()
    {
        $response = new Response();

        self::assertSame([], iterator_to_array($response->getHeaders()));
    }

    /**
     * Test get headers for response with additional headers.
     */
    public function testGetHeadersForResponseWithAdditionalHeaders()
    {
        $response = new Response();
        $response->setHeader('Content-Type', 'text/plain');

        self::assertSame(['Content-Type' => 'text/plain'], iterator_to_array($response->getHeaders()));
    }

    /**
     * Test output method for response with additional headers.
     */
    public function testOutputForResponseWithAdditionalHeaders()
    {
        $response = new Response();
        $response->setContent('You are being redirected.');
        $response->setStatusCode(new StatusCode(StatusCode::TEMPORARY_REDIRECT));
        $response->setHeader('Location', 'http://foo.bar.com/');

        ob_start();
        $response->output();
        $responseOutput = ob_get_contents();
        ob_end_clean();

        self::assertSame(['HTTP/1.1 307 Temporary Redirect', 'Location: http://foo.bar.com/'], FakeHeaders::get());
        self::assertSame('You are being redirected.', $responseOutput);
    }

    /**
     * Test getHeader method.
     */
    public function testGetHeader()
    {
        $response = new Response();
        $response->setHeader('Content-Type', 'text/plain');

        self::assertSame('text/plain', $response->getHeader('content-type'));
        self::assertNull($response->getHeader('Location'));
    }

    /**
     * Test setHeader method.
     */
    public function testSetHeader()
    {
        $response = new Response();
        $response->setHeader('allow', 'GET');
        $response->setHeader('Content-type', 'text/html');
        $response->setHeader('Content-Type', 'application/octet-stream');

        self::assertSame(['allow' => 'GET', 'Content-Type' => 'application/octet-stream'], iterator_to_array($response->getHeaders()));
    }

    /**
     * Test addHeader method.
     */
    public function testAddHeader()
    {
        $response = new Response();
        $response->setHeader('allow', 'GET');
        $response->addHeader('Allow', 'POST');

        self::assertSame(['Allow' => 'GET, POST'], iterator_to_array($response->getHeaders()));
    }

    /**
     * Test setHeaders method.
     */
    public function testSetHeaders()
    {
        $response = new Response();
        $headers = new HeaderCollection();
        $headers->set('Server', 'bluemvc-core');
        $response->setHeaders($headers);

        self::assertSame(['Server' => 'bluemvc-core'], iterator_to_array($response->getHeaders()));
    }

    /**
     * Test setExpiry method with null expiry time value.
     */
    public function testSetExpiryWithNullTime()
    {
        $response = new Response();
        $response->setExpiry(null);

        self::assertSame($response->getHeader('Date'), $response->getHeader('Expires'));
        self::assertSame('no-cache, no-store, must-revalidate, max-age=0', $response->getHeader('Cache-Control'));
    }

    /**
     * Test setExpiry method with past expiry time value.
     */
    public function testSetExpiryWithPastTime()
    {
        $response = new Response();

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
        $response = new Response();

        $expiry = (new DateTimeImmutable())->add(new DateInterval('PT24H'));
        $response->setExpiry($expiry);

        self::assertSame($expiry->setTimezone(new DateTimeZone('UTC'))->format('D, d M Y H:i:s \G\M\T'), $response->getHeader('Expires'));
        self::assertSame('public, max-age=86400', $response->getHeader('Cache-Control'));
    }

    /**
     * Test getCookies method with no cookies set.
     */
    public function testGetCookiesWithNoCookiesSet()
    {
        $response = new Response();

        self::assertSame([], iterator_to_array($response->getCookies()));
    }

    /**
     * Test setCookies method.
     */
    public function testSetCookies()
    {
        $response = new Response();
        $cookies = new ResponseCookieCollection();
        $fooCookie = new ResponseCookie('Foo', new DateTimeImmutable(), UrlPath::parse('/bar/'), Host::parse('example.com'), true, true);
        $barCookie = new ResponseCookie('Bar');
        $cookies->set('foo', $fooCookie);
        $cookies->set('bar', $barCookie);
        $response->setCookies($cookies);

        self::assertSame(['foo' => $fooCookie, 'bar' => $barCookie], iterator_to_array($response->getCookies()));
    }

    /**
     * Test output method for response with cookies.
     */
    public function testOutputForResponseWithCookies()
    {
        $response = new Response();
        $response->setContent('Cookies are set.');
        $cookies = new ResponseCookieCollection();
        $fooExpiry = (new DateTimeImmutable())->add(new DateInterval('P1D'));
        $fooCookie = new ResponseCookie('Foo', $fooExpiry, UrlPath::parse('/bar/'), Host::parse('example.com'), true, true);
        $barCookie = new ResponseCookie('Bar');
        $cookies->set('foo', $fooCookie);
        $cookies->set('bar', $barCookie);
        $response->setCookies($cookies);

        ob_start();
        $response->output();
        $responseOutput = ob_get_contents();
        ob_end_clean();

        self::assertSame(
            [
                ['name' => 'foo', 'value' => 'Foo', 'expire' => $fooExpiry->getTimestamp(), 'path' => '/bar/', 'domain' => 'example.com', 'secure' => true, 'httponly' => true],
                ['name' => 'bar', 'value' => 'Bar', 'expire' => 0, 'path' => '', 'domain' => '', 'secure' => false, 'httponly' => false],
            ],
            FakeCookies::get()
        );
        self::assertSame('Cookies are set.', $responseOutput);
    }

    /**
     * Test setCookie method.
     */
    public function testSetCookie()
    {
        $response = new Response();
        $cookie = new ResponseCookie('Bar', null, UrlPath::parse('/foo/bar/'));
        $response->setCookie('foo', $cookie);

        self::assertSame(['foo' => $cookie], iterator_to_array($response->getCookies()));
    }

    /**
     * Test getCookie method.
     */
    public function testGetCookie()
    {
        $response = new Response();
        $cookie = new ResponseCookie('Bar', null, UrlPath::parse('/foo/bar/'));
        $response->setCookie('foo', $cookie);

        self::assertSame($cookie, $response->getCookie('foo'));
        self::assertNull($response->getCookie('Foo'));
        self::assertNull($response->getCookie('Bar'));
    }

    /**
     * Test setCookieValue method.
     */
    public function testSetCookieValue()
    {
        $response = new Response();
        $response->setCookieValue('foo', 'bar', new DateTimeImmutable('2030-01-02 03:04:05'), UrlPath::parse('/foo/'), Host::parse('example.com'), true, true);
        $cookie = $response->getCookie('foo');

        self::assertSame('bar', $cookie->getValue());
        self::assertSame('2030-01-02 03:04:05', $cookie->getExpiry()->format('Y-m-d H:i:s'));
        self::assertSame('/foo/', $cookie->getPath()->__toString());
        self::assertSame('example.com', $cookie->getDomain()->__toString());
        self::assertTrue($cookie->isSecure());
        self::assertTrue($cookie->isHttpOnly());
    }

    /**
     * Test session is destroyed if session is empty.
     */
    public function testSessionIsDestroyedIfSessionIsEmpty()
    {
        FakeSession::start([]);
        FakeSession::setPreviousSession([]);

        $response = new Response();

        ob_start();
        $response->output();
        ob_end_clean();

        $sessionCookies = FakeCookies::get();

        self::assertSame(PHP_SESSION_NONE, FakeSession::getStatus());
        self::assertSame(1, count($sessionCookies));
        self::assertSame(session_name(), $sessionCookies[0]['name']);
        self::assertSame('', $sessionCookies[0]['value']);
        self::assertSame(1, $sessionCookies[0]['expire']);
    }

    /**
     * Test session is not destroyed if session is not empty.
     */
    public function testSessionIsNotDestroyedIfSessionIsNotEmpty()
    {
        FakeSession::start([]);
        FakeSession::setPreviousSession(['Foo' => 'Bar']);

        $response = new Response();

        ob_start();
        $response->output();
        ob_end_clean();

        $sessionCookies = FakeCookies::get();

        self::assertSame(PHP_SESSION_ACTIVE, FakeSession::getStatus());
        self::assertEmpty($sessionCookies);
    }

    /**
     * Test session is not destroyed if session is not active.
     */
    public function testSessionIsNotDestroyedIfSessionIsNotActive()
    {
        $_COOKIE[session_name()] = 'ABCDE';

        $response = new Response();

        ob_start();
        $response->output();
        ob_end_clean();

        $sessionCookies = FakeCookies::get();

        self::assertSame(PHP_SESSION_NONE, FakeSession::getStatus());
        self::assertEmpty($sessionCookies);
    }

    /**
     * Set up.
     */
    public function setUp(): void
    {
        parent::setUp();

        FakeHeaders::enable();
        FakeCookies::enable();
        FakeSession::enable();
    }

    /**
     * Tear down.
     */
    public function tearDown(): void
    {
        parent::tearDown();

        FakeHeaders::disable();
        FakeCookies::disable();
        FakeSession::disable();
    }
}
