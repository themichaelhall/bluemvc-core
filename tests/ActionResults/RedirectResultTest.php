<?php

use BlueMvc\Core\ActionResults\RedirectResult;
use BlueMvc\Core\Application;
use BlueMvc\Core\Request;
use BlueMvc\Core\Response;

/**
 * Test RedirectResult class.
 */
class RedirectResultTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test with absolute url.
     */
    public function testWithAbsoluteUrl()
    {
        $application = new Application(
            [
                'DOCUMENT_ROOT' => '/var/www/',
            ]
        );
        $request = new Request(
            [
                'HTTP_HOST'      => 'www.domain.com',
                'REQUEST_URI'    => '/foo/bar',
                'REQUEST_METHOD' => 'GET',
            ]
        );
        $response = new Response($request);
        $actionResult = new RedirectResult('https://domain.org/baz?query');
        $actionResult->updateResponse($application, $request, $response);

        $this->assertSame(302, $response->getStatusCode()->getCode());
        $this->assertSame('Found', $response->getStatusCode()->getDescription());
        $this->assertSame('', $response->getContent());
        $this->assertSame('https://domain.org/baz?query', $response->getHeader('Location'));
    }

    /**
     * Test with relative url.
     */
    public function testWithRelativeUrl()
    {
        $application = new Application(
            [
                'DOCUMENT_ROOT' => '/var/www/',
            ]
        );
        $request = new Request(
            [
                'HTTP_HOST'      => 'www.domain.com',
                'REQUEST_URI'    => '/foo/bar',
                'REQUEST_METHOD' => 'GET',
            ]
        );
        $response = new Response($request);
        $actionResult = new RedirectResult('../baz');
        $actionResult->updateResponse($application, $request, $response);

        $this->assertSame(302, $response->getStatusCode()->getCode());
        $this->assertSame('Found', $response->getStatusCode()->getDescription());
        $this->assertSame('', $response->getContent());
        $this->assertSame('http://www.domain.com/baz', $response->getHeader('Location'));
    }

    /**
     * Test with invalid url parameter.
     *
     * @expectedException \DataTypes\Exceptions\UrlInvalidArgumentException
     * @expectedExceptionMessage Url "foobar://localhost/" is invalid: Scheme "foobar" is invalid: Scheme must be "http" or "https".
     */
    public function testWithInvalidUrlParameter()
    {
        $application = new Application(
            [
                'DOCUMENT_ROOT' => '/var/www/',
            ]
        );
        $request = new Request(
            [
                'HTTP_HOST'      => 'www.domain.com',
                'REQUEST_URI'    => '/foo/bar',
                'REQUEST_METHOD' => 'GET',
            ]
        );

        $response = new Response($request);
        $actionResult = new RedirectResult('foobar://localhost/');
        $actionResult->updateResponse($application, $request, $response);
    }

    /**
     * Test with invalid relative url parameter.
     *
     * @expectedException \DataTypes\Exceptions\UrlPathLogicException
     * @expectedExceptionMessage Url path "/foo/bar" can not be combined with url path "../../baz": Absolute path is above root level.
     */
    public function testWithInvalidRelativeUrlParameter()
    {
        $application = new Application(
            [
                'DOCUMENT_ROOT' => '/var/www/',
            ]
        );
        $request = new Request(
            [
                'HTTP_HOST'      => 'www.domain.com',
                'REQUEST_URI'    => '/foo/bar',
                'REQUEST_METHOD' => 'GET',
            ]
        );

        $response = new Response($request);
        $actionResult = new RedirectResult('../../baz');
        $actionResult->updateResponse($application, $request, $response);
    }

    /**
     * Test with invalid url parameter type.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $url parameter is not a string.
     */
    public function testWithInvalidUrlParameterType()
    {
        new RedirectResult(-1);
    }
}
