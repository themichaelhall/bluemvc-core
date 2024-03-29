<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests;

use BlueMvc\Core\Http\Method;
use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Route;
use BlueMvc\Core\Tests\Helpers\TestApplications\BasicTestApplication;
use BlueMvc\Core\Tests\Helpers\TestControllers\ActionResultTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\BasicTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\ErrorTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\ErrorTraitTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\ExceptionTestController;
use BlueMvc\Core\Tests\Helpers\TestRequests\BasicTestRequest;
use BlueMvc\Core\Tests\Helpers\TestResponses\BasicTestResponse;
use BlueMvc\Core\Tests\Helpers\TestViewRenderers\BasicTestViewRenderer;
use DataTypes\Net\Url;
use DataTypes\Net\UrlPath;
use DataTypes\System\FilePath;
use PHPUnit\Framework\TestCase;

/**
 * Test error handling.
 */
class ErrorHandlingTest extends TestCase
{
    /**
     * Test error handling.
     *
     * @dataProvider errorHandlingDataProvider
     *
     * @param bool        $isDebug              If true, application is in debug mode. False otherwise.
     * @param string      $path                 The path.
     * @param string|null $errorControllerClass The error controller class or null if no error controller.
     * @param array       $expectedHeaders      The expected headers.
     * @param string      $expectedContent      The expected content.
     * @param int         $expectedStatusCode   The expected status code.
     */
    public function testErrorHandling(bool $isDebug, string $path, ?string $errorControllerClass, array $expectedHeaders, string $expectedContent, int $expectedStatusCode)
    {
        if ($errorControllerClass !== null) {
            $this->application->setErrorControllerClass($errorControllerClass);
        }
        $this->application->setDebug($isDebug);
        $request = new BasicTestRequest(Url::parse('https://www.example.com/')->withPath(UrlPath::parse($path)), new Method('GET'));
        $response = new BasicTestResponse();
        $this->application->run($request, $response);

        if ($expectedContent === '') {
            self::assertSame('', $response->getContent());
        } else {
            self::assertStringContainsString($expectedContent, $response->getContent());
        }

        self::assertSame($expectedHeaders, iterator_to_array($response->getHeaders()));
        self::assertSame($expectedStatusCode, $response->getStatusCode()->getCode());
    }

    /**
     * Data provider for error handling tests.
     *
     * @return array The data.
     */
    public function errorHandlingDataProvider(): array
    {
        return [
            [true, '/exception/', null, [], '<title>Exception was thrown.</title>', StatusCode::INTERNAL_SERVER_ERROR],
            [false, '/exception/', null, [], '', StatusCode::INTERNAL_SERVER_ERROR],
            [true, '/exception/', ErrorTestController::class, [], '<html><body><h1>Request Failed: Error: 500, Throwable: LogicException, ThrowableMessage: Exception was thrown.</h1></body></html>', StatusCode::INTERNAL_SERVER_ERROR],
            [false, '/exception/', ErrorTestController::class, [], '<html><body><h1>Request Failed: Error: 500, Throwable: LogicException, ThrowableMessage: Exception was thrown.</h1></body></html>', StatusCode::INTERNAL_SERVER_ERROR],
            [true, '/exception/domainException', ErrorTestController::class, [], '<title>DomainException was thrown.</title>', StatusCode::INTERNAL_SERVER_ERROR],
            [false, '/exception/domainException', ErrorTestController::class, [], '', StatusCode::INTERNAL_SERVER_ERROR],
            [true, '/exception/non-existing', ErrorTestController::class, ['X-Error-PreActionEvent' => '1', 'X-Error-PostActionEvent' => '1'], '<html><body><h1>Request Failed: Error: 404</h1></body></html>', StatusCode::NOT_FOUND],
            [false, '/exception/non-existing', ErrorTestController::class, ['X-Error-PreActionEvent' => '1', 'X-Error-PostActionEvent' => '1'], '<html><body><h1>Request Failed: Error: 404</h1></body></html>', StatusCode::NOT_FOUND],
            [true, '/actionresult/notfound', ErrorTestController::class, ['X-Error-PreActionEvent' => '1', 'X-Error-PostActionEvent' => '1'], '<html><body><h1>Request Failed: Error: 404</h1></body></html>', StatusCode::NOT_FOUND],
            [false, '/actionresult/notfound', ErrorTestController::class, ['X-Error-PreActionEvent' => '1', 'X-Error-PostActionEvent' => '1'], '<html><body><h1>Request Failed: Error: 404</h1></body></html>', StatusCode::NOT_FOUND],
            [true, '/', ErrorTestController::class, [], 'Hello World!', StatusCode::OK],
            [false, '/', ErrorTestController::class, [], 'Hello World!', StatusCode::OK],
            [true, '/actionresult/forbidden', ErrorTestController::class, [], 'Exception thrown from 403 action.', StatusCode::INTERNAL_SERVER_ERROR],
            [false, '/actionresult/forbidden', ErrorTestController::class, [], '', StatusCode::INTERNAL_SERVER_ERROR],
            [true, '/actionresult/methodNotAllowed', ErrorTestController::class, [], 'Error thrown from 405 action.', StatusCode::INTERNAL_SERVER_ERROR],
            [false, '/actionresult/methodNotAllowed', ErrorTestController::class, [], '', StatusCode::INTERNAL_SERVER_ERROR],
            [true, '/exception/', ErrorTraitTestController::class, [], '<html><body><h1>Request Failed: Error: 500, Throwable: LogicException, ThrowableMessage: Exception was thrown.</h1></body></html>', StatusCode::INTERNAL_SERVER_ERROR],
            [false, '/exception/', ErrorTraitTestController::class, [], '<html><body><h1>Request Failed: Error: 500, Throwable: LogicException, ThrowableMessage: Exception was thrown.</h1></body></html>', StatusCode::INTERNAL_SERVER_ERROR],
            [true, '/exception/domainException', ErrorTraitTestController::class, [], '<title>DomainException was thrown.</title>', StatusCode::INTERNAL_SERVER_ERROR],
            [false, '/exception/domainException', ErrorTraitTestController::class, [], '', StatusCode::INTERNAL_SERVER_ERROR],
            [true, '/exception/non-existing', ErrorTraitTestController::class, ['X-ErrorTrait-PreActionEvent' => '1', 'X-ErrorTrait-PostActionEvent' => '1'], '<html><body><h1>Request Failed: Error: 404</h1></body></html>', StatusCode::NOT_FOUND],
            [false, '/exception/non-existing', ErrorTraitTestController::class, ['X-ErrorTrait-PreActionEvent' => '1', 'X-ErrorTrait-PostActionEvent' => '1'], '<html><body><h1>Request Failed: Error: 404</h1></body></html>', StatusCode::NOT_FOUND],
            [true, '/exception/notfound', ErrorTraitTestController::class, ['X-ErrorTrait-PreActionEvent' => '1', 'X-ErrorTrait-PostActionEvent' => '1'], '<html><body><h1>Request Failed: Error: 404</h1></body></html>', StatusCode::NOT_FOUND],
            [false, '/exception/notfound', ErrorTraitTestController::class, ['X-ErrorTrait-PreActionEvent' => '1', 'X-ErrorTrait-PostActionEvent' => '1'], '<html><body><h1>Request Failed: Error: 404</h1></body></html>', StatusCode::NOT_FOUND],
            [true, '/', ErrorTraitTestController::class, [], 'Hello World!', StatusCode::OK],
            [false, '/', ErrorTraitTestController::class, [], 'Hello World!', StatusCode::OK],
            [true, '/actionresult/forbidden', ErrorTraitTestController::class, [], 'Exception thrown from 403 action.', StatusCode::INTERNAL_SERVER_ERROR],
            [false, '/actionresult/forbidden', ErrorTraitTestController::class, [], '', StatusCode::INTERNAL_SERVER_ERROR],
            [true, '/actionresult/methodNotAllowed', ErrorTraitTestController::class, [], 'Error thrown from 405 action.', StatusCode::INTERNAL_SERVER_ERROR],
            [false, '/actionresult/methodNotAllowed', ErrorTraitTestController::class, [], '', StatusCode::INTERNAL_SERVER_ERROR],
            [true, '/exception/error', null, [], '<title>Error was thrown.</title>', StatusCode::INTERNAL_SERVER_ERROR],
            [false, '/exception/error', null, [], '', StatusCode::INTERNAL_SERVER_ERROR],
            [true, '/exception/error', ErrorTestController::class, [], '<html><body><h1>Request Failed: Error: 500, Throwable: Error, ThrowableMessage: Error was thrown.</h1></body></html>', StatusCode::INTERNAL_SERVER_ERROR],
            [false, '/exception/error', ErrorTestController::class, [], '<html><body><h1>Request Failed: Error: 500, Throwable: Error, ThrowableMessage: Error was thrown.</h1></body></html>', StatusCode::INTERNAL_SERVER_ERROR],
            [true, '/exception/error', ErrorTraitTestController::class, [], '<html><body><h1>Request Failed: Error: 500, Throwable: Error, ThrowableMessage: Error was thrown.</h1></body></html>', StatusCode::INTERNAL_SERVER_ERROR],
            [false, '/exception/error', ErrorTraitTestController::class, [], '<html><body><h1>Request Failed: Error: 500, Throwable: Error, ThrowableMessage: Error was thrown.</h1></body></html>', StatusCode::INTERNAL_SERVER_ERROR],
        ];
    }

    /**
     * Set up.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->application = new BasicTestApplication(FilePath::parse(__DIR__ . DIRECTORY_SEPARATOR));
        $this->application->setViewPath(FilePath::parse('Helpers' . DIRECTORY_SEPARATOR . 'TestViews' . DIRECTORY_SEPARATOR));
        $this->application->addViewRenderer(new BasicTestViewRenderer());

        $this->application->addRoute(new Route('', BasicTestController::class));
        $this->application->addRoute(new Route('exception', ExceptionTestController::class));
        $this->application->addRoute(new Route('actionresult', ActionResultTestController::class));
    }

    /**
     * Tear down.
     */
    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @var BasicTestApplication The application.
     */
    private BasicTestApplication $application;
}
