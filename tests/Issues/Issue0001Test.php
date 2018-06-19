<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Issues;

use BlueMvc\Core\Http\Method;
use BlueMvc\Core\Route;
use BlueMvc\Core\Tests\Helpers\TestApplications\BasicTestApplication;
use BlueMvc\Core\Tests\Helpers\TestControllers\MultiLevelTestController;
use BlueMvc\Core\Tests\Helpers\TestRequests\BasicTestRequest;
use BlueMvc\Core\Tests\Helpers\TestResponses\BasicTestResponse;
use DataTypes\FilePath;
use DataTypes\Url;
use PHPUnit\Framework\TestCase;

/**
 * Test Issue #1.
 */
class Issue0001Test extends TestCase
{
    /**
     * Test request.
     */
    public function testRequest()
    {
        $application = new BasicTestApplication(FilePath::parse(__DIR__ . DIRECTORY_SEPARATOR));
        $application->addRoute(new Route('test', MultiLevelTestController::class));
        $request = new BasicTestRequest(Url::parse('https://example.com/test/foo/0'), new Method('GET'));
        $response = new BasicTestResponse();
        $application->run($request, $response);

        self::assertSame('FooAction: Foo=[]', $response->getContent());
    }
}
