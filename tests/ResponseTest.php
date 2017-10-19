<?php

namespace BlueMvc\Core\Tests;

use BlueMvc\Core\Collections\HeaderCollection;
use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Response;
use BlueMvc\Core\Tests\Helpers\Fakes\FakeHeaders;

/**
 * Test Response class.
 */
class ResponseTest extends \PHPUnit_Framework_TestCase
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
     * Test setContent method with invalid content parameter type.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $content parameter is not a string.
     */
    public function testSetContentWithInvalidContentParameterType()
    {
        $response = new Response();
        $response->setContent(12.34);
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

        $expiry = (new \DateTimeImmutable())->sub(new \DateInterval('PT24H'));
        $response->setExpiry($expiry);

        self::assertSame($expiry->setTimeZone(new \DateTimeZone('UTC'))->format('D, d M Y H:i:s \G\M\T'), $response->getHeader('Expires'));
        self::assertSame('no-cache, no-store, must-revalidate, max-age=0', $response->getHeader('Cache-Control'));
    }

    /**
     * Test setExpiry method with future expiry time value.
     */
    public function testSetExpiryWithFutureTime()
    {
        $response = new Response();

        $expiry = (new \DateTimeImmutable())->add(new \DateInterval('PT24H'));
        $response->setExpiry($expiry);

        self::assertSame($expiry->setTimeZone(new \DateTimeZone('UTC'))->format('D, d M Y H:i:s \G\M\T'), $response->getHeader('Expires'));
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
     * Set up.
     */
    public function setUp()
    {
        FakeHeaders::enable();
    }

    /**
     * Tear down.
     */
    public function tearDown()
    {
        FakeHeaders::disable();
    }
}
