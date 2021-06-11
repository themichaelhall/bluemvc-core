<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Base\ActionResults;

use BlueMvc\Core\Http\Method;
use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Tests\Helpers\TestActionResults\BasicTestActionResult;
use BlueMvc\Core\Tests\Helpers\TestApplications\BasicTestApplication;
use BlueMvc\Core\Tests\Helpers\TestRequests\BasicTestRequest;
use BlueMvc\Core\Tests\Helpers\TestResponses\BasicTestResponse;
use DataTypes\Net\Url;
use DataTypes\System\FilePath;
use PHPUnit\Framework\TestCase;

/**
 * Test AbstractActionResult (via derived base class).
 */
class AbstractActionResultTest extends TestCase
{
    /**
     * Test action result.
     */
    public function testActionResult()
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('https://www.example.com/foo/bar'), new Method('GET'));
        $response = new BasicTestResponse();
        $actionResult = new BasicTestActionResult('This page is gone!', new StatusCode(StatusCode::GONE));
        $actionResult->updateResponse($application, $request, $response);

        self::assertSame(410, $response->getStatusCode()->getCode());
        self::assertSame('Gone', $response->getStatusCode()->getDescription());
        self::assertSame('This page is gone!', $response->getContent());
    }
}
