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
}
