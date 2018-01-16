<?php

namespace BlueMvc\Core\Tests;

use BlueMvc\Core\Http\Method;
use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Route;
use BlueMvc\Core\Tests\Helpers\TestApplications\BasicTestApplication;
use BlueMvc\Core\Tests\Helpers\TestControllers\ActionResultTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\BasicTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\ErrorTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\ExceptionTestController;
use BlueMvc\Core\Tests\Helpers\TestRequests\BasicTestRequest;
use BlueMvc\Core\Tests\Helpers\TestResponses\BasicTestResponse;
use BlueMvc\Core\Tests\Helpers\TestViewRenderers\BasicTestViewRenderer;
use DataTypes\FilePath;
use DataTypes\Url;
use PHPUnit\Framework\TestCase;

/**
 * Test error handling.
 */
class ErrorHandlingTest extends TestCase
{
    /**
     * Test server error in debug mode.
     */
    public function testServerErrorInDebugMode()
    {
        $this->application->setDebug(true);
        $request = new BasicTestRequest(Url::parse('https://www.example.com/exception/'), new Method('GET'));
        $response = new BasicTestResponse();
        $this->application->run($request, $response);

        self::assertContains('<title>Exception was thrown.</title>', $response->getContent());
        self::assertSame(StatusCode::INTERNAL_SERVER_ERROR, $response->getStatusCode()->getCode());
    }

    /**
     * Test server error in release mode.
     */
    public function testServerErrorInReleaseMode()
    {
        $this->application->setDebug(false);
        $request = new BasicTestRequest(Url::parse('https://www.example.com/exception/'), new Method('GET'));
        $response = new BasicTestResponse();
        $this->application->run($request, $response);

        self::assertSame('', $response->getContent());
        self::assertSame(StatusCode::INTERNAL_SERVER_ERROR, $response->getStatusCode()->getCode());
    }

    /**
     * Test server error with error controller set.
     */
    public function testServerErrorWithErrorControllerSet()
    {
        $this->application->setErrorControllerClass(ErrorTestController::class);
        $request = new BasicTestRequest(Url::parse('https://www.example.com/exception/'), new Method('GET'));
        $response = new BasicTestResponse();
        $this->application->run($request, $response);

        self::assertSame('<html><body><h1>Request Failed: Error: 500, Exception: LogicException, ExceptionMessage: Exception was thrown.</h1></body></html>', $response->getContent());
        self::assertSame(StatusCode::INTERNAL_SERVER_ERROR, $response->getStatusCode()->getCode());
    }

    /**
     * Test server error with error controller returning null.
     */
    public function testServerErrorWithErrorControllerReturningNull()
    {
        $this->application->setDebug(true);
        $this->application->setErrorControllerClass(ErrorTestController::class);
        $request = new BasicTestRequest(Url::parse('https://www.example.com/exception/domainException'), new Method('GET'));
        $response = new BasicTestResponse();
        $this->application->run($request, $response);

        self::assertContains('<title>DomainException was thrown.</title>', $response->getContent());
        self::assertSame(StatusCode::INTERNAL_SERVER_ERROR, $response->getStatusCode()->getCode());
    }

    /**
     * Test not found error with error controller set.
     */
    public function testNotFoundErrorWithErrorControllerSet()
    {
        $this->application->setErrorControllerClass(ErrorTestController::class);
        $request = new BasicTestRequest(Url::parse('https://www.example.com/non-existing'), new Method('GET'));
        $response = new BasicTestResponse();
        $this->application->run($request, $response);

        self::assertSame('<html><body><h1>Request Failed: Error: 404</h1></body></html>', $response->getContent());
        self::assertSame(StatusCode::NOT_FOUND, $response->getStatusCode()->getCode());
    }

    /**
     * Test not found error with error controller set 2.
     */
    public function testNotFoundErrorWithErrorControllerSet2()
    {
        $this->application->setErrorControllerClass(ErrorTestController::class);
        $request = new BasicTestRequest(Url::parse('https://www.example.com/actionresult/notfound'), new Method('GET'));
        $response = new BasicTestResponse();
        $this->application->run($request, $response);

        self::assertSame('<html><body><h1>Request Failed: Error: 404</h1></body></html>', $response->getContent());
        self::assertSame(StatusCode::NOT_FOUND, $response->getStatusCode()->getCode());
    }

    /**
     * Test normal page with error controller set.
     */
    public function testNormalPageWithErrorControllerSet()
    {
        $this->application->setErrorControllerClass(ErrorTestController::class);
        $request = new BasicTestRequest(Url::parse('https://www.example.com/'), new Method('GET'));
        $response = new BasicTestResponse();
        $this->application->run($request, $response);

        self::assertSame('Hello World!', $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test error controller throwing exception in non-debug mode.
     */
    public function testErrorControllerThrowingExceptionInNonDebugMode()
    {
        $this->application->setErrorControllerClass(ErrorTestController::class);
        $request = new BasicTestRequest(Url::parse('https://www.example.com/actionresult/forbidden'), new Method('GET'));
        $response = new BasicTestResponse();
        $this->application->run($request, $response);

        self::assertSame('', $response->getContent());
        self::assertSame(StatusCode::INTERNAL_SERVER_ERROR, $response->getStatusCode()->getCode());
    }

    /**
     * Test error controller throwing exception in debug mode.
     */
    public function testErrorControllerThrowingExceptionInDebugMode()
    {
        $this->application->setDebug(true);
        $this->application->setErrorControllerClass(ErrorTestController::class);
        $request = new BasicTestRequest(Url::parse('https://www.example.com/actionresult/forbidden'), new Method('GET'));
        $response = new BasicTestResponse();
        $this->application->run($request, $response);

        self::assertContains('RuntimeException', $response->getContent());
        self::assertContains('Exception thrown from 403 action.', $response->getContent());
        self::assertSame(StatusCode::INTERNAL_SERVER_ERROR, $response->getStatusCode()->getCode());
    }

    /**
     * Set up.
     */
    public function setUp()
    {
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
    public function tearDown()
    {
        $this->application = null;
    }

    /**
     * @var BasicTestApplication My application.
     */
    private $application;
}
