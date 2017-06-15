<?php

namespace BlueMvc\Core\Tests\ActionResults;

use BlueMvc\Core\ActionResults\JsonResult;
use BlueMvc\Core\Application;
use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Request;
use BlueMvc\Core\Response;

/**
 * Test JsonResult class.
 */
class JsonResultTest extends \PHPUnit_Framework_TestCase
{
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
        $actionResult = new JsonResult(['Foo' => 'Bar']);
        $actionResult->updateResponse($application, $request, $response);

        self::assertSame(200, $response->getStatusCode()->getCode());
        self::assertSame('OK', $response->getStatusCode()->getDescription());
        self::assertSame('{"Foo":"Bar"}', $response->getContent());
        self::assertSame('application/json', $response->getHeader('Content-Type'));
    }

    /**
     * Test with status code.
     */
    public function testWithStatusCode()
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
        $actionResult = new JsonResult(['Error' => 'Not Found'], new StatusCode(StatusCode::NOT_FOUND));
        $actionResult->updateResponse($application, $request, $response);

        self::assertSame(404, $response->getStatusCode()->getCode());
        self::assertSame('Not Found', $response->getStatusCode()->getDescription());
        self::assertSame('{"Error":"Not Found"}', $response->getContent());
        self::assertSame('application/json', $response->getHeader('Content-Type'));
    }
}
