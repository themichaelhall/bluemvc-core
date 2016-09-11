<?php

use BlueMvc\Core\Application;
use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Request;
use BlueMvc\Core\Response;
use BlueMvc\Core\Route;

require_once __DIR__ . '/Helpers/Fakes/FakeHeaders.php';
require_once __DIR__ . '/Helpers/TestControllers/BasicTestController.php';


/**
 * Test basic routing for a application.
 */
class BasicRoutingTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test get index page.
     */
    public function testGetIndexPage()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        $this->assertSame('Hello World!', $responseOutput);
        $this->assertSame('Hello World!', $response->getContent());
        $this->assertSame(['HTTP/1.1 200 OK'], FakeHeaders::get());
        $this->assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test get non-existing action.
     */
    public function testGetNonExistingAction()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/notfound', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        $this->assertSame('', $responseOutput);
        $this->assertSame('', $response->getContent());
        $this->assertSame(['HTTP/1.1 404 Not Found'], FakeHeaders::get());
        $this->assertSame(StatusCode::NOT_FOUND, $response->getStatusCode()->getCode());
    }

    /**
     * Test get non-existing controller.
     */
    public function testGetNonExistingController()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/non-existing-controller/action', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        $this->assertSame('', $responseOutput);
        $this->assertSame('', $response->getContent());
        $this->assertSame(['HTTP/1.1 404 Not Found'], FakeHeaders::get());
        $this->assertSame(StatusCode::NOT_FOUND, $response->getStatusCode()->getCode());
    }

    /**
     * Test get server error page.
     */
    public function testGetServerErrorPage()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/serverError', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        $this->assertSame('Server Error', $responseOutput);
        $this->assertSame('Server Error', $response->getContent());
        $this->assertSame(['HTTP/1.1 500 Internal Server Error'], FakeHeaders::get());
        $this->assertSame(StatusCode::INTERNAL_SERVER_ERROR, $response->getStatusCode()->getCode());
    }

    /**
     * Set up.
     */
    public function setUp()
    {
        FakeHeaders::enable();
        $this->application = new Application(['DOCUMENT_ROOT' => '/var/www/']);
        $this->application->addRoute(new Route('', BasicTestController::class));
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
     * @var Application My application.
     */
    private $application;
}
