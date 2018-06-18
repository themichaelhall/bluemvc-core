<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests;

use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Interfaces\PluginInterface;
use BlueMvc\Core\Request;
use BlueMvc\Core\RequestCookie;
use BlueMvc\Core\Response;
use BlueMvc\Core\Route;
use BlueMvc\Core\Tests\Helpers\Fakes\FakeHeaders;
use BlueMvc\Core\Tests\Helpers\TestApplications\BasicTestApplication;
use BlueMvc\Core\Tests\Helpers\TestControllers\ActionMethodVisibilityTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\ActionResultTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\BasicTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\CookieTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\CustomViewPathTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\DefaultActionTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\DefaultActionWithViewTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\ErrorTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\ExceptionTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\MultiLevelTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\PreAndPostActionEventController;
use BlueMvc\Core\Tests\Helpers\TestControllers\SpecialActionNameTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\TypeHintActionParametersTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\UppercaseActionTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\ViewTestController;
use BlueMvc\Core\Tests\Helpers\TestPlugins\SetContentTestPlugin;
use BlueMvc\Core\Tests\Helpers\TestPlugins\SetHeaderTestPlugin;
use BlueMvc\Core\Tests\Helpers\TestViewRenderers\BasicTestViewRenderer;
use BlueMvc\Core\Tests\Helpers\TestViewRenderers\JsonTestViewRenderer;
use DataTypes\FilePath;
use PHPUnit\Framework\TestCase;

/**
 * Test basic routing for a application.
 */
class BasicRoutingTest extends TestCase
{
    /**
     * Test get index page.
     */
    public function testGetIndexPage()
    {
        $_SERVER = ['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/', 'REQUEST_METHOD' => 'GET'];

        $request = new Request();
        $response = new Response();
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
        $_SERVER = ['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/notfound', 'REQUEST_METHOD' => 'GET'];

        $request = new Request();
        $response = new Response();
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
        $_SERVER = ['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/non-existing-controller/action', 'REQUEST_METHOD' => 'GET'];

        $request = new Request();
        $response = new Response();
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
        $_SERVER = ['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/serverError', 'REQUEST_METHOD' => 'GET'];

        $request = new Request();
        $response = new Response();
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
        $_SERVER = ['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/123numeric', 'REQUEST_METHOD' => 'GET'];

        $request = new Request();
        $response = new Response();
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
        $_SERVER = ['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/view/', 'REQUEST_METHOD' => 'GET'];

        $request = new Request();
        $response = new Response();
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
        $_SERVER = ['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/view/withmodel', 'REQUEST_METHOD' => 'GET'];

        $request = new Request();
        $response = new Response();
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
        $_SERVER = ['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/view/withviewdata', 'REQUEST_METHOD' => 'GET'];

        $request = new Request();
        $response = new Response();
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

        $_SERVER = ['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/view/withnoviewfile', 'REQUEST_METHOD' => 'GET'];

        $request = new Request();
        $response = new Response();
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        self::assertContains('BlueMvc\Core\Exceptions\ViewFileNotFoundException', $responseOutput);
        self::assertContains('Could not find view file &quot;' . $this->application->getViewPath() . 'ViewTest' . $DS . 'withnoviewfile.json&quot; or &quot;' . $this->application->getViewPath() . 'ViewTest' . $DS . 'withnoviewfile.view&quot;', $responseOutput);
        self::assertContains('BlueMvc\Core\Exceptions\ViewFileNotFoundException', $response->getContent());
        self::assertContains('Could not find view file &quot;' . $this->application->getViewPath() . 'ViewTest' . $DS . 'withnoviewfile.json&quot; or &quot;' . $this->application->getViewPath() . 'ViewTest' . $DS . 'withnoviewfile.view&quot;', $response->getContent());
        self::assertSame(['HTTP/1.1 500 Internal Server Error'], FakeHeaders::get());
        self::assertSame(StatusCode::INTERNAL_SERVER_ERROR, $response->getStatusCode()->getCode());
    }

    /**
     * Test get view with custom view file page.
     */
    public function testGetViewWithCustomViewFilePage()
    {
        $_SERVER = ['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/view/withcustomviewfile', 'REQUEST_METHOD' => 'GET'];

        $request = new Request();
        $response = new Response();
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
     * Test get view with custom view path.
     */
    public function testGetViewWithCustomViewFilePath()
    {
        $_SERVER = ['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/customViewPath/', 'REQUEST_METHOD' => 'GET'];

        $request = new Request();
        $response = new Response();
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        self::assertSame('<html><body><h1>View in custom view path</h1></body></html>', $responseOutput);
        self::assertSame('<html><body><h1>View in custom view path</h1></body></html>', $response->getContent());
        self::assertSame(['HTTP/1.1 200 OK'], FakeHeaders::get());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test get view with alternate view type.
     */
    public function testGetViewWithAlternateViewType()
    {
        $_SERVER = ['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/view/alternate', 'REQUEST_METHOD' => 'GET'];

        $request = new Request();
        $response = new Response();
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        self::assertSame('{"Model":"This is the model.","ViewItems":{"Foo":"Bar"}}', $responseOutput);
        self::assertSame('{"Model":"This is the model.","ViewItems":{"Foo":"Bar"}}', $response->getContent());
        self::assertSame(['HTTP/1.1 200 OK'], FakeHeaders::get());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test get view with second choice view type.
     */
    public function testGetViewWithSecondChoiceViewType()
    {
        $_SERVER = ['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/view/onlyjson', 'REQUEST_METHOD' => 'GET'];

        $request = new Request();
        $response = new Response();
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        self::assertSame('{"Model":"This is the model."}', $responseOutput);
        self::assertSame('{"Model":"This is the model."}', $response->getContent());
        self::assertSame(['HTTP/1.1 200 OK'], FakeHeaders::get());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test get existing page for controller with default action.
     */
    public function testGetExistingPageForControllerWithDefaultAction()
    {
        $_SERVER = ['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/default/foo', 'REQUEST_METHOD' => 'GET'];

        $request = new Request();
        $response = new Response();
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
        $_SERVER = ['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/default/bar', 'REQUEST_METHOD' => 'GET'];

        $request = new Request();
        $response = new Response();
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
        $_SERVER = ['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/defaultview/foo', 'REQUEST_METHOD' => 'GET'];

        $request = new Request();
        $response = new Response();
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
     * Test get page returning action result.
     *
     * @dataProvider getPageReturningActionResultDataProvider
     *
     * @param string $path               The path.
     * @param int    $expectedStatusCode The expected status code.
     * @param array  $expectedHeaders    The expected headers.
     * @param string $expectedContent    The expected content.
     */
    public function testGetPageReturningActionResult(string $path, int $expectedStatusCode, array $expectedHeaders, string $expectedContent)
    {
        $_SERVER = ['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/actionresult/' . $path, 'REQUEST_METHOD' => 'GET'];

        $request = new Request();
        $response = new Response();
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        self::assertSame($expectedStatusCode, $response->getStatusCode()->getCode());
        self::assertSame($expectedHeaders, FakeHeaders::get());
        self::assertSame($expectedContent, $responseOutput);
        self::assertSame($expectedContent, $response->getContent());
    }

    /**
     * Data provider for page returning action results test.
     *
     * @return array The data.
     */
    public function getPageReturningActionResultDataProvider()
    {
        return [
            ['notfound', StatusCode::NOT_FOUND, ['HTTP/1.1 404 Not Found'], 'Page was not found'],
            ['redirect', StatusCode::FOUND, ['HTTP/1.1 302 Found', 'Location: http://www.domain.com/foo/bar'], ''],
            ['permanentRedirect', StatusCode::MOVED_PERMANENTLY, ['HTTP/1.1 301 Moved Permanently', 'Location: https://domain.com/'], ''],
            ['forbidden', StatusCode::FORBIDDEN, ['HTTP/1.1 403 Forbidden'], 'Page is forbidden'],
            ['nocontent', StatusCode::NO_CONTENT, ['HTTP/1.1 204 No Content'], ''],
            ['notmodified', StatusCode::NOT_MODIFIED, ['HTTP/1.1 304 Not Modified'], ''],
            ['methodnotallowed', StatusCode::METHOD_NOT_ALLOWED, ['HTTP/1.1 405 Method Not Allowed'], ''],
            ['json', StatusCode::OK, ['HTTP/1.1 200 OK', 'Content-Type: application/json'], '{"Foo":1,"Bar":{"Baz":2}}'],
            ['created', StatusCode::CREATED, ['HTTP/1.1 201 Created', 'Location: http://www.domain.com/actionresult/created'], ''],
            ['custom', StatusCode::MULTI_STATUS, ['HTTP/1.1 207 Multi-Status'], 'Custom action result'],
        ];
    }

    /**
     * Test get pages with pre- and post-action event.
     *
     * @dataProvider preAndPostActionEventDataProvider
     *
     * @param string $action             The action.
     * @param int    $port               The port.
     * @param int    $expectedStatusCode The expected status code.
     * @param array  $expectedHeaders    The expected headers.
     * @param string $expectedContent    The expected content.
     */
    public function testGetPagesWithPreAndPostActionEvent(string $action, int $port, int $expectedStatusCode, array $expectedHeaders, string $expectedContent)
    {
        $_SERVER = ['HTTP_HOST' => 'www.domain.com:' . $port, 'REQUEST_URI' => '/preandpostactionevent/' . $action, 'REQUEST_METHOD' => 'GET'];

        $request = new Request();
        $response = new Response();
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        self::assertSame($expectedStatusCode, $response->getStatusCode()->getCode());
        self::assertSame($expectedHeaders, FakeHeaders::get());
        self::assertSame($expectedContent, $responseOutput);
        self::assertSame($expectedContent, $response->getContent());
    }

    /**
     * Data provider for pre- and post-action event tests.
     *
     * @return array The data.
     */
    public function preAndPostActionEventDataProvider()
    {
        return [
            ['', 80, 200, ['HTTP/1.1 200 OK', 'X-Pre-Action: true', 'X-Post-Action: true'], 'Index action with pre- and post-action event'],
            ['foo', 80, 200, ['HTTP/1.1 200 OK', 'X-Pre-Action: true', 'X-Post-Action: true'], 'Default action "foo" with pre- and post-action event'],
            ['', 81, 404, ['HTTP/1.1 404 Not Found'], 'This is a pre-action result'],
            ['foo', 81, 404, ['HTTP/1.1 404 Not Found'], 'This is a pre-action result'],
            ['', 82, 200, ['HTTP/1.1 200 OK', 'X-Pre-Action: true'], 'This is a post-action result'],
            ['foo', 82, 200, ['HTTP/1.1 200 OK', 'X-Pre-Action: true'], 'This is a post-action result'],
            ['', 83, 200, ['HTTP/1.1 200 OK', 'X-Post-Action: true'], 'Index action with pre- and post-action event'],
            ['foo', 83, 200, ['HTTP/1.1 200 OK', 'X-Post-Action: true'], 'Default action "foo" with pre- and post-action event'],
            ['', 84, 200, ['HTTP/1.1 200 OK', 'X-Pre-Action: true'], 'Index action with pre- and post-action event'],
            ['foo', 84, 200, ['HTTP/1.1 200 OK', 'X-Pre-Action: true'], 'Default action "foo" with pre- and post-action event'],
            ['', 85, 200, ['HTTP/1.1 200 OK'], 'Index action with pre- and post-action event'],
            ['foo', 85, 200, ['HTTP/1.1 200 OK'], 'Default action "foo" with pre- and post-action event'],
        ];
    }

    /**
     * Test get a page with not found error with error controller set.
     */
    public function testGetPageWithNotFoundErrorWithErrorControllerSet()
    {
        $this->application->setErrorControllerClass(ErrorTestController::class);

        $_SERVER = ['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/actionresult/notfound', 'REQUEST_METHOD' => 'GET'];

        $request = new Request();
        $response = new Response();
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        self::assertSame('<html><body><h1>Request Failed: Error: 404</h1></body></html>', $responseOutput);
        self::assertSame('<html><body><h1>Request Failed: Error: 404</h1></body></html>', $response->getContent());
        self::assertSame(['HTTP/1.1 404 Not Found', 'X-Error-PreActionEvent: 1', 'X-Error-PostActionEvent: 1'], FakeHeaders::get());
        self::assertSame(StatusCode::NOT_FOUND, $response->getStatusCode()->getCode());
    }

    /**
     * Test get a page with server error with error controller set.
     */
    public function testGetPageWithServerErrorWithErrorControllerSet()
    {
        $this->application->setErrorControllerClass(ErrorTestController::class);

        $_SERVER = ['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/exception/', 'REQUEST_METHOD' => 'GET'];

        $request = new Request();
        $response = new Response();
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        self::assertSame('<html><body><h1>Request Failed: Error: 500, Throwable: LogicException, ThrowableMessage: Exception was thrown.</h1></body></html>', $responseOutput);
        self::assertSame('<html><body><h1>Request Failed: Error: 500, Throwable: LogicException, ThrowableMessage: Exception was thrown.</h1></body></html>', $response->getContent());
        self::assertSame(['HTTP/1.1 500 Internal Server Error'], FakeHeaders::get());
        self::assertSame(StatusCode::INTERNAL_SERVER_ERROR, $response->getStatusCode()->getCode());
    }

    /**
     * Test get a page with error controller throwing exception in non debug mode.
     */
    public function testGetPageWithErrorControllerThrowingExceptionInNonDebugMode()
    {
        $this->application->setErrorControllerClass(ErrorTestController::class);

        $_SERVER = ['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/actionresult/forbidden', 'REQUEST_METHOD' => 'GET'];

        $request = new Request();
        $response = new Response();
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

        $_SERVER = ['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/actionresult/forbidden', 'REQUEST_METHOD' => 'GET'];

        $request = new Request();
        $response = new Response();
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
        $_SERVER = ['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/int', 'REQUEST_METHOD' => 'GET'];

        $request = new Request();
        $response = new Response();
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
        $_SERVER = ['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/false', 'REQUEST_METHOD' => 'GET'];

        $request = new Request();
        $response = new Response();
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
        $_SERVER = ['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/true', 'REQUEST_METHOD' => 'GET'];

        $request = new Request();
        $response = new Response();
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
        $_SERVER = ['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/null', 'REQUEST_METHOD' => 'GET'];

        $request = new Request();
        $response = new Response();
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
        $_SERVER = ['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/object', 'REQUEST_METHOD' => 'GET'];

        $request = new Request();
        $response = new Response();
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
        $_SERVER = ['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/stringable', 'REQUEST_METHOD' => 'GET'];

        $request = new Request();
        $response = new Response();
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
        $_SERVER = ['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/uppercase/', 'REQUEST_METHOD' => 'GET'];

        $request = new Request();
        $response = new Response();
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
        $_SERVER = ['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/uppercase/bar', 'REQUEST_METHOD' => 'GET'];

        $request = new Request();
        $response = new Response();
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
        $_SERVER = ['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/uppercase/foo', 'REQUEST_METHOD' => 'GET'];

        $request = new Request();
        $response = new Response();
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
    public function testMultiLevelPages(string $url, string $expectedContent, array $expectedHeaders, int $expectedStatusCode)
    {
        $_SERVER = ['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/multilevel/' . $url, 'REQUEST_METHOD' => 'GET'];

        $request = new Request();
        $response = new Response();
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
            ['nonexisting', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['nonexisting/', 'DefaultAction: Foo=[nonexisting], Bar=[], Baz=[*null*]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['nonexisting/param1', 'DefaultAction: Foo=[nonexisting], Bar=[param1], Baz=[*null*]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['nonexisting/param1/', 'DefaultAction: Foo=[nonexisting], Bar=[param1], Baz=[]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['nonexisting/param1/param2', 'DefaultAction: Foo=[nonexisting], Bar=[param1], Baz=[param2]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['nonexisting/param1/param2/', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['nonexisting/param1/param2/param3', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['nonexisting/param1/param2/param3/', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
        ];
    }

    /**
     * Test get pages with different action method visibilities.
     *
     * @dataProvider actionMethodVisibilityPagesDataProvider
     *
     * @param string $url                The (relative) url.
     * @param string $expectedContent    The expected content.
     * @param array  $expectedHeaders    The expected headers.
     * @param int    $expectedStatusCode The expected status code.
     */
    public function testActionMethodVisibilityPages(string $url, string $expectedContent, array $expectedHeaders, int $expectedStatusCode)
    {
        $_SERVER = ['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/actionMethodVisibility/' . $url, 'REQUEST_METHOD' => 'GET'];

        $request = new Request();
        $response = new Response();
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
     * Data provider for action method visibility tests.
     */
    public function actionMethodVisibilityPagesDataProvider()
    {
        return [
            ['public', 'Public action', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['protected', 'Default action: Action=[protected]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['private', 'Default action: Action=[private]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['publicStatic', 'Public static action', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['protectedStatic', 'Default action: Action=[protectedStatic]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['privateStatic', 'Default action: Action=[privateStatic]', ['HTTP/1.1 200 OK'], StatusCode::OK],
        ];
    }

    /**
     * Test get pages with special action names.
     *
     * @dataProvider specialActionNamePagesDataProvider
     *
     * @param string $url                The (relative) url.
     * @param string $expectedContent    The expected content.
     * @param array  $expectedHeaders    The expected headers.
     * @param int    $expectedStatusCode The expected status code.
     */
    public function testSpecialActionNamePages(string $url, string $expectedContent, array $expectedHeaders, int $expectedStatusCode)
    {
        $_SERVER = ['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/specialActionName/' . $url, 'REQUEST_METHOD' => 'GET'];

        $request = new Request();
        $response = new Response();
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
     * Data provider for special action name tests.
     */
    public function specialActionNamePagesDataProvider()
    {
        return [
            ['index', '_index action', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['INDEX', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['Default', '_Default action', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['default', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['Foo', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
        ];
    }

    /**
     * Test get page that displays request cookies.
     *
     * @dataProvider requestCookiePageDataProvider
     *
     * @param RequestCookie[] $requestCookies  The request cookies.
     * @param string          $expectedContent The expected content.
     */
    public function testRequestCookiePage(array $requestCookies, string $expectedContent)
    {
        $_SERVER = ['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/cookies/', 'REQUEST_METHOD' => 'GET'];
        $_COOKIE = $requestCookies;

        $request = new Request();
        $response = new Response();
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        self::assertSame($expectedContent, $responseOutput);
        self::assertSame($expectedContent, $response->getContent());
        self::assertSame(['HTTP/1.1 200 OK'], FakeHeaders::get());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Data provider for request cookie tests.
     */
    public function requestCookiePageDataProvider()
    {
        return [
            [[], ''],
            [['Foo' => 'Bar'], 'Foo=Bar'],
            [[1 => 'Baz', 'Foo' => 2], '1=Baz,Foo=2'],
        ];
    }

    /**
     * Test get page with multiple level route path.
     *
     * @dataProvider multipleLevelRoutePathDataProvider
     *
     * @param string $url                The (relative) url.
     * @param string $expectedContent    The expected content.
     * @param array  $expectedHeaders    The expected headers.
     * @param int    $expectedStatusCode The expected status code.
     */
    public function testMultipleLevelRoutePathPage(string $url, string $expectedContent, array $expectedHeaders, int $expectedStatusCode)
    {
        $_SERVER = ['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/multiple/level/' . $url, 'REQUEST_METHOD' => 'GET'];

        $request = new Request();
        $response = new Response();
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
     * Data provider for multiple level route path tests.
     *
     * @return array The data.
     */
    public function multipleLevelRoutePathDataProvider()
    {
        return [
            ['noparams', 'No Parameters', ['HTTP/1.1 200 OK'], 200],
            ['noparams/', '', ['HTTP/1.1 404 Not Found'], 404],
            ['foo', '', ['HTTP/1.1 404 Not Found'], 404],
            ['foo/', 'FooAction: Foo=[]', ['HTTP/1.1 200 OK'], 200],
            ['foo/param1', 'FooAction: Foo=[param1]', ['HTTP/1.1 200 OK'], 200],
            ['foo/param1/', '', ['HTTP/1.1 404 Not Found'], 404],
            ['foobar/param1', '', ['HTTP/1.1 404 Not Found'], 404],
            ['foobar/param1/', 'FooBarAction: Foo=[param1], Bar=[]', ['HTTP/1.1 200 OK'], 200],
            ['foobar/param1/param2', 'FooBarAction: Foo=[param1], Bar=[param2]', ['HTTP/1.1 200 OK'], 200],
            ['foobar/param1/param2/', '', ['HTTP/1.1 404 Not Found'], 404],
            ['foobarbaz/param1/param2', '', ['HTTP/1.1 404 Not Found'], 404],
            ['foobarbaz/param1/param2/', 'FooBarBazAction: Foo=[param1], Bar=[param2], Baz=[]', ['HTTP/1.1 200 OK'], 200],
            ['foobarbaz/param1/param2/param3', 'FooBarBazAction: Foo=[param1], Bar=[param2], Baz=[param3]', ['HTTP/1.1 200 OK'], 200],
            ['foobarbaz/param1/param2/param3/', '', ['HTTP/1.1 404 Not Found'], 404],
        ];
    }

    /**
     * Test page with plugin.
     *
     * @dataProvider pageWithPluginDataProvider
     *
     * @param PluginInterface $plugin             The plugin.
     * @param string          $expectedContent    The expected content.
     * @param array           $expectedHeaders    The expected headers.
     * @param int             $expectedStatusCode The expected status code.
     */
    public function testPageWithPlugin(PluginInterface $plugin, string $expectedContent, array $expectedHeaders, int $expectedStatusCode)
    {
        $_SERVER = ['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/', 'REQUEST_METHOD' => 'GET'];

        $this->application->addPlugin($plugin);
        $request = new Request();
        $response = new Response();
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
     * Data provider for page with plugin tests.
     *
     * @return array The data.
     */
    public function pageWithPluginDataProvider()
    {
        return [
            [new SetHeaderTestPlugin(false, false), 'Hello World!', ['HTTP/1.1 200 OK', 'X-PluginOnPreRequest: 1', 'X-PluginOnPostRequest: 1'], 200],
            [new SetHeaderTestPlugin(false, true), 'Hello World!', ['HTTP/1.1 200 OK', 'X-PluginOnPreRequest: 1', 'X-PluginOnPostRequest: 1'], 200],
            [new SetHeaderTestPlugin(true, false), '', ['HTTP/1.1 200 OK', 'X-PluginOnPreRequest: 1'], 200],
            [new SetHeaderTestPlugin(true, true), '', ['HTTP/1.1 200 OK', 'X-PluginOnPreRequest: 1'], 200],
            [new SetContentTestPlugin(false, false), 'Hello World!onPostRequest', ['HTTP/1.1 200 OK'], 200],
            [new SetContentTestPlugin(false, true), 'Hello World!onPostRequest', ['HTTP/1.1 200 OK'], 200],
            [new SetContentTestPlugin(true, false), 'onPreRequest', ['HTTP/1.1 200 OK'], 200],
            [new SetContentTestPlugin(true, true), 'onPreRequest', ['HTTP/1.1 200 OK'], 200],
        ];
    }

    /**
     * Test get pages with typed hint action parameters.
     *
     * @dataProvider typeHintActionParameterPagesDataProvider
     *
     * @param string $url                The (relative) url.
     * @param string $expectedContent    The expected content.
     * @param array  $expectedHeaders    The expected headers.
     * @param int    $expectedStatusCode The expected status code.
     */
    public function testTypeHintActionParameterPages(string $url, string $expectedContent, array $expectedHeaders, int $expectedStatusCode)
    {
        $_SERVER = ['HTTP_HOST' => 'www.example.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/typeHintActionParameters/' . $url, 'REQUEST_METHOD' => 'GET'];

        $request = new Request();
        $response = new Response();
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
     * Data provider for type hint action parameter pages tests.
     *
     * @return array The data.
     */
    public function typeHintActionParameterPagesDataProvider()
    {
        return [
            ['stringTypes', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['stringTypes/', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['stringTypes/param1', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['stringTypes/param1/', 'StringTypesAction: Foo=[string:param1], Bar=[string:], Baz=[NULL:], FooBar=[string:Foo Bar]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['stringTypes/param1/param2', 'StringTypesAction: Foo=[string:param1], Bar=[string:param2], Baz=[NULL:], FooBar=[string:Foo Bar]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['stringTypes/param1/param2/', 'StringTypesAction: Foo=[string:param1], Bar=[string:param2], Baz=[string:], FooBar=[string:Foo Bar]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['stringTypes/param1/param2/param3', 'StringTypesAction: Foo=[string:param1], Bar=[string:param2], Baz=[string:param3], FooBar=[string:Foo Bar]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['stringTypes/param1/param2/param3/', 'StringTypesAction: Foo=[string:param1], Bar=[string:param2], Baz=[string:param3], FooBar=[string:]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['stringTypes/param1/param2/param3/param4', 'StringTypesAction: Foo=[string:param1], Bar=[string:param2], Baz=[string:param3], FooBar=[string:param4]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['stringTypes/param1/param2/param3/param4/', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['stringTypes/param1/param2/param3/param4/param5', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['stringTypes/10/20.30/false/true', 'StringTypesAction: Foo=[string:10], Bar=[string:20.30], Baz=[string:false], FooBar=[string:true]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['nonExistingAction', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['nonExistingAction/', 'DefaultAction: Action=[string:nonExistingAction], Foo=[string:]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['nonExistingAction/param1', 'DefaultAction: Action=[string:nonExistingAction], Foo=[string:param1]', ['HTTP/1.1 200 OK'], StatusCode::OK],
            ['nonExistingAction/param1/', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
            ['nonExistingAction/param1/param2', '', ['HTTP/1.1 404 Not Found'], StatusCode::NOT_FOUND],
        ];
    }

    /**
     * Set up.
     */
    public function setUp()
    {
        $this->originalServerArray = $_SERVER;

        FakeHeaders::enable();
        $this->application = new BasicTestApplication(FilePath::parse(__DIR__ . DIRECTORY_SEPARATOR));
        $this->application->setViewPath(FilePath::parse('Helpers' . DIRECTORY_SEPARATOR . 'TestViews' . DIRECTORY_SEPARATOR));
        $this->application->addViewRenderer(new JsonTestViewRenderer());
        $this->application->addViewRenderer(new BasicTestViewRenderer());

        $this->application->addRoute(new Route('', BasicTestController::class));
        $this->application->addRoute(new Route('view', ViewTestController::class));
        $this->application->addRoute(new Route('customViewPath', CustomViewPathTestController::class));
        $this->application->addRoute(new Route('default', DefaultActionTestController::class));
        $this->application->addRoute(new Route('defaultview', DefaultActionWithViewTestController::class));
        $this->application->addRoute(new Route('actionresult', ActionResultTestController::class));
        $this->application->addRoute(new Route('preandpostactionevent', PreAndPostActionEventController::class));
        $this->application->addRoute(new Route('exception', ExceptionTestController::class));
        $this->application->addRoute(new Route('uppercase', UppercaseActionTestController::class));
        $this->application->addRoute(new Route('multilevel', MultiLevelTestController::class));
        $this->application->addRoute(new Route('actionMethodVisibility', ActionMethodVisibilityTestController::class));
        $this->application->addRoute(new Route('specialActionName', SpecialActionNameTestController::class));
        $this->application->addRoute(new Route('cookies', CookieTestController::class));
        $this->application->addRoute(new Route('multiple/level', MultiLevelTestController::class));
        $this->application->addRoute(new Route('typeHintActionParameters', TypeHintActionParametersTestController::class));
    }

    /**
     * Tear down.
     */
    public function tearDown()
    {
        $_COOKIE = [];

        FakeHeaders::disable();
        $this->application = null;

        $_SERVER = $this->originalServerArray;
    }

    /**
     * @var BasicTestApplication My application.
     */
    private $application;

    /**
     * @var array The original server array.
     */
    private $originalServerArray;
}
