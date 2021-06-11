<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\ActionResults;

use BlueMvc\Core\ActionResults\UnauthorizedResultException;
use BlueMvc\Core\Http\Method;
use BlueMvc\Core\Tests\Helpers\TestApplications\BasicTestApplication;
use BlueMvc\Core\Tests\Helpers\TestRequests\BasicTestRequest;
use BlueMvc\Core\Tests\Helpers\TestResponses\BasicTestResponse;
use DataTypes\Net\Url;
use DataTypes\System\FilePath;
use PHPUnit\Framework\TestCase;

/**
 * Test UnauthorizedResult class.
 */
class UnauthorizedResultExceptionTest extends TestCase
{
    /**
     * Test default constructor.
     */
    public function testDefaultConstructor()
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('https://www.example.com/foo/bar'), new Method('GET'));
        $response = new BasicTestResponse();
        $actionResultException = new UnauthorizedResultException();
        $actionResultException->getActionResult()->updateResponse($application, $request, $response);

        self::assertSame(401, $response->getStatusCode()->getCode());
        self::assertSame('Unauthorized', $response->getStatusCode()->getDescription());
        self::assertSame(['WWW-Authenticate' => 'Basic'], iterator_to_array($response->getHeaders()));
        self::assertSame('', $response->getContent());
    }

    /**
     * Test with WWW-Authenticate header.
     */
    public function testWithWWWAuthenticateHeader()
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('https://www.example.com/foo/bar'), new Method('GET'));
        $response = new BasicTestResponse();
        $actionResultException = new UnauthorizedResultException('Foo Bar Baz');
        $actionResultException->getActionResult()->updateResponse($application, $request, $response);

        self::assertSame(401, $response->getStatusCode()->getCode());
        self::assertSame('Unauthorized', $response->getStatusCode()->getDescription());
        self::assertSame(['WWW-Authenticate' => 'Foo Bar Baz'], iterator_to_array($response->getHeaders()));
        self::assertSame('', $response->getContent());
    }
}
