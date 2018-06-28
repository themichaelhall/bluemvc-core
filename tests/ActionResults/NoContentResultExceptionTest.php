<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\ActionResults;

use BlueMvc\Core\ActionResults\NoContentResultException;
use BlueMvc\Core\Http\Method;
use BlueMvc\Core\Tests\Helpers\TestApplications\BasicTestApplication;
use BlueMvc\Core\Tests\Helpers\TestRequests\BasicTestRequest;
use BlueMvc\Core\Tests\Helpers\TestResponses\BasicTestResponse;
use DataTypes\FilePath;
use DataTypes\Url;
use PHPUnit\Framework\TestCase;

/**
 * Test NoContentResultException class.
 */
class NoContentResultExceptionTest extends TestCase
{
    /**
     * Test default constructor.
     */
    public function testDefaultConstructor()
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('https://www.example.com/foo/bar'), new Method('GET'));
        $response = new BasicTestResponse();
        $actionResultException = new NoContentResultException();
        $actionResultException->getActionResult()->updateResponse($application, $request, $response);

        self::assertSame(204, $response->getStatusCode()->getCode());
        self::assertSame('No Content', $response->getStatusCode()->getDescription());
        self::assertSame('', $response->getContent());
    }
}
