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
require_once __DIR__ . '/Helpers/TestControllers/DefaultActionWithViewTestController.php';
require_once __DIR__ . '/Helpers/TestControllers/ActionResultTestController.php';
require_once __DIR__ . '/Helpers/TestControllers/PreActionEventController.php';
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
     * Test get invalid controller class name 1.
     *
     * @expectedException \BlueMvc\Core\Exceptions\InvalidControllerClassException
     * @expectedExceptionMessage Controller class "BlueMvc\Core\FooBar" does not exist.
     */
    public function testGetInvalidControllerClassName1()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/invalid-controller-class-name/', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        $this->application->addRoute(new Route('invalid-controller-class-name', 'BlueMvc\\Core\\FooBar'));
        $this->application->run($request, $response);
    }

    /**
     * Test get invalid controller class name 2.
     *
     * @expectedException \BlueMvc\Core\Exceptions\InvalidControllerClassException
     * @expectedExceptionMessage Controller class "BlueMvc\Core\Request" does not implement "BlueMvc\Core\Interfaces\ControllerInterface".
     */
    public function testGetInvalidControllerClassName2()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/invalid-controller-class-name/', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        $this->application->addRoute(new Route('invalid-controller-class-name', 'BlueMvc\\Core\\Request'));
        $this->application->run($request, $response);
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
     * Test get existing page for controller with default action.
     */
    public function testGetExistingPageForControllerWithDefaultAction()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/default/foo', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        $this->assertSame('Foo Action', $responseOutput);
        $this->assertSame('Foo Action', $response->getContent());
        $this->assertSame(['HTTP/1.1 200 OK'], FakeHeaders::get());
        $this->assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test get default page for controller with default action.
     */
    public function testGetDefaultPageForControllerWithDefaultAction()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/default/bar', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        $this->assertSame('Default Action bar', $responseOutput);
        $this->assertSame('Default Action bar', $response->getContent());
        $this->assertSame(['HTTP/1.1 200 OK'], FakeHeaders::get());
        $this->assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test get default page for controller with default action with view.
     */
    public function testGetDefaultPageForControllerWithDefaultActionWithView()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/defaultview/foo', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        $this->assertSame('<html><body><h1>Default view for action foo</h1></body></html>', $responseOutput);
        $this->assertSame('<html><body><h1>Default view for action foo</h1></body></html>', $response->getContent());
        $this->assertSame(['HTTP/1.1 200 OK'], FakeHeaders::get());
        $this->assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test get a page returning a NotFoundResult.
     */
    public function testGetPageWithNotFoundResult()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/actionresult/notfound', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        $this->assertSame('Page was not found', $responseOutput);
        $this->assertSame('Page was not found', $response->getContent());
        $this->assertSame(['HTTP/1.1 404 Not Found'], FakeHeaders::get());
        $this->assertSame(StatusCode::NOT_FOUND, $response->getStatusCode()->getCode());
    }

    /**
     * Test get a page returning a RedirectResult.
     */
    public function testGetPageWithRedirectResult()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/actionresult/redirect', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        $this->assertSame('', $responseOutput);
        $this->assertSame('', $response->getContent());
        $this->assertSame(['HTTP/1.1 302 Found', 'Location: http://www.domain.com/foo/bar'], FakeHeaders::get());
        $this->assertSame(StatusCode::FOUND, $response->getStatusCode()->getCode());
    }

    /**
     * Test get a page returning a PermanentRedirectResult.
     */
    public function testGetPageWithPermanentRedirectResult()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/actionresult/permanentRedirect', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        $this->assertSame('', $responseOutput);
        $this->assertSame('', $response->getContent());
        $this->assertSame(['HTTP/1.1 301 Moved Permanently', 'Location: https://domain.com/'], FakeHeaders::get());
        $this->assertSame(StatusCode::MOVED_PERMANENTLY, $response->getStatusCode()->getCode());
    }

    /**
     * Test get index page for controller with pre-action event.
     */
    public function testGetIndexPageForControllerWithPreActionEvent()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/preactionevent/', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        $this->assertSame('Index action with pre-action event', $responseOutput);
        $this->assertSame('Index action with pre-action event', $response->getContent());
        $this->assertSame(['HTTP/1.1 200 OK', 'X-Pre-Action: true'], FakeHeaders::get());
        $this->assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test get default page for controller with pre-action event.
     */
    public function testGetDefaultPageForControllerWithPreActionEvent()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/preactionevent/foo', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        $this->assertSame('Default action with pre-action event', $responseOutput);
        $this->assertSame('Default action with pre-action event', $response->getContent());
        $this->assertSame(['HTTP/1.1 200 OK', 'X-Pre-Action: true'], FakeHeaders::get());
        $this->assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
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
        $this->application->addRoute(new Route('default', DefaultActionTestController::class));
        $this->application->addRoute(new Route('defaultview', DefaultActionWithViewTestController::class));
        $this->application->addRoute(new Route('actionresult', ActionResultTestController::class));
        $this->application->addRoute(new Route('preactionevent', PreActionEventController::class));
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
