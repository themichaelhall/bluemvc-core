<?php

use BlueMvc\Core\ActionResults\NoContentResult;
use BlueMvc\Core\Application;
use BlueMvc\Core\Request;
use BlueMvc\Core\Response;

/**
 * Test NoContentResult class.
 */
class NoContentResultTest extends PHPUnit_Framework_TestCase
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
        $actionResult = new NoContentResult();
        $actionResult->updateResponse($application, $request, $response);

        $this->assertSame(204, $response->getStatusCode()->getCode());
        $this->assertSame('No Content', $response->getStatusCode()->getDescription());
        $this->assertSame('', $response->getContent());
    }
}
