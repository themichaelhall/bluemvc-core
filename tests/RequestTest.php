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
                'HTTP_HOST'      => 'www.domain.com',
                'REQUEST_URI'    => '/foo/bar',
                'REQUEST_METHOD' => 'GET',
            ]
        );

        $this->assertSame('http://www.domain.com/foo/bar', $request->getUrl()->__toString());
    }

    /**
     * Test getUrl method for https request.
     */
    public function testGetUrlForHttpsRequest()
    {
        $request = new Request(
            [
                'HTTPS'          => 'On',
                'HTTP_HOST'      => 'www.domain.com',
                'REQUEST_URI'    => '/foo/bar',
                'REQUEST_METHOD' => 'GET',
            ]
        );

        $this->assertSame('https://www.domain.com/foo/bar', $request->getUrl()->__toString());
    }

    /**
     * Test getUrl method with port.
     */
    public function testGetUrlWithPort()
    {
        $request = new Request(
            [
                'HTTP_HOST'      => 'www.domain.com:8080',
                'REQUEST_URI'    => '/foo/bar',
                'REQUEST_METHOD' => 'GET',
            ]
        );

        $this->assertSame('http://www.domain.com:8080/foo/bar', $request->getUrl()->__toString());
    }

    /**
     * Test getUrl method with empty query string.
     */
    public function testGetUrlWithEmptyQueryString()
    {
        $request = new Request(
            [
                'HTTP_HOST'      => 'www.domain.com',
                'REQUEST_URI'    => '/foo/bar?',
                'REQUEST_METHOD' => 'GET',
            ]
        );

        $this->assertSame('http://www.domain.com/foo/bar?', $request->getUrl()->__toString());
    }

    /**
     * Test getUrl method with query string.
     */
    public function testGetUrlWithQueryString()
    {
        $request = new Request(
            [
                'HTTP_HOST'      => 'www.domain.com',
                'REQUEST_URI'    => '/foo/bar?baz=true',
                'REQUEST_METHOD' => 'GET',
            ]
        );

        $this->assertSame('http://www.domain.com/foo/bar?baz=true', $request->getUrl()->__toString());
    }

    /**
     * Test getMethod method.
     */
    public function testGetMethod()
    {
        $request = new Request(
            [
                'HTTP_HOST'      => 'www.domain.com',
                'REQUEST_URI'    => '/foo/bar',
                'REQUEST_METHOD' => 'POST',
            ]
        );

        $this->assertSame('POST', $request->getMethod()->__toString());
    }

    /**
     * Test getHeaders method.
     */
    public function testGetHeaders()
    {
        $request = new Request(
            [
                'HTTP_HOST'            => 'www.domain.com',
                'REQUEST_URI'          => '/foo/bar',
                'REQUEST_METHOD'       => 'GET',
                'HTTP_ACCEPT_ENCODING' => 'gzip, deflate',
            ]
        );

        $this->assertSame(['Host' => 'www.domain.com', 'Accept-Encoding' => 'gzip, deflate'], iterator_to_array($request->getHeaders()));
    }

    /**
     * Test getHeader method.
     */
    public function testGetHeader()
    {
        $request = new Request(
            [
                'HTTP_HOST'            => 'www.domain.com',
                'REQUEST_URI'          => '/foo/bar',
                'REQUEST_METHOD'       => 'GET',
                'HTTP_ACCEPT_ENCODING' => 'gzip, deflate',
            ]
        );

        $this->assertSame('gzip, deflate', $request->getHeader('Accept-Encoding'));
        $this->assertNull($request->getHeader('Foo-Bar'));
    }
}
