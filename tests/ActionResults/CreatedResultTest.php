<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\ActionResults;

use BlueMvc\Core\ActionResults\CreatedResult;
use BlueMvc\Core\Http\Method;
use BlueMvc\Core\Tests\Helpers\TestApplications\BasicTestApplication;
use BlueMvc\Core\Tests\Helpers\TestRequests\BasicTestRequest;
use BlueMvc\Core\Tests\Helpers\TestResponses\BasicTestResponse;
use DataTypes\Net\Exceptions\UrlInvalidArgumentException;
use DataTypes\Net\Exceptions\UrlPathLogicException;
use DataTypes\Net\Url;
use DataTypes\System\FilePath;
use PHPUnit\Framework\TestCase;

/**
 * Test CreatedResult class.
 */
class CreatedResultTest extends TestCase
{
    /**
     * Test with absolute url.
     */
    public function testWithAbsoluteUrl()
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('https://www.example.com/foo/bar'), new Method('GET'));
        $response = new BasicTestResponse();
        $actionResult = new CreatedResult('https://domain.org/baz?query');
        $actionResult->updateResponse($application, $request, $response);

        self::assertSame(201, $response->getStatusCode()->getCode());
        self::assertSame('Created', $response->getStatusCode()->getDescription());
        self::assertSame('', $response->getContent());
        self::assertSame('https://domain.org/baz?query', $response->getHeader('Location'));
    }

    /**
     * Test with relative url.
     */
    public function testWithRelativeUrl()
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('https://www.example.com/foo/bar'), new Method('GET'));
        $response = new BasicTestResponse();
        $actionResult = new CreatedResult('../baz');
        $actionResult->updateResponse($application, $request, $response);

        self::assertSame(201, $response->getStatusCode()->getCode());
        self::assertSame('Created', $response->getStatusCode()->getDescription());
        self::assertSame('', $response->getContent());
        self::assertSame('https://www.example.com/baz', $response->getHeader('Location'));
    }

    /**
     * Test with empty url.
     */
    public function testWithEmptyUrl()
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('https://www.example.com/foo/bar?baz'), new Method('GET'));
        $response = new BasicTestResponse();
        $actionResult = new CreatedResult();
        $actionResult->updateResponse($application, $request, $response);

        self::assertSame(201, $response->getStatusCode()->getCode());
        self::assertSame('Created', $response->getStatusCode()->getDescription());
        self::assertSame('', $response->getContent());
        self::assertSame('https://www.example.com/foo/bar?baz', $response->getHeader('Location'));
    }

    /**
     * Test with invalid url parameter.
     */
    public function testWithInvalidUrlParameter()
    {
        self::expectException(UrlInvalidArgumentException::class);
        self::expectExceptionMessage('Url "foobar://localhost/" is invalid: Scheme "foobar" is invalid: Scheme must be "http" or "https".');

        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('https://www.example.com/foo/bar'), new Method('GET'));
        $response = new BasicTestResponse();
        $actionResult = new CreatedResult('foobar://localhost/');
        $actionResult->updateResponse($application, $request, $response);
    }

    /**
     * Test with invalid relative url parameter.
     */
    public function testWithInvalidRelativeUrlParameter()
    {
        self::expectException(UrlPathLogicException::class);
        self::expectExceptionMessage('Url path "/foo/bar" can not be combined with url path "../../baz": Absolute path is above root level.');

        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('https://www.example.com/foo/bar'), new Method('GET'));
        $response = new BasicTestResponse();
        $actionResult = new CreatedResult('../../baz');
        $actionResult->updateResponse($application, $request, $response);
    }
}
