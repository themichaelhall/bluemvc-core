<?php

namespace BlueMvc\Core\Tests\ActionResults;

use BlueMvc\Core\ActionResults\MethodNotAllowedResult;
use BlueMvc\Core\Application;
use BlueMvc\Core\Request;
use BlueMvc\Core\Response;

/**
 * Test MethodNotAllowed class.
 */
class MethodNotAllowedTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test default constructor.
     */
    public function testDefaultConstructor()
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
        $actionResult = new MethodNotAllowedResult();
        $actionResult->updateResponse($application, $request, $response);

        self::assertSame(405, $response->getStatusCode()->getCode());
        self::assertSame('Method Not Allowed', $response->getStatusCode()->getDescription());
        self::assertSame('', $response->getContent());
    }

    /**
     * Test with content.
     */
    public function testWithContent()
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
        $actionResult = new MethodNotAllowedResult('Method is not allowed.');
        $actionResult->updateResponse($application, $request, $response);

        self::assertSame(405, $response->getStatusCode()->getCode());
        self::assertSame('Method Not Allowed', $response->getStatusCode()->getDescription());
        self::assertSame('Method is not allowed.', $response->getContent());
    }

    /**
     * Test with invalid content parameter type.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $content parameter is not a string.
     */
    public function testWithInvalidContentParameterType()
    {
        new MethodNotAllowedResult(null);
    }
}
