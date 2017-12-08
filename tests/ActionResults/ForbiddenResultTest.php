<?php

namespace BlueMvc\Core\Tests\ActionResults;

use BlueMvc\Core\ActionResults\ForbiddenResult;
use BlueMvc\Core\Request;
use BlueMvc\Core\Response;
use BlueMvc\Core\Tests\Helpers\TestApplications\BasicTestApplication;
use DataTypes\FilePath;

/**
 * Test ForbiddenResult class.
 */
class ForbiddenResultTest extends \PHPUnit_Framework_TestCase
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
        $actionResult = new ForbiddenResult();
        $actionResult->updateResponse($application, $request, $response);

        self::assertSame(403, $response->getStatusCode()->getCode());
        self::assertSame('Forbidden', $response->getStatusCode()->getDescription());
        self::assertSame('', $response->getContent());
    }

    /**
     * Test with content.
     */
    public function testWithContent()
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
        $actionResult = new ForbiddenResult('You are forbidden to view this content.');
        $actionResult->updateResponse($application, $request, $response);

        self::assertSame(403, $response->getStatusCode()->getCode());
        self::assertSame('Forbidden', $response->getStatusCode()->getDescription());
        self::assertSame('You are forbidden to view this content.', $response->getContent());
    }

    /**
     * Test with invalid content parameter type.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $content parameter is not a string.
     */
    public function testWithInvalidContentParameterType()
    {
        new ForbiddenResult(null);
    }
}
