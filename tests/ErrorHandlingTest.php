<?php

use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Request;
use BlueMvc\Core\Response;
use BlueMvc\Core\Route;
use DataTypes\FilePath;

require_once __DIR__ . '/Helpers/TestApplications/BasicTestApplication.php';
require_once __DIR__ . '/Helpers/TestControllers/ExceptionTestController.php';
require_once __DIR__ . '/Helpers/Fakes/FakeHeaders.php';

/**
 * Test error handling.
 */
class ErrorHandlingTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test server error in debug mode.
     */
    public function testServerErrorInDebugMode()
    {
        $this->application->setDebug(true);
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/exception/', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        $this->assertContains('LogicException', $responseOutput);
        $this->assertContains('Exception was thrown.', $responseOutput);
        $this->assertContains('LogicException', $response->getContent());
        $this->assertContains('Exception was thrown.', $response->getContent());
        $this->assertSame(['HTTP/1.1 500 Internal Server Error'], FakeHeaders::get());
        $this->assertSame(StatusCode::INTERNAL_SERVER_ERROR, $response->getStatusCode()->getCode());
    }

    /**
     * Test server error in release mode.
     */
    public function testServerErrorInReleaseMode()
    {
        $this->application->setDebug(false);
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/exception/', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        $this->assertSame('', $responseOutput);
        $this->assertSame('', $response->getContent());
        $this->assertSame(['HTTP/1.1 500 Internal Server Error'], FakeHeaders::get());
        $this->assertSame(StatusCode::INTERNAL_SERVER_ERROR, $response->getStatusCode()->getCode());
    }

    /**
     * Set up.
     */
    public function setUp()
    {
        FakeHeaders::enable();
        $this->application = new BasicTestApplication(FilePath::parse(__DIR__ . DIRECTORY_SEPARATOR));
        $this->application->addRoute(new Route('exception', ExceptionTestController::class));
    }

    /**
     * Tear down.
     */
    public function tearDown()
    {
        FakeHeaders::disable();
        $this->application = null;
    }

    /**
     * @var BasicTestApplication My application.
     */
    private $application;
}
