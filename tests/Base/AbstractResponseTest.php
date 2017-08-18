<?php

namespace BlueMvc\Core\Tests\Base;

use BlueMvc\Core\Collections\HeaderCollection;
use BlueMvc\Core\Http\Method;
use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Tests\Helpers\TestRequests\BasicTestRequest;
use BlueMvc\Core\Tests\Helpers\TestResponses\BasicTestResponse;
use DataTypes\Url;

/**
 * Test AbstractResponse class (via derived test class).
 */
class AbstractResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test getContent method.
     */
    public function testGetContent()
    {
        $request = new BasicTestRequest(Url::parse('http://localhost/'), new Method('GET'));
        $response = new BasicTestResponse($request);

        self::assertSame('', $response->getContent());
    }

    /**
     * Test setContent method.
     */
    public function testSetContent()
    {
        $request = new BasicTestRequest(Url::parse('http://localhost/'), new Method('GET'));
        $response = new BasicTestResponse($request);
        $response->setContent('Hello world!');

        self::assertSame('Hello world!', $response->getContent());
    }

    /**
     * Test getStatusCode method.
     */
    public function testGetStatusCode()
    {
        $request = new BasicTestRequest(Url::parse('http://localhost/'), new Method('GET'));
        $response = new BasicTestResponse($request);

        self::assertSame('200 OK', $response->getStatusCode()->__toString());
    }

    /**
     * Test setStatusCode method.
     */
    public function testSetStatusCode()
    {
        $request = new BasicTestRequest(Url::parse('http://localhost/'), new Method('GET'));
        $response = new BasicTestResponse($request);
        $response->setStatusCode(new StatusCode(StatusCode::GONE));

        self::assertSame('410 Gone', $response->getStatusCode()->__toString());
    }

    /**
     * Test get headers for response with no additional headers.
     */
    public function testGetHeadersForResponseWithNoAdditionalHeaders()
    {
        $request = new BasicTestRequest(Url::parse('http://localhost/'), new Method('GET'));
        $response = new BasicTestResponse($request);

        self::assertSame([], iterator_to_array($response->getHeaders()));
    }

    /**
     * Test get headers for response with additional headers.
     */
    public function testGetHeadersForResponseWithAdditionalHeaders()
    {
        $request = new BasicTestRequest(Url::parse('http://localhost/'), new Method('GET'));
        $response = new BasicTestResponse($request);
        $response->setHeader('Content-Type', 'text/plain');

        self::assertSame(['Content-Type' => 'text/plain'], iterator_to_array($response->getHeaders()));
    }

    /**
     * Test getHeader method.
     */
    public function testGetHeader()
    {
        $request = new BasicTestRequest(Url::parse('http://localhost/'), new Method('GET'));
        $response = new BasicTestResponse($request);
        $response->setHeader('Content-Type', 'text/plain');

        self::assertSame('text/plain', $response->getHeader('content-type'));
        self::assertNull($response->getHeader('Location'));
    }

    /**
     * Test addHeader method.
     */
    public function testAddHeader()
    {
        $request = new BasicTestRequest(Url::parse('http://localhost/'), new Method('GET'));
        $response = new BasicTestResponse($request);
        $response->setHeader('allow', 'GET');
        $response->addHeader('Allow', 'POST');

        self::assertSame(['Allow' => 'GET, POST'], iterator_to_array($response->getHeaders()));
    }

    /**
     * Test setHeaders method.
     */
    public function testSetHeaders()
    {
        $request = new BasicTestRequest(Url::parse('http://localhost/'), new Method('GET'));
        $response = new BasicTestResponse($request);
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
        $request = new BasicTestRequest(Url::parse('http://localhost/'), new Method('GET'));
        $response = new BasicTestResponse($request);
        $response->setExpiry(null);

        self::assertSame($response->getHeader('Date'), $response->getHeader('Expires'));
        self::assertSame('no-cache, no-store, must-revalidate, max-age=0', $response->getHeader('Cache-Control'));
    }

    /**
     * Test setExpiry method with past expiry time value.
     */
    public function testSetExpiryWithPastTime()
    {
        $request = new BasicTestRequest(Url::parse('http://localhost/'), new Method('GET'));
        $response = new BasicTestResponse($request);

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
        $request = new BasicTestRequest(Url::parse('http://localhost/'), new Method('GET'));
        $response = new BasicTestResponse($request);

        $expiry = (new \DateTimeImmutable())->add(new \DateInterval('PT24H'));
        $response->setExpiry($expiry);

        self::assertSame($expiry->setTimeZone(new \DateTimeZone('UTC'))->format('D, d M Y H:i:s \G\M\T'), $response->getHeader('Expires'));
        self::assertSame('public, max-age=86400', $response->getHeader('Cache-Control'));
        self::assertSame('Accept-Encoding', $response->getHeader('Vary'));
    }
}
