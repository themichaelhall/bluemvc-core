<?php

use BlueMvc\Core\ActionResults\NotFoundResult;
use BlueMvc\Core\Application;
use BlueMvc\Core\Request;
use BlueMvc\Core\Response;

/**
 * Test NotFoundResult class.
 */
class NotFoundResultTest extends PHPUnit_Framework_TestCase
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
        $actionResult = new NotFoundResult();
        $actionResult->updateResponse($application, $request, $response);

        $this->assertSame(404, $response->getStatusCode()->getCode());
        $this->assertSame('Not Found', $response->getStatusCode()->getDescription());
        $this->assertSame('', $response->getContent());
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
        $actionResult = new NotFoundResult('The page was not found.');
        $actionResult->updateResponse($application, $request, $response);

        $this->assertSame(404, $response->getStatusCode()->getCode());
        $this->assertSame('Not Found', $response->getStatusCode()->getDescription());
        $this->assertSame('The page was not found.', $response->getContent());
    }

    /**
     * Test with invalid content parameter type.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $content parameter is not a string.
     */
    public function testWithInvalidContentParameterType()
    {
        new NotFoundResult(null);
    }
}
