<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\ActionResults;

use BlueMvc\Core\ActionResults\NotModifiedResult;
use BlueMvc\Core\Http\Method;
use BlueMvc\Core\Tests\Helpers\TestApplications\BasicTestApplication;
use BlueMvc\Core\Tests\Helpers\TestRequests\BasicTestRequest;
use BlueMvc\Core\Tests\Helpers\TestResponses\BasicTestResponse;
use DataTypes\FilePath;
use DataTypes\Url;
use PHPUnit\Framework\TestCase;

/**
 * Test NotModifiedResult class.
 */
class NotModifiedResultTest extends TestCase
{
    /**
     * Test default constructor.
     */
    public function testDefaultConstructor()
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('https://www.example.com/foo/bar'), new Method('GET'));
        $response = new BasicTestResponse();
        $actionResult = new NotModifiedResult();
        $actionResult->updateResponse($application, $request, $response);

        self::assertSame(304, $response->getStatusCode()->getCode());
        self::assertSame('Not Modified', $response->getStatusCode()->getDescription());
        self::assertSame('', $response->getContent());
    }
}
