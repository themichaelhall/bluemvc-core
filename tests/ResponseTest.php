<?php

require_once __DIR__ . '/Helpers/Fakes/FakeHeaders.php';

use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Request;
use BlueMvc\Core\Response;

/**
 * Test Response class.
 */
class ResponseTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test getRequest method.
     */
    public function testGetRequest()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);

        $this->assertSame($request, $response->getRequest());
    }

    /**
     * Test getContent method.
     */
    public function testGetContent()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);

        $this->assertSame('', $response->getContent());
    }

    /**
     * Test setContent method.
     */
    public function testSetContent()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        $response->setContent('Hello world!');

        $this->assertSame('Hello world!', $response->getContent());
    }

    /**
     * Test getStatusCode method.
     */
    public function testGetStatusCode()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);

        $this->assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test setStatusCode method.
     */
    public function testSetStatusCode()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        $response->setStatusCode(new StatusCode(StatusCode::INTERNAL_SERVER_ERROR));

        $this->assertSame(StatusCode::INTERNAL_SERVER_ERROR, $response->getStatusCode()->getCode());
    }

    /**
     * Test output method for valid request.
     */
    public function testOutputForValidRequest()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        $response->setContent('Hello world!');

        ob_start();
        $response->output();
        $responseOutput = ob_get_contents();
        ob_end_clean();

        $this->assertSame(['HTTP/1.1 200 OK'], FakeHeaders::get());
        $this->assertSame('Hello world!', $responseOutput);
    }

    /**
     * Test output method for invalid request.
     */
    public function testOutputForInvalidRequest()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        $response->setContent('Hello world!');
        $response->setStatusCode(new StatusCode(StatusCode::CONFLICT));

        ob_start();
        $response->output();
        $responseOutput = ob_get_contents();
        ob_end_clean();

        $this->assertSame(['HTTP/1.1 409 Conflict'], FakeHeaders::get());
        $this->assertSame('Hello world!', $responseOutput);
    }

    /**
     * Test get headers for response with no additional headers.
     */
    public function testGetHeadersForResponseWithNoAdditionalHeaders()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);

        $this->assertSame([], iterator_to_array($response->getHeaders()));
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
