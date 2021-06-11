<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\ActionResults;

use BlueMvc\Core\ActionResults\BadRequestResultException;
use BlueMvc\Core\Http\Method;
use BlueMvc\Core\Tests\Helpers\TestApplications\BasicTestApplication;
use BlueMvc\Core\Tests\Helpers\TestRequests\BasicTestRequest;
use BlueMvc\Core\Tests\Helpers\TestResponses\BasicTestResponse;
use DataTypes\Net\Url;
use DataTypes\System\FilePath;
use PHPUnit\Framework\TestCase;

/**
 * Test BadRequestResultException class.
 */
class BadRequestResultExceptionTest extends TestCase
{
    /**
     * Test default constructor.
     */
    public function testDefaultConstructor()
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('https://www.example.com/foo/bar'), new Method('GET'));
        $response = new BasicTestResponse();
        $actionResultException = new BadRequestResultException();
        $actionResultException->getActionResult()->updateResponse($application, $request, $response);

        self::assertSame(400, $response->getStatusCode()->getCode());
        self::assertSame('Bad Request', $response->getStatusCode()->getDescription());
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
        $actionResultException = new BadRequestResultException('The request was not valid.');
        $actionResultException->getActionResult()->updateResponse($application, $request, $response);

        self::assertSame(400, $response->getStatusCode()->getCode());
        self::assertSame('Bad Request', $response->getStatusCode()->getDescription());
        self::assertSame('The request was not valid.', $response->getContent());
    }
}
