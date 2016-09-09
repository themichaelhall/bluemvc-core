<?php

use BlueMvc\Core\Request;

/**
 * Test Request class.
 */
class RequestTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test getUrl method.
     */
    public function testGetUrl()
    {
        $request = new Request(
            [
                'HTTP_HOST'   => 'www.domain.com',
                'SERVER_PORT' => '8080',
                'REQUEST_URI' => '/foo/bar',
            ]
        );

        $this->assertSame('http://www.domain.com:8080/foo/bar', $request->getUrl()->__toString());
    }

    /**
     * Test getUrl method for https request.
     */
    public function testGetUrlForHttpsRequest()
    {
        $request = new Request(
            [
                'HTTPS'       => 'On',
                'HTTP_HOST'   => 'www.domain.com',
                'SERVER_PORT' => '443',
                'REQUEST_URI' => '/foo/bar',
            ]
        );

        $this->assertSame('https://www.domain.com/foo/bar', $request->getUrl()->__toString());
    }

    /**
     * Test getMethod method.
     */
    public function testGetMethod()
    {
        $request = new Request(
            [
                'REQUEST_METHOD' => 'POST',
            ]
        );

        $this->assertSame('POST', $request->getMethod()->__toString());
    }
}
