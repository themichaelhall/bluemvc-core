<?php

use BlueMvc\Core\Application;
use BlueMvc\Core\Exceptions\ViewFileNotFoundException;
use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Request;
use BlueMvc\Core\Response;
use BlueMvc\Core\Route;
use DataTypes\FilePath;

require_once __DIR__ . '/Helpers/Fakes/FakeHeaders.php';
require_once __DIR__ . '/Helpers/TestApplications/BasicTestApplication.php';
require_once __DIR__ . '/Helpers/TestControllers/BasicTestController.php';
require_once __DIR__ . '/Helpers/TestControllers/ViewTestController.php';
require_once __DIR__ . '/Helpers/TestViewRenderers/BasicTestViewRenderer.php';

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
     * Test get view index page.
     */
    public function testGetViewIndexPage()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/view/', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        $this->assertSame('<html><body><h1>Index</h1></body></html>', $responseOutput);
        $this->assertSame('<html><body><h1>Index</h1></body></html>', $response->getContent());
        $this->assertSame(['HTTP/1.1 200 OK'], FakeHeaders::get());
        $this->assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test get view with model page.
     */
    public function testGetViewWithModelPage()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/view/withmodel', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        $this->assertSame('<html><body><h1>With model</h1><p>This is the model.</p></body></html>', $responseOutput);
        $this->assertSame('<html><body><h1>With model</h1><p>This is the model.</p></body></html>', $response->getContent());
        $this->assertSame(['HTTP/1.1 200 OK'], FakeHeaders::get());
        $this->assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test get view with view data page.
     */
    public function testGetViewWithViewDataPage()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/view/withviewdata', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        $this->assertSame('<html><body><h1>With model and view data</h1><p>This is the model.</p><i>This is the view data.</i></body></html>', $responseOutput);
        $this->assertSame('<html><body><h1>With model and view data</h1><p>This is the model.</p><i>This is the view data.</i></body></html>', $response->getContent());
        $this->assertSame(['HTTP/1.1 200 OK'], FakeHeaders::get());
        $this->assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test get view with non-existing view file page.
     */
    public function testGetViewWithNonExistingViewFilePage()
    {
        $DS = DIRECTORY_SEPARATOR;
        $exceptionMessage = '';

        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/view/withnoviewfile', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        ob_start();

        try {
            $this->application->run($request, $response);
        } catch (ViewFileNotFoundException $e) {
            $exceptionMessage = $e->getMessage();
        }

        ob_end_clean();

        $this->assertSame('Could not find view file "' . $this->application->getViewPath() . 'ViewTest' . $DS . 'withnoviewfile.view"', $exceptionMessage);
    }

    /**
     * Set up.
     */
    public function setUp()
    {
        FakeHeaders::enable();
        $this->application = new BasicTestApplication(FilePath::parse(__DIR__ . DIRECTORY_SEPARATOR));

        $this->application->setViewPath(FilePath::parse('Helpers' . DIRECTORY_SEPARATOR . 'TestViews' . DIRECTORY_SEPARATOR));
        $this->application->addViewRenderer(new BasicTestViewRenderer());

        $this->application->addRoute(new Route('', BasicTestController::class));
        $this->application->addRoute(new Route('view', ViewTestController::class));
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
