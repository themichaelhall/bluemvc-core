<?php

require_once __DIR__ . '/Helpers/Fakes/FakeHeaders.php';

use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Response;
use BlueMvc\Fakes\FakeRequest;
use DataTypes\Url;

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
        $request = new FakeRequest(Url::parse('http://www.domain.com/'));
        $response = new Response($request);

        $this->assertSame($request, $response->getRequest());
    }

    /**
     * Test getContent method.
     */
    public function testGetContent()
    {
        $request = new FakeRequest(Url::parse('http://www.domain.com/'));
        $response = new Response($request);

        $this->assertSame('', $response->getContent());
    }

    /**
     * Test setContent method.
     */
    public function testSetContent()
    {
        $request = new FakeRequest(Url::parse('http://www.domain.com/'));
        $response = new Response($request);
        $response->setContent('Hello world!');

        $this->assertSame('Hello world!', $response->getContent());
    }

    /**
     * Test getStatusCode method.
     */
    public function testGetStatusCode()
    {
        $request = new FakeRequest(Url::parse('http://www.domain.com/'));
        $response = new Response($request);

        $this->assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test setStatusCode method.
     */
    public function testSetStatusCode()
    {
        $request = new FakeRequest(Url::parse('http://www.domain.com/'));
        $response = new Response($request);
        $response->setStatusCode(new StatusCode(StatusCode::INTERNAL_SERVER_ERROR));

        $this->assertSame(StatusCode::INTERNAL_SERVER_ERROR, $response->getStatusCode()->getCode());
    }

    /**
     * Test output method for valid request.
     */
    public function testOutputForValidRequest()
    {
        $request = new FakeRequest(Url::parse('http://www.domain.com/'));
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
        $request = new FakeRequest(Url::parse('http://www.domain.com/'));
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
