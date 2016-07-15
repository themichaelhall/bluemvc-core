<?php

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
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/']);
        $response = new Response($request);

        $this->assertSame($request, $response->getRequest());
    }

    /**
     * Test getContent method.
     */
    public function testGetContent()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/']);
        $response = new Response($request);

        $this->assertSame('', $response->getContent());
    }

    /**
     * Test setContent method.
     */
    public function testSetContent()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/']);
        $response = new Response($request);
        $response->setContent('Hello world!');

        $this->assertSame('Hello world!', $response->getContent());
    }
}
