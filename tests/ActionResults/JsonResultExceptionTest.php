<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\ActionResults;

use BlueMvc\Core\ActionResults\JsonResultException;
use BlueMvc\Core\Exceptions\InvalidActionResultContentException;
use BlueMvc\Core\Http\Method;
use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Tests\Helpers\TestApplications\BasicTestApplication;
use BlueMvc\Core\Tests\Helpers\TestClasses\JsonSerializableTestClass;
use BlueMvc\Core\Tests\Helpers\TestRequests\BasicTestRequest;
use BlueMvc\Core\Tests\Helpers\TestResponses\BasicTestResponse;
use DataTypes\Net\Url;
use DataTypes\System\FilePath;
use PHPUnit\Framework\TestCase;

/**
 * Test JsonResultException class.
 */
class JsonResultExceptionTest extends TestCase
{
    /**
     * Test with content.
     */
    public function testWithContent()
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('https://www.example.com/foo/bar'), new Method('GET'));
        $response = new BasicTestResponse();
        $actionResultException = new JsonResultException(['Foo' => 'Bar']);
        $actionResultException->getActionResult()->updateResponse($application, $request, $response);

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
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('https://www.example.com/foo/bar'), new Method('GET'));
        $response = new BasicTestResponse();
        $actionResultException = new JsonResultException(['Error' => 'Not Found'], new StatusCode(StatusCode::NOT_FOUND));
        $actionResultException->getActionResult()->updateResponse($application, $request, $response);

        self::assertSame(404, $response->getStatusCode()->getCode());
        self::assertSame('Not Found', $response->getStatusCode()->getDescription());
        self::assertSame('{"Error":"Not Found"}', $response->getContent());
        self::assertSame('application/json', $response->getHeader('Content-Type'));
    }

    /**
     * Test with JsonSerializable class.
     */
    public function testWithJsonSerializable()
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('https://www.example.com/foo/bar'), new Method('GET'));
        $response = new BasicTestResponse();
        $actionResultException = new JsonResultException(new JsonSerializableTestClass(10, 'Foo'));
        $actionResultException->getActionResult()->updateResponse($application, $request, $response);

        self::assertSame(200, $response->getStatusCode()->getCode());
        self::assertSame('OK', $response->getStatusCode()->getDescription());
        self::assertSame('{"text":"Foo"}', $response->getContent());
        self::assertSame('application/json', $response->getHeader('Content-Type'));
    }

    /**
     * Test with invalid json data class.
     */
    public function testWithInvalidJsonData()
    {
        self::expectException(InvalidActionResultContentException::class);
        self::expectExceptionMessage('Could not create JsonResult from content: Malformed UTF-8 characters, possibly incorrectly encoded');

        new JsonResultException(chr(255));
    }
}
