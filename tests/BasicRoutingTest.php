<?php

namespace BlueMvc\Core\Tests;

use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Request;
use BlueMvc\Core\Response;
use BlueMvc\Core\Route;
use BlueMvc\Core\Tests\Helpers\Fakes\FakeHeaders;
use BlueMvc\Core\Tests\Helpers\TestApplications\BasicTestApplication;
use BlueMvc\Core\Tests\Helpers\TestControllers\ActionResultTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\BasicTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\DefaultActionTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\DefaultActionWithViewTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\ErrorTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\ExceptionTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\MultiLevelTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\PreAndPostActionEventController;
use BlueMvc\Core\Tests\Helpers\TestControllers\UppercaseActionTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\ViewTestController;
use BlueMvc\Core\Tests\Helpers\TestViewRenderers\BasicTestViewRenderer;
use DataTypes\FilePath;

/**
 * Test basic routing for a application.
 */
class BasicRoutingTest extends \PHPUnit_Framework_TestCase
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

        self::assertSame('Hello World!', $responseOutput);
        self::assertSame('Hello World!', $response->getContent());
        self::assertSame(['HTTP/1.1 200 OK'], FakeHeaders::get());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
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

        self::assertSame('', $responseOutput);
        self::assertSame('', $response->getContent());
        self::assertSame(['HTTP/1.1 404 Not Found'], FakeHeaders::get());
        self::assertSame(StatusCode::NOT_FOUND, $response->getStatusCode()->getCode());
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

        self::assertSame('', $responseOutput);
        self::assertSame('', $response->getContent());
        self::assertSame(['HTTP/1.1 404 Not Found'], FakeHeaders::get());
        self::assertSame(StatusCode::NOT_FOUND, $response->getStatusCode()->getCode());
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

        self::assertSame('Server Error', $responseOutput);
        self::assertSame('Server Error', $response->getContent());
        self::assertSame(['HTTP/1.1 500 Internal Server Error'], FakeHeaders::get());
        self::assertSame(StatusCode::INTERNAL_SERVER_ERROR, $response->getStatusCode()->getCode());
    }

    /**
     * Test get page starting with a numeric character.
     */
    public function testGetPageStartingWithNumericCharacter()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/123numeric', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        self::assertSame('Numeric action result', $responseOutput);
        self::assertSame('Numeric action result', $response->getContent());
        self::assertSame(['HTTP/1.1 200 OK'], FakeHeaders::get());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
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

        self::assertSame('<html><body><h1>Index</h1><span>' . $this->application->getDocumentRoot() . '</span><em>' . $request->getUrl() . '</em></body></html>', $responseOutput);
        self::assertSame('<html><body><h1>Index</h1><span>' . $this->application->getDocumentRoot() . '</span><em>' . $request->getUrl() . '</em></body></html>', $response->getContent());
        self::assertSame(['HTTP/1.1 200 OK'], FakeHeaders::get());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
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

        self::assertSame('<html><body><h1>With model</h1><span>' . $this->application->getDocumentRoot() . '</span><em>' . $request->getUrl() . '</em><p>This is the model.</p></body></html>', $responseOutput);
        self::assertSame('<html><body><h1>With model</h1><span>' . $this->application->getDocumentRoot() . '</span><em>' . $request->getUrl() . '</em><p>This is the model.</p></body></html>', $response->getContent());
        self::assertSame(['HTTP/1.1 200 OK'], FakeHeaders::get());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
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

        self::assertSame('<html><body><h1>With model and view data</h1><span>' . $this->application->getDocumentRoot() . '</span><em>' . $request->getUrl() . '</em><p>This is the model.</p><i>This is the view data.</i></body></html>', $responseOutput);
        self::assertSame('<html><body><h1>With model and view data</h1><span>' . $this->application->getDocumentRoot() . '</span><em>' . $request->getUrl() . '</em><p>This is the model.</p><i>This is the view data.</i></body></html>', $response->getContent());
        self::assertSame(['HTTP/1.1 200 OK'], FakeHeaders::get());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test get view with non-existing view file page.
     */
    public function testGetViewWithNonExistingViewFilePage()
    {
        $DS = DIRECTORY_SEPARATOR;
        $this->application->setDebug(true);

        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/view/withnoviewfile', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        self::assertContains('BlueMvc\Core\Exceptions\ViewFileNotFoundException', $responseOutput);
        self::assertContains('Could not find view file &quot;' . $this->application->getViewPath() . 'ViewTest' . $DS . 'withnoviewfile.view&quot;', $responseOutput);
        self::assertContains('BlueMvc\Core\Exceptions\ViewFileNotFoundException', $response->getContent());
        self::assertContains('Could not find view file &quot;' . $this->application->getViewPath() . 'ViewTest' . $DS . 'withnoviewfile.view&quot;', $response->getContent());
        self::assertSame(['HTTP/1.1 500 Internal Server Error'], FakeHeaders::get());
        self::assertSame(StatusCode::INTERNAL_SERVER_ERROR, $response->getStatusCode()->getCode());
    }

    /**
     * Test get view with custom view file page.
     */
    public function testGetViewWithCustomViewFilePage()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/view/withcustomviewfile', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        self::assertSame('<html><body><h1>Custom view file</h1><span>' . $this->application->getDocumentRoot() . '</span><em>http://www.domain.com/view/withcustomviewfile</em><p>This is the model.</p></body></html>', $responseOutput);
        self::assertSame('<html><body><h1>Custom view file</h1><span>' . $this->application->getDocumentRoot() . '</span><em>http://www.domain.com/view/withcustomviewfile</em><p>This is the model.</p></body></html>', $response->getContent());
        self::assertSame(['HTTP/1.1 200 OK'], FakeHeaders::get());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
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

        self::assertSame('Foo Action', $responseOutput);
        self::assertSame('Foo Action', $response->getContent());
        self::assertSame(['HTTP/1.1 200 OK'], FakeHeaders::get());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
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

        self::assertSame('Default Action bar', $responseOutput);
        self::assertSame('Default Action bar', $response->getContent());
        self::assertSame(['HTTP/1.1 200 OK'], FakeHeaders::get());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
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

        self::assertSame('<html><body><h1>Default view for action foo</h1></body></html>', $responseOutput);
        self::assertSame('<html><body><h1>Default view for action foo</h1></body></html>', $response->getContent());
        self::assertSame(['HTTP/1.1 200 OK'], FakeHeaders::get());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
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

        self::assertSame('Page was not found', $responseOutput);
        self::assertSame('Page was not found', $response->getContent());
        self::assertSame(['HTTP/1.1 404 Not Found'], FakeHeaders::get());
        self::assertSame(StatusCode::NOT_FOUND, $response->getStatusCode()->getCode());
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

        self::assertSame('', $responseOutput);
        self::assertSame('', $response->getContent());
        self::assertSame(['HTTP/1.1 302 Found', 'Location: http://www.domain.com/foo/bar'], FakeHeaders::get());
        self::assertSame(StatusCode::FOUND, $response->getStatusCode()->getCode());
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

        self::assertSame('', $responseOutput);
        self::assertSame('', $response->getContent());
        self::assertSame(['HTTP/1.1 301 Moved Permanently', 'Location: https://domain.com/'], FakeHeaders::get());
        self::assertSame(StatusCode::MOVED_PERMANENTLY, $response->getStatusCode()->getCode());
    }

    /**
     * Test get a page returning a ForbiddenResult.
     */
    public function testGetPageWithForbiddenResult()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/actionresult/forbidden', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        self::assertSame('Page is forbidden', $responseOutput);
        self::assertSame('Page is forbidden', $response->getContent());
        self::assertSame(['HTTP/1.1 403 Forbidden'], FakeHeaders::get());
        self::assertSame(StatusCode::FORBIDDEN, $response->getStatusCode()->getCode());
    }

    /**
     * Test get a page returning a NoContentResult.
     */
    public function testGetPageWithNoContentResult()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/actionresult/nocontent', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        self::assertSame('', $responseOutput);
        self::assertSame('', $response->getContent());
        self::assertSame(['HTTP/1.1 204 No Content'], FakeHeaders::get());
        self::assertSame(StatusCode::NO_CONTENT, $response->getStatusCode()->getCode());
    }

    /**
     * Test get a page returning a NotModifiedResult.
     */
    public function testGetPageWithNotModifiedResult()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/actionresult/notmodified', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        self::assertSame('', $responseOutput);
        self::assertSame('', $response->getContent());
        self::assertSame(['HTTP/1.1 304 Not Modified'], FakeHeaders::get());
        self::assertSame(StatusCode::NOT_MODIFIED, $response->getStatusCode()->getCode());
    }

    /**
     * Test get a page returning a JsonResult.
     */
    public function testGetPageWithJsonResult()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/actionresult/json', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        self::assertSame('{"Foo":1,"Bar":{"Baz":2}}', $responseOutput);
        self::assertSame('{"Foo":1,"Bar":{"Baz":2}}', $response->getContent());
        self::assertSame(['HTTP/1.1 200 OK', 'Content-Type: application/json'], FakeHeaders::get());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test get index page for controller with pre-action event.
     */
    public function testGetIndexPageForControllerWithPreActionEvent()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/preandpostactionevent/', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        self::assertSame('Index action with pre- and post-action event', $responseOutput);
        self::assertSame('Index action with pre- and post-action event', $response->getContent());
        self::assertSame(['HTTP/1.1 200 OK', 'X-Pre-Action: true', 'X-Post-Action: true'], FakeHeaders::get());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test get default page for controller with pre-action event.
     */
    public function testGetDefaultPageForControllerWithPreActionEvent()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/preandpostactionevent/foo', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        self::assertSame('Default action "foo" with pre- and post-action event', $responseOutput);
        self::assertSame('Default action "foo" with pre- and post-action event', $response->getContent());
        self::assertSame(['HTTP/1.1 200 OK', 'X-Pre-Action: true', 'X-Post-Action: true'], FakeHeaders::get());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test get a page with pre-action event result.
     */
    public function testGetPageWithPreActionEventResult()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com:81', 'SERVER_PORT' => '81', 'REQUEST_URI' => '/preandpostactionevent/', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        self::assertSame('This is a pre-action result', $responseOutput);
        self::assertSame('This is a pre-action result', $response->getContent());
        self::assertSame(['HTTP/1.1 404 Not Found'], FakeHeaders::get());
        self::assertSame(StatusCode::NOT_FOUND, $response->getStatusCode()->getCode());
    }

    /**
     * Test get a page with post-action event result.
     */
    public function testGetPageWithPostActionEventResult()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com:82', 'SERVER_PORT' => '82', 'REQUEST_URI' => '/preandpostactionevent/', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        self::assertSame('This is a post-action result', $responseOutput);
        self::assertSame('This is a post-action result', $response->getContent());
        self::assertSame(['HTTP/1.1 200 OK', 'X-Pre-Action: true'], FakeHeaders::get());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test get a page with not found error with error controller set.
     */
    public function testGetPageWithNotFoundErrorWithErrorControllerSet()
    {
        $this->application->setErrorControllerClass(ErrorTestController::class);
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/actionresult/notfound', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        self::assertSame('<html><body><h1>Request Failed: Error: 404</h1></body></html>', $responseOutput);
        self::assertSame('<html><body><h1>Request Failed: Error: 404</h1></body></html>', $response->getContent());
        self::assertSame(['HTTP/1.1 404 Not Found'], FakeHeaders::get());
        self::assertSame(StatusCode::NOT_FOUND, $response->getStatusCode()->getCode());
    }

    /**
     * Test get a page with server error with error controller set.
     */
    public function testGetPageWithServerErrorWithErrorControllerSet()
    {
        $this->application->setErrorControllerClass(ErrorTestController::class);
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/exception/', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        self::assertSame('<html><body><h1>Request Failed: Error: 500, Exception: LogicException, ExceptionMessage: Exception was thrown.</h1></body></html>', $responseOutput);
        self::assertSame('<html><body><h1>Request Failed: Error: 500, Exception: LogicException, ExceptionMessage: Exception was thrown.</h1></body></html>', $response->getContent());
        self::assertSame(['HTTP/1.1 500 Internal Server Error'], FakeHeaders::get());
        self::assertSame(StatusCode::INTERNAL_SERVER_ERROR, $response->getStatusCode()->getCode());
    }

    /**
     * Test get a page with error controller throwing exception in non debug mode.
     */
    public function testGetPageWithErrorControllerThrowingExceptionInNonDebugMode()
    {
        $this->application->setErrorControllerClass(ErrorTestController::class);
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/actionresult/forbidden', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        self::assertSame('', $responseOutput);
        self::assertSame('', $response->getContent());
        self::assertSame(['HTTP/1.1 500 Internal Server Error'], FakeHeaders::get());
        self::assertSame(StatusCode::INTERNAL_SERVER_ERROR, $response->getStatusCode()->getCode());
    }

    /**
     * Test get a page with error controller throwing exception in debug mode.
     */
    public function testGetPageWithErrorControllerThrowingExceptionInDebugMode()
    {
        $this->application->setDebug(true);
        $this->application->setErrorControllerClass(ErrorTestController::class);
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/actionresult/forbidden', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        self::assertContains('Exception thrown from 403 action.', $responseOutput);
        self::assertContains('Exception thrown from 403 action.', $response->getContent());
        self::assertContains('RuntimeException', $responseOutput);
        self::assertContains('RuntimeException', $response->getContent());
        self::assertSame(['HTTP/1.1 500 Internal Server Error'], FakeHeaders::get());
        self::assertSame(StatusCode::INTERNAL_SERVER_ERROR, $response->getStatusCode()->getCode());
    }

    /**
     * Test get integer result page.
     */
    public function testGetIntegerResultPage()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/int', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        self::assertSame('42', $responseOutput);
        self::assertSame('42', $response->getContent());
        self::assertSame(['HTTP/1.1 200 OK'], FakeHeaders::get());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test get false result page.
     */
    public function testGetFalseResultPage()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/false', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        self::assertSame('false', $responseOutput);
        self::assertSame('false', $response->getContent());
        self::assertSame(['HTTP/1.1 200 OK'], FakeHeaders::get());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test get true result page.
     */
    public function testGetTrueResultPage()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/true', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        self::assertSame('true', $responseOutput);
        self::assertSame('true', $response->getContent());
        self::assertSame(['HTTP/1.1 200 OK'], FakeHeaders::get());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test get null result page.
     */
    public function testGetNullResultPage()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/null', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        self::assertSame('Content set manually.', $responseOutput);
        self::assertSame('Content set manually.', $response->getContent());
        self::assertSame(['HTTP/1.1 200 OK'], FakeHeaders::get());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test get object result page.
     */
    public function testGetObjectResultPage()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/object', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        self::assertSame('object', $responseOutput);
        self::assertSame('object', $response->getContent());
        self::assertSame(['HTTP/1.1 200 OK'], FakeHeaders::get());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test get stringable object result page.
     */
    public function testGetStringableResultPage()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/stringable', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        self::assertSame('Text is "Bar"', $responseOutput);
        self::assertSame('Text is "Bar"', $response->getContent());
        self::assertSame(['HTTP/1.1 200 OK'], FakeHeaders::get());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test get uppercase index action method page.
     */
    public function testUppercaseIndexPage()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/uppercase/', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        self::assertSame('INDEX action', $responseOutput);
        self::assertSame('INDEX action', $response->getContent());
        self::assertSame(['HTTP/1.1 200 OK'], FakeHeaders::get());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test get uppercase default action method page.
     */
    public function testUppercaseDefaultPage()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/uppercase/bar', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        self::assertSame('DEFAULT action "bar"', $responseOutput);
        self::assertSame('DEFAULT action "bar"', $response->getContent());
        self::assertSame(['HTTP/1.1 200 OK'], FakeHeaders::get());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test get uppercase action method page with lowercase path.
     */
    public function testUppercaseActionMethodPageWithLowercasePath()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/uppercase/foo', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        self::assertSame('DEFAULT action "foo"', $responseOutput);
        self::assertSame('DEFAULT action "foo"', $response->getContent());
        self::assertSame(['HTTP/1.1 200 OK'], FakeHeaders::get());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test get pages with multi level urls.
     *
     * @dataProvider multiLevelPagesDataProvider
     *
     * @param string $url                The (relative) url.
     * @param string $expectedContent    The expected content.
     * @param array  $expectedHeaders    The expected headers.
     * @param int    $expectedStatusCode The expected status code.
     */
    public function testMultiLevelPages($url, $expectedContent, array $expectedHeaders, $expectedStatusCode)
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/multilevel/' . $url, 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        self::assertSame($expectedContent, $responseOutput);
        self::assertSame($expectedContent, $response->getContent());
        self::assertSame($expectedHeaders, FakeHeaders::get());
        self::assertSame($expectedStatusCode, $response->getStatusCode()->getCode());
    }

    /**
     * Data provider for multi level urls tests.
     */
    public function multiLevelPagesDataProvider()
    {
        return [
            ['noparams', 'No Parameters', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['noparams/', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['noparams/param1', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['noparams/param1/', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['noparams/param1/param2', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['noparams/param1/param2/', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['noparams/param1/param2/param3', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['noparams/param1/param2/param3/', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['foo', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['foo/', 'FooAction: Foo=[]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['foo/param1', 'FooAction: Foo=[param1]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['foo/param1/', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['foo/param1/param2', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['foo/param1/param2/', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['foo/param1/param2/param3', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['foo/param1/param2/param3/', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['foobar', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['foobar/', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['foobar/param1', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['foobar/param1/', 'FooBarAction: Foo=[param1], Bar=[]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['foobar/param1/param2', 'FooBarAction: Foo=[param1], Bar=[param2]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['foobar/param1/param2/', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['foobar/param1/param2/param3', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['foobar/param1/param2/param3/', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['foobarbaz', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['foobarbaz/', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['foobarbaz/param1', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['foobarbaz/param1/', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['foobarbaz/param1/param2', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['foobarbaz/param1/param2/', 'FooBarBazAction: Foo=[param1], Bar=[param2], Baz=[]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['foobarbaz/param1/param2/param3', 'FooBarBazAction: Foo=[param1], Bar=[param2], Baz=[param3]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['foobarbaz/param1/param2/param3/', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['foonull', 'FooNullAction: Foo=[*null*]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['foonull/', 'FooNullAction: Foo=[]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['foonull/param1', 'FooNullAction: Foo=[param1]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['foonull/param1/', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['foonull/param1/param2', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['foonull/param1/param2/', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['foonull/param1/param2/param3', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['foonull/param1/param2/param3/', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['foonullbarnull', 'FooNullBarNullAction: Foo=[*null*], Bar=[*null*]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['foonullbarnull/', 'FooNullBarNullAction: Foo=[], Bar=[*null*]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['foonullbarnull/param1', 'FooNullBarNullAction: Foo=[param1], Bar=[*null*]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['foonullbarnull/param1/', 'FooNullBarNullAction: Foo=[param1], Bar=[]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['foonullbarnull/param1/param2', 'FooNullBarNullAction: Foo=[param1], Bar=[param2]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['foonullbarnull/param1/param2/', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['foonullbarnull/param1/param2/param3', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['foonullbarnull/param1/param2/param3/', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['foonullbarnullbaznull', 'FooNullBarNullBazNullAction: Foo=[*null*], Bar=[*null*], Baz=[*null*]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['foonullbarnullbaznull/', 'FooNullBarNullBazNullAction: Foo=[], Bar=[*null*], Baz=[*null*]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['foonullbarnullbaznull/param1', 'FooNullBarNullBazNullAction: Foo=[param1], Bar=[*null*], Baz=[*null*]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['foonullbarnullbaznull/param1/', 'FooNullBarNullBazNullAction: Foo=[param1], Bar=[], Baz=[*null*]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['foonullbarnullbaznull/param1/param2', 'FooNullBarNullBazNullAction: Foo=[param1], Bar=[param2], Baz=[*null*]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['foonullbarnullbaznull/param1/param2/', 'FooNullBarNullBazNullAction: Foo=[param1], Bar=[param2], Baz=[]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['foonullbarnullbaznull/param1/param2/param3', 'FooNullBarNullBazNullAction: Foo=[param1], Bar=[param2], Baz=[param3]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['foonullbarnullbaznull/param1/param2/param3/', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['foobarnull', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['foobarnull/', 'FooBarNullAction: Foo=[], Bar=[*null*]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['foobarnull/param1', 'FooBarNullAction: Foo=[param1], Bar=[*null*]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['foobarnull/param1/', 'FooBarNullAction: Foo=[param1], Bar=[]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['foobarnull/param1/param2', 'FooBarNullAction: Foo=[param1], Bar=[param2]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['foobarnull/param1/param2/', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['foobarnull/param1/param2/param3', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['foobarnull/param1/param2/param3/', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['foobarnullbaznull', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['foobarnullbaznull/', 'FooBarNullBazNullAction: Foo=[], Bar=[*null*], Baz=[*null*]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['foobarnullbaznull/param1', 'FooBarNullBazNullAction: Foo=[param1], Bar=[*null*], Baz=[*null*]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['foobarnullbaznull/param1/', 'FooBarNullBazNullAction: Foo=[param1], Bar=[], Baz=[*null*]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['foobarnullbaznull/param1/param2', 'FooBarNullBazNullAction: Foo=[param1], Bar=[param2], Baz=[*null*]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['foobarnullbaznull/param1/param2/', 'FooBarNullBazNullAction: Foo=[param1], Bar=[param2], Baz=[]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['foobarnullbaznull/param1/param2/param3', 'FooBarNullBazNullAction: Foo=[param1], Bar=[param2], Baz=[param3]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['foobarnullbaznull/param1/param2/param3/', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['foobarbazstring', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['foobarbazstring/', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['foobarbazstring/param1', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['foobarbazstring/param1/', 'FooBarBazStringAction: Foo=[param1], Bar=[], Baz=[default string]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['foobarbazstring/param1/param2', 'FooBarBazStringAction: Foo=[param1], Bar=[param2], Baz=[default string]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['foobarbazstring/param1/param2/', 'FooBarBazStringAction: Foo=[param1], Bar=[param2], Baz=[]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['foobarbazstring/param1/param2/param3', 'FooBarBazStringAction: Foo=[param1], Bar=[param2], Baz=[param3]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['foobarbazstring/param1/param2/param3/', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
        ];
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
        $this->application->addRoute(new Route('preandpostactionevent', PreAndPostActionEventController::class));
        $this->application->addRoute(new Route('exception', ExceptionTestController::class));
        $this->application->addRoute(new Route('uppercase', UppercaseActionTestController::class));
        $this->application->addRoute(new Route('multilevel', MultiLevelTestController::class));
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
