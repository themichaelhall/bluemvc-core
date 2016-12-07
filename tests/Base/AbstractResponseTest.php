<?php

require_once __DIR__ . '/../Helpers/TestResponses/BasicTestResponse.php';
require_once __DIR__ . '/../Helpers/TestRequests/BasicTestRequest.php';

use BlueMvc\Core\Http\Method;
use BlueMvc\Core\Http\StatusCode;
use DataTypes\Url;

/**
 * Test AbstractResponse class (via derived test class).
 */
class AbstractResponseTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test getContent method.
     */
    public function testGetContent()
    {
        $request = new BasicTestRequest(Url::parse('http://localhost/'), new Method('GET'));
        $response = new BasicTestResponse($request);

        $this->assertSame('', $response->getContent());
    }

    /**
     * Test setContent method.
     */
    public function testSetContent()
    {
        $request = new BasicTestRequest(Url::parse('http://localhost/'), new Method('GET'));
        $response = new BasicTestResponse($request);
        $response->setContent('Hello world!');

        $this->assertSame('Hello world!', $response->getContent());
    }

    /**
     * Test getStatusCode method.
     */
    public function testGetStatusCode()
    {
        $request = new BasicTestRequest(Url::parse('http://localhost/'), new Method('GET'));
        $response = new BasicTestResponse($request);

        $this->assertSame('200 OK', $response->getStatusCode()->__toString());
    }

    /**
     * Test setStatusCode method.
     */
    public function testSetStatusCode()
    {
        $request = new BasicTestRequest(Url::parse('http://localhost/'), new Method('GET'));
        $response = new BasicTestResponse($request);
        $response->setStatusCode(new StatusCode(StatusCode::GONE));

        $this->assertSame('410 Gone', $response->getStatusCode()->__toString());
    }

    /**
     * Test get headers for response with no additional headers.
     */
    public function testGetHeadersForResponseWithNoAdditionalHeaders()
    {
        $request = new BasicTestRequest(Url::parse('http://localhost/'), new Method('GET'));
        $response = new BasicTestResponse($request);

        $this->assertSame([], iterator_to_array($response->getHeaders()));
    }

    /**
     * Test get headers for response with additional headers.
     */
    public function testGetHeadersForResponseWithAdditionalHeaders()
    {
        $request = new BasicTestRequest(Url::parse('http://localhost/'), new Method('GET'));
        $response = new BasicTestResponse($request);
        $response->setHeader('Content-Type', 'text/plain');

        $this->assertSame(['Content-Type' => 'text/plain'], iterator_to_array($response->getHeaders()));
    }

    /**
     * Test getHeader method.
     */
    public function testGetHeader()
    {
        $request = new BasicTestRequest(Url::parse('http://localhost/'), new Method('GET'));
        $response = new BasicTestResponse($request);
        $response->setHeader('Content-Type', 'text/plain');

        $this->assertSame('text/plain', $response->getHeader('content-type'));
        $this->assertNull($response->getHeader('Location'));
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

        $this->assertSame(['Allow' => 'GET, POST'], iterator_to_array($response->getHeaders()));
    }
}
