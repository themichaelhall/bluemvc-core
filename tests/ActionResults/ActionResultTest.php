<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\ActionResults;

use BlueMvc\Core\ActionResults\ActionResult;
use BlueMvc\Core\Http\Method;
use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Tests\Helpers\TestApplications\BasicTestApplication;
use BlueMvc\Core\Tests\Helpers\TestRequests\BasicTestRequest;
use BlueMvc\Core\Tests\Helpers\TestResponses\BasicTestResponse;
use DataTypes\FilePath;
use DataTypes\Url;
use PHPUnit\Framework\TestCase;

/**
 * Test ActionResult class.
 */
class ActionResultTest extends TestCase
{
    /**
     * Test action result.
     */
    public function testActionResult()
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('https://www.example.com/foo/bar'), new Method('GET'));
        $response = new BasicTestResponse();
        $actionResult = new ActionResult('This page is gone!', new StatusCode(StatusCode::GONE));
        $actionResult->updateResponse($application, $request, $response);

        self::assertSame(410, $response->getStatusCode()->getCode());
        self::assertSame('Gone', $response->getStatusCode()->getDescription());
        self::assertSame('This page is gone!', $response->getContent());
    }
}
