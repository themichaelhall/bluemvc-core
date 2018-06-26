<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\ActionResults;

use BlueMvc\Core\ActionResults\ForbiddenResultException;
use BlueMvc\Core\Http\Method;
use BlueMvc\Core\Tests\Helpers\TestApplications\BasicTestApplication;
use BlueMvc\Core\Tests\Helpers\TestRequests\BasicTestRequest;
use BlueMvc\Core\Tests\Helpers\TestResponses\BasicTestResponse;
use DataTypes\FilePath;
use DataTypes\Url;
use PHPUnit\Framework\TestCase;

/**
 * Test ForbiddenResultException class.
 */
class ForbiddenResultExceptionTest extends TestCase
{
    /**
     * Test default constructor.
     */
    public function testDefaultConstructor()
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('https://www.example.com/foo/bar'), new Method('GET'));
        $response = new BasicTestResponse();
        $actionResultException = new ForbiddenResultException();
        $actionResultException->getActionResult()->updateResponse($application, $request, $response);

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
        $actionResultException = new ForbiddenResultException('You are forbidden to view this content.');
        $actionResultException->getActionResult()->updateResponse($application, $request, $response);

        self::assertSame(403, $response->getStatusCode()->getCode());
        self::assertSame('Forbidden', $response->getStatusCode()->getDescription());
        self::assertSame('You are forbidden to view this content.', $response->getContent());
    }
}
