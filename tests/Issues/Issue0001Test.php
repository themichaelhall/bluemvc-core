<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Issues;

use BlueMvc\Core\Http\Method;
use BlueMvc\Core\Route;
use BlueMvc\Core\Tests\Helpers\TestApplications\BasicTestApplication;
use BlueMvc\Core\Tests\Helpers\TestControllers\MultiLevelTestController;
use BlueMvc\Core\Tests\Helpers\TestRequests\BasicTestRequest;
use BlueMvc\Core\Tests\Helpers\TestResponses\BasicTestResponse;
use DataTypes\Net\Url;
use DataTypes\System\FilePath;
use PHPUnit\Framework\TestCase;

/**
 * Test Issue #1.
 */
class Issue0001Test extends TestCase
{
    /**
     * Test request ending with zero.
     */
    public function testRequestEndingWithZero()
    {
        $application = new BasicTestApplication(FilePath::parse(__DIR__ . DIRECTORY_SEPARATOR));
        $application->addRoute(new Route('test', MultiLevelTestController::class));
        $request = new BasicTestRequest(Url::parse('https://example.com/test/foo/0'), new Method('GET'));
        $response = new BasicTestResponse();
        $application->run($request, $response);

        self::assertSame('FooAction: Foo=[0]', $response->getContent());
    }

    /**
     * Test request ending with one.
     */
    public function testRequestEndingWithOne()
    {
        $application = new BasicTestApplication(FilePath::parse(__DIR__ . DIRECTORY_SEPARATOR));
        $application->addRoute(new Route('test', MultiLevelTestController::class));
        $request = new BasicTestRequest(Url::parse('https://example.com/test/foo/1'), new Method('GET'));
        $response = new BasicTestResponse();
        $application->run($request, $response);

        self::assertSame('FooAction: Foo=[1]', $response->getContent());
    }

    /**
     * Test route matches.
     */
    public function testRouteMatches()
    {
        $route = new Route('test', MultiLevelTestController::class);

        self::assertSame(['0'], $route->matches(new BasicTestRequest(Url::parse('https://example.com/test/foo/0'), new Method('GET')))->getParameters());
        self::assertSame(['1'], $route->matches(new BasicTestRequest(Url::parse('https://example.com/test/foo/1'), new Method('GET')))->getParameters());
        self::assertSame([''], $route->matches(new BasicTestRequest(Url::parse('https://example.com/test/foo/'), new Method('GET')))->getParameters());
    }
}
