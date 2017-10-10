<?php

namespace BlueMvc\Core\Tests\ActionResults;

use BlueMvc\Core\ActionResults\NotModifiedResult;
use BlueMvc\Core\Application;
use BlueMvc\Core\Request;
use BlueMvc\Core\Response;

/**
 * Test NotModifiedResult class.
 */
class NotModifiedResultTest extends \PHPUnit_Framework_TestCase
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
        $response = new Response();
        $actionResult = new NotModifiedResult();
        $actionResult->updateResponse($application, $request, $response);

        self::assertSame(304, $response->getStatusCode()->getCode());
        self::assertSame('Not Modified', $response->getStatusCode()->getDescription());
        self::assertSame('', $response->getContent());
    }
}
