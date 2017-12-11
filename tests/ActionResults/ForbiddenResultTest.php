<?php

namespace BlueMvc\Core\Tests\ActionResults;

use BlueMvc\Core\ActionResults\ForbiddenResult;
use BlueMvc\Core\Http\Method;
use BlueMvc\Core\Tests\Helpers\TestApplications\BasicTestApplication;
use BlueMvc\Core\Tests\Helpers\TestRequests\BasicTestRequest;
use BlueMvc\Core\Tests\Helpers\TestResponses\BasicTestResponse;
use DataTypes\FilePath;
use DataTypes\Url;

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
        $request = new BasicTestRequest(Url::parse('https://www.example.com/foo/bar'), new Method('GET'));
        $response = new BasicTestResponse();
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
        $request = new BasicTestRequest(Url::parse('https://www.example.com/foo/bar'), new Method('GET'));
        $response = new BasicTestResponse();
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
