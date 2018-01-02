<?php

namespace BlueMvc\Core\Tests;

use BlueMvc\Core\Collections\ViewItemCollection;
use BlueMvc\Core\Http\Method;
use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Tests\Helpers\TestApplications\BasicTestApplication;
use BlueMvc\Core\Tests\Helpers\TestControllers\ActionMethodVisibilityTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\ActionResultTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\BasicTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\CustomViewPathTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\DefaultActionTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\MultiLevelTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\PreAndPostActionEventController;
use BlueMvc\Core\Tests\Helpers\TestControllers\SpecialActionNameTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\UppercaseActionTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\ViewTestController;
use BlueMvc\Core\Tests\Helpers\TestRequests\BasicTestRequest;
use BlueMvc\Core\Tests\Helpers\TestResponses\BasicTestResponse;
use BlueMvc\Core\Tests\Helpers\TestViewRenderers\BasicTestViewRenderer;
use DataTypes\FilePath;
use DataTypes\Url;

/**
 * Test Controller class.
 */
class ControllerTest extends \PHPUnit_Framework_TestCase
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
     * Test processRequest method with invalid action parameter type.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $action parameter is not a string.
     */
    public function testProcessRequestWithInvalidActionParameterType()
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('http://www.domain.com/'), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new BasicTestController();
        $controller->processRequest($application, $request, $response, null);
    }

    /**
     * Test processRequest method for index path.
     */
    public function testProcessRequestForIndexPath()
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('http://www.domain.com/'), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new BasicTestController();
        $controller->processRequest($application, $request, $response, '');

        self::assertSame('Hello World!', $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test processRequest method for non existing path.
     */
    public function testProcessRequestForNonExistingPath()
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('http://www.domain.com/notfound'), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new BasicTestController();
        $controller->processRequest($application, $request, $response, 'notfound');

        self::assertSame('', $response->getContent());
        self::assertSame(StatusCode::NOT_FOUND, $response->getStatusCode()->getCode());
    }

    /**
     * Test an action starting with a numeric character.
     */
    public function testActionStartingWithNumericCharacter()
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('http://www.domain.com/123numeric'), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new BasicTestController();
        $controller->processRequest($application, $request, $response, '123numeric');

        self::assertSame('Numeric action result', $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test existing action for controller with default action.
     */
    public function testExistingActionForControllerWithDefaultAction()
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('http://www.domain.com/foo'), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new DefaultActionTestController();
        $controller->processRequest($application, $request, $response, 'foo');

        self::assertSame('Foo Action', $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test non-existing action for controller with default action.
     */
    public function testNonExistingActionForControllerWithDefaultAction()
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('http://www.domain.com/bar'), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new DefaultActionTestController();
        $controller->processRequest($application, $request, $response, 'bar');

        self::assertSame('Default Action bar', $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test an action returning a ActionResult.
     */
    public function testActionReturningActionResult()
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('http://www.domain.com/notfound'), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new ActionResultTestController();
        $controller->processRequest($application, $request, $response, 'notfound');

        self::assertSame('Page was not found', $response->getContent());
        self::assertSame(StatusCode::NOT_FOUND, $response->getStatusCode()->getCode());
    }

    /**
     * Test an action returning a view.
     */
    public function testActionReturningView()
    {
        $DS = DIRECTORY_SEPARATOR;

        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $application->setViewPath(FilePath::parse(__DIR__ . $DS . 'Helpers' . $DS . 'TestViews' . $DS));
        $application->addViewRenderer(new BasicTestViewRenderer());

        $request = new BasicTestRequest(Url::parse('http://www.domain.com/'), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new ViewTestController();
        $controller->processRequest($application, $request, $response, '');

        self::assertSame('<html><body><h1>Index</h1><span>' . $application->getDocumentRoot() . '</span><em>http://www.domain.com/</em></body></html>', $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test an action returning a custom view file.
     */
    public function testActionReturningCustomViewFile()
    {
        $DS = DIRECTORY_SEPARATOR;

        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $application->setViewPath(FilePath::parse(__DIR__ . $DS . 'Helpers' . $DS . 'TestViews' . $DS));
        $application->addViewRenderer(new BasicTestViewRenderer());

        $request = new BasicTestRequest(Url::parse('http://www.domain.com/withcustomviewfile'), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new ViewTestController();
        $controller->processRequest($application, $request, $response, 'withcustomviewfile');

        self::assertSame('<html><body><h1>Custom view file</h1><span>' . $application->getDocumentRoot() . '</span><em>http://www.domain.com/withcustomviewfile</em><p>This is the model.</p></body></html>', $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test an action returning a custom view file.
     */
    public function testControllerWithCustomViewPath()
    {
        $DS = DIRECTORY_SEPARATOR;

        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $application->setViewPath(FilePath::parse(__DIR__ . $DS . 'Helpers' . $DS . 'TestViews' . $DS));
        $application->addViewRenderer(new BasicTestViewRenderer());

        $request = new BasicTestRequest(Url::parse('http://www.domain.com/'), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new CustomViewPathTestController();
        $controller->processRequest($application, $request, $response, '');

        self::assertSame('<html><body><h1>View in custom view path</h1></body></html>', $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test index action with controller with a pre- and post- action event method.
     */
    public function testIndexActionWithControllerWithPreAndPostActionEventMethod()
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('http://www.domain.com/'), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new PreAndPostActionEventController();
        $controller->processRequest($application, $request, $response, '');

        self::assertSame('Index action with pre- and post-action event', $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
        self::assertSame('true', $response->getHeader('X-Pre-Action'));
        self::assertSame('true', $response->getHeader('X-Post-Action'));
    }

    /**
     * Test default action with controller with a pre- and post- action event method.
     */
    public function testDefaultActionWithControllerWithPreAndPostActionEventMethod()
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('http://www.domain.com/foo'), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new PreAndPostActionEventController();
        $controller->processRequest($application, $request, $response, 'foo');

        self::assertSame('Default action "foo" with pre- and post-action event', $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
        self::assertSame('true', $response->getHeader('X-Pre-Action'));
        self::assertSame('true', $response->getHeader('X-Post-Action'));
    }

    /**
     * Test a pre-action event returning a result.
     */
    public function testPreActionEventReturningResult()
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('http://www.domain.com:81/'), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new PreAndPostActionEventController();
        $controller->processRequest($application, $request, $response, '');

        self::assertSame('This is a pre-action result', $response->getContent());
        self::assertSame(StatusCode::NOT_FOUND, $response->getStatusCode()->getCode());
        self::assertNull($response->getHeader('X-Pre-Action'));
        self::assertNull($response->getHeader('X-Post-Action'));
    }

    /**
     * Test a post-action event returning a result.
     */
    public function testPostActionEventReturningResult()
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('http://www.domain.com:82/'), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new PreAndPostActionEventController();
        $controller->processRequest($application, $request, $response, '');

        self::assertSame('This is a post-action result', $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
        self::assertSame('true', $response->getHeader('X-Pre-Action'));
        self::assertNull($response->getHeader('X-Post-Action'));
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
     * Test an action returning an integer.
     */
    public function testActionReturningInteger()
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('http://www.domain.com/int'), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new BasicTestController();
        $controller->processRequest($application, $request, $response, 'int');

        self::assertSame('42', $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test an action returning false.
     */
    public function testActionReturningFalse()
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('http://www.domain.com/false'), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new BasicTestController();
        $controller->processRequest($application, $request, $response, 'false');

        self::assertSame('false', $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test an action returning true.
     */
    public function testActionReturningTrue()
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('http://www.domain.com/true'), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new BasicTestController();
        $controller->processRequest($application, $request, $response, 'true');

        self::assertSame('true', $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test an action returning null.
     */
    public function testActionReturningNull()
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('http://www.domain.com/null'), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new BasicTestController();
        $controller->processRequest($application, $request, $response, 'null');

        self::assertSame('Content set manually.', $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test an action returning an object.
     */
    public function testActionReturningObject()
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('http://www.domain.com/object'), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new BasicTestController();
        $controller->processRequest($application, $request, $response, 'object');

        self::assertSame('object', $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test an action returning a stringable object.
     */
    public function testActionReturningStringable()
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('http://www.domain.com/stringable'), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new BasicTestController();
        $controller->processRequest($application, $request, $response, 'stringable');

        self::assertSame('Text is "Bar"', $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test an index action in uppercase.
     */
    public function testUppercaseIndexAction()
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('http://www.domain.com/'), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new UppercaseActionTestController();
        $controller->processRequest($application, $request, $response, '');

        self::assertSame('INDEX action', $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test a default action in uppercase.
     */
    public function testUppercaseDefaultAction()
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('http://www.domain.com/bar'), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new UppercaseActionTestController();
        $controller->processRequest($application, $request, $response, 'bar');

        self::assertSame('DEFAULT action "bar"', $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test an uppercase action method with uppercase action.
     */
    public function testUppercaseActionMethodWithUppercaseAction()
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('http://www.domain.com/FOO'), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new UppercaseActionTestController();
        $controller->processRequest($application, $request, $response, 'FOO');

        self::assertSame('FOO action', $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test an uppercase action method with uppercase action.
     */
    public function testUppercaseActionMethodWithLowercaseAction()
    {
        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $request = new BasicTestRequest(Url::parse('http://www.domain.com/foo'), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new UppercaseActionTestController();
        $controller->processRequest($application, $request, $response, 'foo');

        self::assertSame('DEFAULT action "foo"', $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test multi level actions.
     *
     * @dataProvider multiLevelActionsDataProvider
     *
     * @param string $action             The action.
     * @param array  $parameters         The parameters.
     * @param int    $expectedStatusCode The expected status code.
     * @param string $expectedContent    The expected content.
     */
    public function testMultiLevelActions($action, array $parameters, $expectedStatusCode, $expectedContent)
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
     * Data provider for multi level action tests.
     */
    public function multiLevelActionsDataProvider()
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
     * Test action method visibility.
     *
     * @dataProvider actionMethodVisibilityDataProvider
     *
     * @param string $action             The action.
     * @param int    $expectedStatusCode The expected status code.
     * @param string $expectedContent    The expected content.
     */
    public function testActionMethodVisibility($action, $expectedStatusCode, $expectedContent)
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
     * Data provider for action method visibility tests.
     */
    public function actionMethodVisibilityDataProvider()
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
     * Test actions with special names.
     *
     * @dataProvider specialNameActionDataProvider
     *
     * @param string $action             The action.
     * @param int    $expectedStatusCode The expected status code.
     * @param string $expectedContent    The expected content.
     */
    public function testSpecialNameAction($action, $expectedStatusCode, $expectedContent)
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
     * Data provider for special action names tests.
     */
    public function specialNameActionDataProvider()
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
        $request = new BasicTestRequest(Url::parse('http://www.domain.com/int'), new Method('GET'));
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
        $request = new BasicTestRequest(Url::parse('http://www.domain.com/bar'), new Method('GET'));
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
        $request = new BasicTestRequest(Url::parse('http://www.domain.com/foo'), new Method('GET'));
        $response = new BasicTestResponse();
        $controller = new BasicTestController();
        $controller->processRequest($application, $request, $response, 'foo', []);

        self::assertNull($controller->getActionMethod());
    }
}
