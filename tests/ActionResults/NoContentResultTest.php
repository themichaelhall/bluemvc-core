<?php

namespace BlueMvc\Core\Tests\ActionResults;

use BlueMvc\Core\ActionResults\NoContentResult;
use BlueMvc\Core\Request;
use BlueMvc\Core\Response;
use BlueMvc\Core\Tests\Helpers\TestApplications\BasicTestApplication;
use DataTypes\FilePath;

/**
 * Test NoContentResult class.
 */
class NoContentResultTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test default constructor.
     */
    public function testDefaultConstructor()
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new Request(
            [
                'HTTP_HOST'      => 'www.domain.com',
                'REQUEST_URI'    => '/foo/bar',
                'REQUEST_METHOD' => 'GET',
            ]
        );
        $response = new Response();
        $actionResult = new NoContentResult();
        $actionResult->updateResponse($application, $request, $response);

        self::assertSame(204, $response->getStatusCode()->getCode());
        self::assertSame('No Content', $response->getStatusCode()->getDescription());
        self::assertSame('', $response->getContent());
    }
}
