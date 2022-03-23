<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests;

use BlueMvc\Core\Collections\ViewItemCollection;
use BlueMvc\Core\Http\Method;
use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Tests\Helpers\TestApplications\BasicTestApplication;
use BlueMvc\Core\Tests\Helpers\TestControllers\ActionMethodVisibilityTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\ActionResultExceptionTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\ActionResultTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\ActionResultWithPostActionEventTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\BasicTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\CustomViewPathTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\DefaultActionTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\MultiLevelTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\PreAndPostActionEventController;
use BlueMvc\Core\Tests\Helpers\TestControllers\PreAndPostActionEventExceptionController;
use BlueMvc\Core\Tests\Helpers\TestControllers\SpecialActionNameTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\TypeHintActionParametersTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\UppercaseActionTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\ViewTestController;
use BlueMvc\Core\Tests\Helpers\TestRequests\BasicTestRequest;
use BlueMvc\Core\Tests\Helpers\TestResponses\BasicTestResponse;
use BlueMvc\Core\Tests\Helpers\TestViewRenderers\BasicTestViewRenderer;
use BlueMvc\Core\Tests\Helpers\TestViewRenderers\JsonTestViewRenderer;
use DataTypes\Net\Url;
use DataTypes\System\FilePath;
use PHPUnit\Framework\TestCase;

/**
 * Test Controller class.
 */
class ControllerTest extends TestCase
{
    /**
     * Test getApplication method.
     */
    public function testGetApplication()
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('http://www.domain.com/'), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new BasicTestController();
        $controller->processRequest($application, $request, $response, '');

        self::assertSame($application, $controller->getApplication());
    }

    /**
     * Test getRequest method.
     */
    public function testGetRequest()
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('http://www.domain.com/'), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new BasicTestController();
        $controller->processRequest($application, $request, $response, '');

        self::assertSame($request, $controller->getRequest());
    }

    /**
     * Test getResponse method.
     */
    public function testGetResponse()
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('http://www.domain.com/'), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new BasicTestController();
        $controller->processRequest($application, $request, $response, '');

        self::assertSame($response, $controller->getResponse());
    }

    /**
     * Test getViewItems method.
     */
    public function testGetViewItems()
    {
        $controller = new BasicTestController();

        self::assertSame([], iterator_to_array($controller->getViewItems(), true));
    }

    /**
     * Test setViewItems method.
     */
    public function testSetViewItems()
    {
        $controller = new BasicTestController();
        $viewItems = new ViewItemCollection();
        $viewItems->set('Foo', 'Bar');
        $viewItems->set('One', 1);
        $controller->setViewItems($viewItems);

        self::assertSame(['Foo' => 'Bar', 'One' => 1], iterator_to_array($controller->getViewItems(), true));
    }

    /**
     * Test setViewItem method.
     */
    public function testSetViewItem()
    {
        $controller = new BasicTestController();
        $controller->setViewItem('Foo', 'Bar');
        $controller->setViewItem('Baz', ['One' => 1, 'Two' => 2]);

        self::assertSame(['Foo' => 'Bar', 'Baz' => ['One' => 1, 'Two' => 2]], iterator_to_array($controller->getViewItems(), true));
    }

    /**
     * Test getViewItem method.
     */
    public function testGetViewItem()
    {
        $controller = new BasicTestController();
        $controller->setViewItem('Foo', 'Bar');
        $controller->setViewItem('Baz', ['One' => 1, 'Two' => 2]);

        self::assertSame('Bar', $controller->getViewItem('Foo'));
        self::assertSame(['One' => 1, 'Two' => 2], $controller->getViewItem('Baz'));
        self::assertNull($controller->getViewItem('Bar'));
    }

    /**
     * Test getActionMethod method for index action.
     */
    public function testGetActionMethodForIndexAction()
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('http://www.domain.com/'), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new BasicTestController();
        $controller->processRequest($application, $request, $response, '', []);

        self::assertSame('indexAction', $controller->getActionMethod()->getName());
    }

    /**
     * Test getActionMethod method for custom action.
     */
    public function testGetActionMethodForCustomAction()
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('http://www.domain.com/'), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new BasicTestController();
        $controller->processRequest($application, $request, $response, 'int', []);

        self::assertSame('intAction', $controller->getActionMethod()->getName());
    }

    /**
     * Test getActionMethod method for default action.
     */
    public function testGetActionMethodForDefaultAction()
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('http://www.domain.com/'), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new DefaultActionTestController();
        $controller->processRequest($application, $request, $response, 'bar', []);

        self::assertSame('defaultAction', $controller->getActionMethod()->getName());
    }

    /**
     * Test getActionMethod method for invalid action.
     */
    public function testGetActionMethodForInvalidAction()
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('http://www.domain.com/'), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new BasicTestController();
        $controller->processRequest($application, $request, $response, 'foo', []);

        self::assertNull($controller->getActionMethod());
    }

    /**
     * Test process BasicTestController.
     *
     * @dataProvider processBasicTestControllerDataProvider
     *
     * @param string $path               The path.
     * @param array  $queryParameters    The query parameters.
     * @param int    $expectedStatusCode The expected status code.
     * @param string $expectedContent    The expected content.
     */
    public function testProcessBasicTestController(string $path, array $queryParameters, int $expectedStatusCode, string $expectedContent)
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $application->setViewPath(FilePath::parse(__DIR__ . DIRECTORY_SEPARATOR . 'Helpers' . DIRECTORY_SEPARATOR . 'TestViews' . DIRECTORY_SEPARATOR));
        $application->addViewRenderer(new BasicTestViewRenderer());
        $request = new BasicTestRequest(Url::parse('https://www.example.com/'), new Method('GET'));
        foreach ($queryParameters as $name => $value) {
            $request->setQueryParameter($name, $value);
        }
        $response = new BasicTestResponse();
        $controller = new BasicTestController();
        $controller->processRequest($application, $request, $response, $path);

        self::assertSame($expectedStatusCode, $response->getStatusCode()->getCode());
        self::assertSame($expectedContent, self::normalizeEndOfLine($response->getContent()));
    }

    /**
     * Data provider for testProcessBasicController.
     *
     * @return array
     */
    public function processBasicTestControllerDataProvider(): array
    {
        return [
            ['', [], StatusCode::OK, 'Hello World!'],
            ['nonExistingAction', [], StatusCode::NOT_FOUND, ''],
            ['serverError', [], StatusCode::INTERNAL_SERVER_ERROR, 'Server Error'],
            ['123numeric', [], StatusCode::OK, 'Numeric action result'],
            ['int', [], StatusCode::OK, '42'],
            ['false', [], StatusCode::OK, 'false'],
            ['true', [], StatusCode::OK, 'true'],
            ['null', [], StatusCode::OK, 'Content set manually.'],
            ['object', [], StatusCode::OK, 'object'],
            ['stringable', [], StatusCode::OK, 'Text is "Bar"'],
            ['actionResult', [], StatusCode::NOT_MODIFIED, ''],
            ['view', [], StatusCode::OK, "<html><body><h1>viewAction</h1></body></html>\n"],
            ['viewOrActionResult', ['showView' => '1'], StatusCode::OK, "<html><body><h1>viewOrActionResultAction</h1></body></html>\n"],
            ['viewOrActionResult', ['showView' => '0'], StatusCode::NO_CONTENT, ''],
        ];
    }

    /**
     * Test process DefaultActionTestController.
     *
     * @dataProvider processDefaultActionControllerDataProvider
     *
     * @param string $path            The path.
     * @param string $expectedContent The expected content.
     */
    public function testProcessDefaultActionController(string $path, string $expectedContent)
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('https://www.example.com/'), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new DefaultActionTestController();
        $controller->processRequest($application, $request, $response, $path);

        self::assertSame($expectedContent, $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Data provider for testProcessDefaultActionController.
     *
     * @return array
     */
    public function processDefaultActionControllerDataProvider(): array
    {
        return [
            ['foo', 'Foo Action'],
            ['bar', 'Default Action bar'],
            ['', 'Default Action '],
        ];
    }

    /**
     * Test process ActionResultTestController.
     *
     * @dataProvider processActionResultTestControllerDataProvider
     *
     * @param string $path               The path.
     * @param int    $expectedStatusCode The expected status code.
     * @param array  $expectedHeaders    The expected headers.
     * @param string $expectedContent    The expected content.
     */
    public function testProcessActionResultTestController(string $path, int $expectedStatusCode, array $expectedHeaders, string $expectedContent)
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('http://www.domain.com/' . $path), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new ActionResultTestController();
        $controller->processRequest($application, $request, $response, $path);

        self::assertSame($expectedStatusCode, $response->getStatusCode()->getCode());
        self::assertSame($expectedHeaders, iterator_to_array($response->getHeaders()));
        self::assertSame($expectedContent, $response->getContent());
    }

    /**
     * Test process ActionResultExceptionTestController.
     *
     * @dataProvider processActionResultTestControllerDataProvider
     *
     * @param string $path               The path.
     * @param int    $expectedStatusCode The expected status code.
     * @param array  $expectedHeaders    The expected headers.
     * @param string $expectedContent    The expected content.
     */
    public function testProcessActionResultExceptionTestController(string $path, int $expectedStatusCode, array $expectedHeaders, string $expectedContent)
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('http://www.domain.com/' . $path), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new ActionResultExceptionTestController();
        $controller->processRequest($application, $request, $response, $path);

        self::assertSame($expectedStatusCode, $response->getStatusCode()->getCode());
        self::assertSame($expectedHeaders, iterator_to_array($response->getHeaders()));
        self::assertSame($expectedContent, $response->getContent());
    }

    /**
     * Data provider for testProcessActionResultTestController and testProcessActionResultExceptionTestController.
     *
     * @return array
     */
    public function processActionResultTestControllerDataProvider(): array
    {
        return [
            ['notFound', StatusCode::NOT_FOUND, [], 'Page was not found'],
            ['redirect', StatusCode::FOUND, ['Location' => 'http://www.domain.com/foo/bar'], ''],
            ['permanentRedirect', StatusCode::MOVED_PERMANENTLY, ['Location' => 'https://domain.com/'], ''],
            ['forbidden', StatusCode::FORBIDDEN, [], 'Page is forbidden'],
            ['noContent', StatusCode::NO_CONTENT, [], ''],
            ['notModified', StatusCode::NOT_MODIFIED, [], ''],
            ['methodNotAllowed', StatusCode::METHOD_NOT_ALLOWED, [], ''],
            ['json', StatusCode::OK, ['Content-Type' => 'application/json'], '{"Foo":1,"Bar":{"Baz":2}}'],
            ['created', StatusCode::CREATED, ['Location' => 'https://example.com/created'], ''],
            ['badRequest', StatusCode::BAD_REQUEST, [], 'The request was bad'],
            ['unauthorized', StatusCode::UNAUTHORIZED, ['WWW-Authenticate' => 'Basic realm="Foo"'], ''],
            ['custom', StatusCode::MULTI_STATUS, [], 'Custom action result'],
        ];
    }

    /**
     * Test process ViewTestController.
     *
     * @dataProvider processViewTestControllerDataProvider
     *
     * @param string $path            The path.
     * @param string $expectedContent The expected content.
     */
    public function testProcessViewTestController(string $path, string $expectedContent)
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $application->setViewPath(FilePath::parse(__DIR__ . '/Helpers/TestViews/'));
        $application->addViewRenderer(new JsonTestViewRenderer());
        $application->addViewRenderer(new BasicTestViewRenderer());
        $request = new BasicTestRequest(Url::parse('http://www.domain.com/'), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new ViewTestController();
        $controller->processRequest($application, $request, $response, $path);

        self::assertSame($expectedContent, self::normalizeEndOfLine($response->getContent()));
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Data provider for testProcessViewTestController.
     *
     * @return array
     */
    public function processViewTestControllerDataProvider(): array
    {
        return [
            ['', '<html><body><h1>Index</h1><span>' . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'www' . DIRECTORY_SEPARATOR . "</span><em>http://www.domain.com/</em></body></html>\n"],
            ['alternate', "{\"Model\":\"This is the model.\",\"ViewItems\":{\"Foo\":\"Bar\"}}\n"],
            ['onlyjson', "{\"Model\":\"This is the model.\"}\n"],
            ['withmodel', '<html><body><h1>With model</h1><span>' . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'www' . DIRECTORY_SEPARATOR . "</span><em>http://www.domain.com/</em><p>This is the model.</p></body></html>\n"],
            ['withviewdata', '<html><body><h1>With model and view data</h1><span>' . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'www' . DIRECTORY_SEPARATOR . "</span><em>http://www.domain.com/</em><p>This is the model.</p><i>This is the view data.</i></body></html>\n"],
            // Skipped 'withnoviewfile' intentionally here.
            ['withcustomviewfile', '<html><body><h1>Custom view file</h1><span>' . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'www' . DIRECTORY_SEPARATOR . "</span><em>http://www.domain.com/</em><p>This is the model.</p></body></html>\n"],
        ];
    }

    /**
     * Test process CustomViewPathTestController.
     */
    public function testProcessCustomViewPathController()
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $application->setViewPath(FilePath::parse(__DIR__ . '/Helpers/TestViews/'));
        $application->addViewRenderer(new BasicTestViewRenderer());

        $request = new BasicTestRequest(Url::parse('http://www.domain.com/'), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new CustomViewPathTestController();
        $controller->processRequest($application, $request, $response, '');

        self::assertSame("<html><body><h1>View in custom view path</h1></body></html>\n", self::normalizeEndOfLine($response->getContent()));
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test process PreAndPostActionEventController.
     *
     * @dataProvider processPreAndPostActionEventControllerDataProvider
     *
     * @param string $action             The action.
     * @param int    $port               The port.
     * @param int    $expectedStatusCode The expected status code.
     * @param array  $expectedHeaders    The expected headers.
     * @param string $expectedContent    The expected content.
     */
    public function testProcessPreAndPostActionEventController(string $action, int $port, int $expectedStatusCode, array $expectedHeaders, string $expectedContent)
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('http://www.domain.com:' . $port . '/' . $action), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new PreAndPostActionEventController();
        $controller->processRequest($application, $request, $response, $action);

        self::assertSame($expectedStatusCode, $response->getStatusCode()->getCode());
        self::assertSame($expectedHeaders, iterator_to_array($response->getHeaders()));
        self::assertSame($expectedContent, $response->getContent());
    }

    /**
     * Test process PreAndPostActionEventExceptionController.
     *
     * @dataProvider processPreAndPostActionEventControllerDataProvider
     *
     * @param string $action             The action.
     * @param int    $port               The port.
     * @param int    $expectedStatusCode The expected status code.
     * @param array  $expectedHeaders    The expected headers.
     * @param string $expectedContent    The expected content.
     */
    public function testProcessPreAndPostActionEventExceptionController(string $action, int $port, int $expectedStatusCode, array $expectedHeaders, string $expectedContent)
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('http://www.domain.com:' . $port . '/' . $action), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new PreAndPostActionEventExceptionController();
        $controller->processRequest($application, $request, $response, $action);

        self::assertSame($expectedStatusCode, $response->getStatusCode()->getCode());
        self::assertSame($expectedHeaders, iterator_to_array($response->getHeaders()));
        self::assertSame($expectedContent, $response->getContent());
    }

    /**
     * Data provider testProcessPreAndPostActionEventController and testProcessPreAndPostActionEventExceptionController.
     *
     * @return array The data.
     */
    public function processPreAndPostActionEventControllerDataProvider(): array
    {
        return [
            ['', 80, StatusCode::OK, ['X-Pre-Action' => 'true', 'X-Post-Action' => 'true'], 'Index action with pre- and post-action event'],
            ['foo', 80, StatusCode::OK, ['X-Pre-Action' => 'true', 'X-Post-Action' => 'true'], 'Default action "foo" with pre- and post-action event'],
            ['', 81, StatusCode::NOT_FOUND, [], 'This is a pre-action result'],
            ['foo', 81, StatusCode::NOT_FOUND, [], 'This is a pre-action result'],
            ['', 82, StatusCode::OK, ['X-Pre-Action' => 'true'], 'This is a post-action result'],
            ['foo', 82, StatusCode::OK, ['X-Pre-Action' => 'true'], 'This is a post-action result'],
            ['', 83, StatusCode::OK, ['X-Post-Action' => 'true'], 'Index action with pre- and post-action event'],
            ['foo', 83, StatusCode::OK, ['X-Post-Action' => 'true'], 'Default action "foo" with pre- and post-action event'],
            ['', 84, StatusCode::OK, ['X-Pre-Action' => 'true'], 'Index action with pre- and post-action event'],
            ['foo', 84, StatusCode::OK, ['X-Pre-Action' => 'true'], 'Default action "foo" with pre- and post-action event'],
            ['', 85, StatusCode::OK, [], 'Index action with pre- and post-action event'],
            ['foo', 85, StatusCode::OK, [], 'Default action "foo" with pre- and post-action event'],
        ];
    }

    /**
     * Test process UppercaseActionTestController.
     *
     * @dataProvider processUppercaseActionTestControllerDataProvider
     *
     * @param string $path            The path.
     * @param string $expectedContent The expected content.
     */
    public function testProcessUppercaseActionTestController(string $path, string $expectedContent)
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('http://www.domain.com/'), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new UppercaseActionTestController();
        $controller->processRequest($application, $request, $response, $path);

        self::assertSame($expectedContent, $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Data provider for testProcessUppercaseActionTestController.
     *
     * @return array
     */
    public function processUppercaseActionTestControllerDataProvider(): array
    {
        return [
            ['', 'INDEX action'],
            ['bar', 'DEFAULT action "bar"'],
            ['FOO', 'FOO action'],
            ['foo', 'DEFAULT action "foo"'],
        ];
    }

    /**
     * Test process MultiLevelTestController.
     *
     * @dataProvider processMultiLevelTestControllerDataProvider
     *
     * @param string $action             The action.
     * @param array  $parameters         The parameters.
     * @param int    $expectedStatusCode The expected status code.
     * @param string $expectedContent    The expected content.
     */
    public function testProcessMultiLevelTestController(string $action, array $parameters, int $expectedStatusCode, string $expectedContent)
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('http://www.domain.com/'), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new MultiLevelTestController();
        $controller->processRequest($application, $request, $response, $action, $parameters);

        self::assertSame($expectedStatusCode, $response->getStatusCode()->getCode());
        self::assertSame($expectedContent, $response->getContent());
    }

    /**
     * Data provider for testProcessMultiLevelTestController.
     *
     * @return array
     */
    public function processMultiLevelTestControllerDataProvider(): array
    {
        return [
            ['noparams', [], StatusCode::OK, 'No Parameters'],
            ['noparams', ['param1'], StatusCode::NOT_FOUND, ''],
            ['noparams', ['param1', 'param2'], StatusCode::NOT_FOUND, ''],
            ['noparams', ['param1', 'param2', 'param3'], StatusCode::NOT_FOUND, ''],
            ['foo', [], StatusCode::NOT_FOUND, ''],
            ['foo', ['param1'], StatusCode::OK, 'FooAction: Foo=[param1]'],
            ['foo', ['param1', 'param2'], StatusCode::NOT_FOUND, ''],
            ['foo', ['param1', 'param2', 'param3'], StatusCode::NOT_FOUND, ''],
            ['foobar', [], StatusCode::NOT_FOUND, ''],
            ['foobar', ['param1'], StatusCode::NOT_FOUND, ''],
            ['foobar', ['param1', 'param2'], StatusCode::OK, 'FooBarAction: Foo=[param1], Bar=[param2]'],
            ['foobar', ['param1', 'param2', 'param3'], StatusCode::NOT_FOUND, ''],
            ['foobarbaz', [], StatusCode::NOT_FOUND, ''],
            ['foobarbaz', ['param1'], StatusCode::NOT_FOUND, ''],
            ['foobarbaz', ['param1', 'param2'], StatusCode::NOT_FOUND, ''],
            ['foobarbaz', ['param1', 'param2', 'param3'], 200, 'FooBarBazAction: Foo=[param1], Bar=[param2], Baz=[param3]'],
            ['foonull', [], StatusCode::OK, 'FooNullAction: Foo=[*null*]'],
            ['foonull', ['param1'], StatusCode::OK, 'FooNullAction: Foo=[param1]'],
            ['foonull', ['param1', 'param2'], StatusCode::NOT_FOUND, ''],
            ['foonull', ['param1', 'param2', 'param3'], StatusCode::NOT_FOUND, ''],
            ['foonullbarnull', [], StatusCode::OK, 'FooNullBarNullAction: Foo=[*null*], Bar=[*null*]'],
            ['foonullbarnull', ['param1'], StatusCode::OK, 'FooNullBarNullAction: Foo=[param1], Bar=[*null*]'],
            ['foonullbarnull', ['param1', 'param2'], StatusCode::OK, 'FooNullBarNullAction: Foo=[param1], Bar=[param2]'],
            ['foonullbarnull', ['param1', 'param2', 'param3'], StatusCode::NOT_FOUND, ''],
            ['foonullbarnullbaznull', [], StatusCode::OK, 'FooNullBarNullBazNullAction: Foo=[*null*], Bar=[*null*], Baz=[*null*]'],
            ['foonullbarnullbaznull', ['param1'], StatusCode::OK, 'FooNullBarNullBazNullAction: Foo=[param1], Bar=[*null*], Baz=[*null*]'],
            ['foonullbarnullbaznull', ['param1', 'param2'], StatusCode::OK, 'FooNullBarNullBazNullAction: Foo=[param1], Bar=[param2], Baz=[*null*]'],
            ['foonullbarnullbaznull', ['param1', 'param2', 'param3'], StatusCode::OK, 'FooNullBarNullBazNullAction: Foo=[param1], Bar=[param2], Baz=[param3]'],
            ['foobarnull', [], StatusCode::NOT_FOUND, ''],
            ['foobarnull', ['param1'], StatusCode::OK, 'FooBarNullAction: Foo=[param1], Bar=[*null*]'],
            ['foobarnull', ['param1', 'param2'], StatusCode::OK, 'FooBarNullAction: Foo=[param1], Bar=[param2]'],
            ['foobarnull', ['param1', 'param2', 'param3'], StatusCode::NOT_FOUND, ''],
            ['foobarnullbaznull', [], StatusCode::NOT_FOUND, ''],
            ['foobarnullbaznull', ['param1'], StatusCode::OK, 'FooBarNullBazNullAction: Foo=[param1], Bar=[*null*], Baz=[*null*]'],
            ['foobarnullbaznull', ['param1', 'param2'], StatusCode::OK, 'FooBarNullBazNullAction: Foo=[param1], Bar=[param2], Baz=[*null*]'],
            ['foobarnullbaznull', ['param1', 'param2', 'param3'], StatusCode::OK, 'FooBarNullBazNullAction: Foo=[param1], Bar=[param2], Baz=[param3]'],
            ['foobarbazstring', [], StatusCode::NOT_FOUND, ''],
            ['foobarbazstring', ['param1'], StatusCode::NOT_FOUND, ''],
            ['foobarbazstring', ['param1', 'param2'], StatusCode::OK, 'FooBarBazStringAction: Foo=[param1], Bar=[param2], Baz=[default string]'],
            ['foobarbazstring', ['param1', 'param2', 'param3'], StatusCode::OK, 'FooBarBazStringAction: Foo=[param1], Bar=[param2], Baz=[param3]'],
            ['nonexisting', [], StatusCode::NOT_FOUND, ''],
            ['nonexisting', ['param1'], StatusCode::OK, 'DefaultAction: Foo=[nonexisting], Bar=[param1], Baz=[*null*]'],
            ['nonexisting', ['param1', 'param2'], StatusCode::OK, 'DefaultAction: Foo=[nonexisting], Bar=[param1], Baz=[param2]'],
            ['nonexisting', ['param1', 'param2', 'param3'], StatusCode::NOT_FOUND, ''],
        ];
    }

    /**
     * Test process ActionMethodVisibilityTestController.
     *
     * @dataProvider processActionMethodVisibilityTestControllerDataProvider
     *
     * @param string $action             The action.
     * @param int    $expectedStatusCode The expected status code.
     * @param string $expectedContent    The expected content.
     */
    public function testProcessActionMethodVisibilityTestController(string $action, int $expectedStatusCode, string $expectedContent)
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('http://www.domain.com/'), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new ActionMethodVisibilityTestController();
        $controller->processRequest($application, $request, $response, $action, []);

        self::assertSame($expectedStatusCode, $response->getStatusCode()->getCode());
        self::assertSame($expectedContent, $response->getContent());
    }

    /**
     * Data provider for testProcessActionMethodVisibilityTestController.
     *
     * @return array
     */
    public function processActionMethodVisibilityTestControllerDataProvider(): array
    {
        return [
            ['public', StatusCode::OK, 'Public action'],
            ['protected', StatusCode::OK, 'Default action: Action=[protected]'],
            ['private', StatusCode::OK, 'Default action: Action=[private]'],
            ['publicStatic', StatusCode::OK, 'Public static action'],
            ['protectedStatic', StatusCode::OK, 'Default action: Action=[protectedStatic]'],
            ['privateStatic', StatusCode::OK, 'Default action: Action=[privateStatic]'],
        ];
    }

    /**
     * Test process SpecialActionNameTestController.
     *
     * @dataProvider processSpecialActionNameTestControllerDataProvider
     *
     * @param string $action             The action.
     * @param int    $expectedStatusCode The expected status code.
     * @param string $expectedContent    The expected content.
     */
    public function testProcessSpecialActionNameTestController(string $action, int $expectedStatusCode, string $expectedContent)
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('http://www.domain.com/'), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new SpecialActionNameTestController();
        $controller->processRequest($application, $request, $response, $action, []);

        self::assertSame($expectedStatusCode, $response->getStatusCode()->getCode());
        self::assertSame($expectedContent, $response->getContent());
    }

    /**
     * Data provider for testProcessSpecialActionNameTestController.
     *
     * @return array
     */
    public function processSpecialActionNameTestControllerDataProvider(): array
    {
        return [
            ['index', StatusCode::OK, '_index action'],
            ['Index', StatusCode::NOT_FOUND, ''],
            ['', StatusCode::NOT_FOUND, ''],
            ['Default', StatusCode::OK, '_Default action'],
            ['default', StatusCode::NOT_FOUND, ''],
            ['foo', StatusCode::NOT_FOUND, ''],
        ];
    }

    /**
     * Test process TypeHintActionParametersTestController.
     *
     * @dataProvider processTypeHintActionParametersTestControllerDataProvider
     *
     * @param string $action             The action.
     * @param array  $parameters         The parameters.
     * @param int    $expectedStatusCode The expected status code.
     * @param string $expectedContent    The expected content.
     */
    public function testProcessTypeHintActionParametersTestController(string $action, array $parameters, int $expectedStatusCode, string $expectedContent)
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('http://www.domain.com/'), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new TypeHintActionParametersTestController();
        $controller->processRequest($application, $request, $response, $action, $parameters);

        self::assertSame($expectedStatusCode, $response->getStatusCode()->getCode());
        self::assertSame($expectedContent, $response->getContent());
    }

    /**
     * Data provider for testProcessTypeHintActionParametersTestController.
     *
     * @return array
     */
    public function processTypeHintActionParametersTestControllerDataProvider(): array
    {
        return [
            ['noTypes', [], StatusCode::NOT_FOUND, ''],
            ['noTypes', ['param1'], StatusCode::NOT_FOUND, ''],
            ['noTypes', ['param1', 'param2'], StatusCode::OK, 'NoTypesAction: Foo=[string:param1], Bar=[string:param2], Baz=[NULL:], FooBar=[integer:1234]'],
            ['noTypes', ['param1', 'param2', 'param3'], StatusCode::OK, 'NoTypesAction: Foo=[string:param1], Bar=[string:param2], Baz=[string:param3], FooBar=[integer:1234]'],
            ['noTypes', ['param1', 'param2', 'param3', 'param4'], StatusCode::OK, 'NoTypesAction: Foo=[string:param1], Bar=[string:param2], Baz=[string:param3], FooBar=[string:param4]'],
            ['noTypes', ['param1', 'param2', 'param3', 'param4', 'param5'], StatusCode::NOT_FOUND, ''],
            ['noTypes', ['10', '20.30', 'false', 'true'], StatusCode::OK, 'NoTypesAction: Foo=[string:10], Bar=[string:20.30], Baz=[string:false], FooBar=[string:true]'],
            ['stringTypes', [], StatusCode::NOT_FOUND, ''],
            ['stringTypes', ['param1'], StatusCode::NOT_FOUND, ''],
            ['stringTypes', ['param1', 'param2'], StatusCode::OK, 'StringTypesAction: Foo=[string:param1], Bar=[string:param2], Baz=[NULL:], FooBar=[string:Foo Bar]'],
            ['stringTypes', ['param1', 'param2', 'param3'], StatusCode::OK, 'StringTypesAction: Foo=[string:param1], Bar=[string:param2], Baz=[string:param3], FooBar=[string:Foo Bar]'],
            ['stringTypes', ['param1', 'param2', 'param3', 'param4'], StatusCode::OK, 'StringTypesAction: Foo=[string:param1], Bar=[string:param2], Baz=[string:param3], FooBar=[string:param4]'],
            ['stringTypes', ['param1', 'param2', 'param3', 'param4', 'param5'], StatusCode::NOT_FOUND, ''],
            ['stringTypes', ['10', '20.30', 'false', 'true'], StatusCode::OK, 'StringTypesAction: Foo=[string:10], Bar=[string:20.30], Baz=[string:false], FooBar=[string:true]'],
            ['intTypes', [], StatusCode::NOT_FOUND, ''],
            ['intTypes', ['10'], StatusCode::NOT_FOUND, ''],
            ['intTypes', ['10', '20'], StatusCode::OK, 'IntTypesAction: Foo=[integer:10], Bar=[integer:20], Baz=[NULL:], FooBar=[integer:42]'],
            ['intTypes', ['10', '20', '30'], StatusCode::OK, 'IntTypesAction: Foo=[integer:10], Bar=[integer:20], Baz=[integer:30], FooBar=[integer:42]'],
            ['intTypes', ['10', '20', '30', '40'], StatusCode::OK, 'IntTypesAction: Foo=[integer:10], Bar=[integer:20], Baz=[integer:30], FooBar=[integer:40]'],
            ['intTypes', ['10', '20', '30', '40', '50'], StatusCode::NOT_FOUND, ''],
            ['intTypes', ['0', '0'], StatusCode::OK, 'IntTypesAction: Foo=[integer:0], Bar=[integer:0], Baz=[NULL:], FooBar=[integer:42]'],
            ['intTypes', ['0', '-20'], StatusCode::OK, 'IntTypesAction: Foo=[integer:0], Bar=[integer:-20], Baz=[NULL:], FooBar=[integer:42]'],
            ['intTypes', ['10', 'foo'], StatusCode::NOT_FOUND, ''],
            ['intTypes', ['10', '050'], StatusCode::NOT_FOUND, ''],
            ['intTypes', ['10', '+4'], StatusCode::NOT_FOUND, ''],
            ['intTypes', ['12345678901234567890', '-12345678901234567890'], StatusCode::NOT_FOUND, ''],
            ['floatTypes', [], StatusCode::NOT_FOUND, ''],
            ['floatTypes', ['10'], StatusCode::NOT_FOUND, ''],
            ['floatTypes', ['10', '1.5'], StatusCode::OK, 'FloatTypesAction: Foo=[double:10], Bar=[double:1.5], Baz=[NULL:], FooBar=[double:0.5]'],
            ['floatTypes', ['-1', '0.0', '-200E6'], StatusCode::OK, 'FloatTypesAction: Foo=[double:-1], Bar=[double:0], Baz=[double:-200000000], FooBar=[double:0.5]'],
            ['floatTypes', ['9e-4', '0', '2.50', '-1e-10'], StatusCode::OK, 'FloatTypesAction: Foo=[double:0.0009], Bar=[double:0], Baz=[double:2.5], FooBar=[double:-1.0E-10]'],
            ['floatTypes', ['0.1', '2.3', '4.5', '6.7', '8.9'], StatusCode::NOT_FOUND, ''],
            ['floatTypes', ['0', '0'], StatusCode::OK, 'FloatTypesAction: Foo=[double:0], Bar=[double:0], Baz=[NULL:], FooBar=[double:0.5]'],
            ['floatTypes', ['0.0', '0.0'], StatusCode::OK, 'FloatTypesAction: Foo=[double:0], Bar=[double:0], Baz=[NULL:], FooBar=[double:0.5]'],
            ['floatTypes', ['10', 'foo'], StatusCode::NOT_FOUND, ''],
            ['floatTypes', ['0x10', '50'], StatusCode::NOT_FOUND, ''],
            ['floatTypes', ['1E1000', '50'], StatusCode::NOT_FOUND, ''],
            ['floatTypes', ['50', '-1E-1000'], StatusCode::OK, 'FloatTypesAction: Foo=[double:50], Bar=[double:-0], Baz=[NULL:], FooBar=[double:0.5]'],
            ['boolTypes', [], StatusCode::NOT_FOUND, ''],
            ['boolTypes', ['true'], StatusCode::NOT_FOUND, ''],
            ['boolTypes', ['true', 'false'], StatusCode::OK, 'BoolTypesAction: Foo=[boolean:1], Bar=[boolean:], Baz=[NULL:], FooBar=[boolean:1]'],
            ['boolTypes', ['false', 'true', 'false'], StatusCode::OK, 'BoolTypesAction: Foo=[boolean:], Bar=[boolean:1], Baz=[boolean:], FooBar=[boolean:1]'],
            ['boolTypes', ['true', 'false', 'true', 'false'], StatusCode::OK, 'BoolTypesAction: Foo=[boolean:1], Bar=[boolean:], Baz=[boolean:1], FooBar=[boolean:]'],
            ['boolTypes', ['true', 'false', 'true', 'false', 'true'], StatusCode::NOT_FOUND, ''],
            ['boolTypes', ['TRUE', 'false'], StatusCode::NOT_FOUND, ''],
            ['boolTypes', ['true', 'FALSE'], StatusCode::NOT_FOUND, ''],
            ['boolTypes', ['', 'false'], StatusCode::NOT_FOUND, ''],
            ['boolTypes', ['1', 'false'], StatusCode::NOT_FOUND, ''],
            ['boolTypes', ['0', 'false'], StatusCode::NOT_FOUND, ''],
            ['arrayTypes', [], StatusCode::NOT_FOUND, ''],
            ['arrayTypes', ['foo'], StatusCode::NOT_FOUND, ''],
            ['arrayTypes', ['foo', 'bar'], StatusCode::NOT_FOUND, ''],
            ['objectTypes', [], StatusCode::NOT_FOUND, ''],
            ['objectTypes', ['foo'], StatusCode::NOT_FOUND, ''],
            ['objectTypes', ['foo', 'bar'], StatusCode::NOT_FOUND, ''],
            ['mixedTypes', [], StatusCode::NOT_FOUND, ''],
            ['mixedTypes', ['1.0', 'foo', 'bar'], StatusCode::OK, 'MixedTypesAction: TypeFloat=[double:1], TypeNon=[string:foo], TypeString=[string:bar], TypeBool=[boolean:]'],
            ['mixedTypes', ['1.0', '123', 'bar'], StatusCode::OK, 'MixedTypesAction: TypeFloat=[double:1], TypeNon=[string:123], TypeString=[string:bar], TypeBool=[boolean:]'],
            ['mixedTypes', ['1.0', 'foo', '456'], StatusCode::OK, 'MixedTypesAction: TypeFloat=[double:1], TypeNon=[string:foo], TypeString=[string:456], TypeBool=[boolean:]'],
            ['mixedTypes', ['baz', '123', 'bar'], StatusCode::NOT_FOUND, ''],
            ['mixedTypes', ['1.0', '123', 'bar', 'true'], StatusCode::OK, 'MixedTypesAction: TypeFloat=[double:1], TypeNon=[string:123], TypeString=[string:bar], TypeBool=[boolean:1]'],
            ['mixedTypes', ['1.0', '123', 'bar', 'baz'], StatusCode::NOT_FOUND, ''],
            ['nonExistingAction', [], StatusCode::OK, 'DefaultAction: Action=[string:nonExistingAction], Foo=[integer:-1]'],
            ['nonExistingAction', ['1234'], StatusCode::OK, 'DefaultAction: Action=[string:nonExistingAction], Foo=[integer:1234]'],
            ['nonExistingAction', ['param1'], StatusCode::NOT_FOUND, ''],
            ['nonExistingAction', ['1234', 'param2'], StatusCode::NOT_FOUND, ''],
            ['nonExistingAction', ['param1', 'param2'], StatusCode::NOT_FOUND, ''],
        ];
    }

    /**
     * Test process ActionResultWithPostActionEventTestController.
     *
     * @dataProvider processActionResultWithPostActionEventTestControllerDataProvider
     *
     * @param string $action             The action.
     * @param int    $expectedStatusCode The expected status code.
     * @param string $expectedContent    The expected content.
     */
    public function testProcessActionResultWithPostActionEventTestController(string $action, int $expectedStatusCode, string $expectedContent)
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('http://www.domain.com/'), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new ActionResultWithPostActionEventTestController();
        $controller->processRequest($application, $request, $response, $action);

        self::assertSame($application, $controller->getApplication());
        self::assertSame($request, $controller->getRequest());
        self::assertSame($response, $controller->getResponse());
        self::assertSame($expectedStatusCode, $response->getStatusCode()->getCode());
        self::assertSame($expectedContent, $response->getContent());
    }

    /**
     * Data provider for testProcessActionResultWithPostActionEventTestController.
     *
     * @return array
     */
    public function processActionResultWithPostActionEventTestControllerDataProvider(): array
    {
        return [
            ['', 200, 'Ok'],
            ['foo', 200, 'Ok'],
            ['bar', 404, 'Failed with status: 405 Method Not Allowed'],
            ['baz', 404, 'Failed with status: 400 Bad Request'],
        ];
    }

    /**
     * Normalizes the end of line character(s) to \n, so tests will pass even if the newline(s) in tests files are converted, e.g. by Git.
     *
     * @param string $s
     *
     * @return string
     */
    private static function normalizeEndOfLine(string $s): string
    {
        return str_replace("\r\n", "\n", $s);
    }
}
