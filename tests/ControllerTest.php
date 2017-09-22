<?php

namespace BlueMvc\Core\Tests;

use BlueMvc\Core\Application;
use BlueMvc\Core\Collections\ViewItemCollection;
use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Request;
use BlueMvc\Core\Response;
use BlueMvc\Core\Tests\Helpers\TestControllers\ActionMethodVisibilityTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\ActionResultTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\BasicTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\DefaultActionTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\MultiLevelTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\PreAndPostActionEventController;
use BlueMvc\Core\Tests\Helpers\TestControllers\SpecialActionNameTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\UppercaseActionTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\ViewTestController;
use BlueMvc\Core\Tests\Helpers\TestViewRenderers\BasicTestViewRenderer;
use DataTypes\FilePath;

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
        $application = new Application(['DOCUMENT_ROOT' => '/var/www/']);
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        $controller = new BasicTestController();
        $controller->processRequest($application, $request, $response, '');

        self::assertSame($application, $controller->getApplication());
    }

    /**
     * Test getRequest method.
     */
    public function testGetRequest()
    {
        $application = new Application(['DOCUMENT_ROOT' => '/var/www/']);
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        $controller = new BasicTestController();
        $controller->processRequest($application, $request, $response, '');

        self::assertSame($request, $controller->getRequest());
    }

    /**
     * Test getResponse method.
     */
    public function testGetResponse()
    {
        $application = new Application(['DOCUMENT_ROOT' => '/var/www/']);
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
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
        $application = new Application(['DOCUMENT_ROOT' => '/var/www/']);
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/notfound', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        $controller = new BasicTestController();
        $controller->processRequest($application, $request, $response, null);
    }

    /**
     * Test processRequest method for index path.
     */
    public function testProcessRequestForIndexPath()
    {
        $application = new Application(['DOCUMENT_ROOT' => '/var/www/']);
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        $controller = new BasicTestController();
        $isProcessed = $controller->processRequest($application, $request, $response, '');

        self::assertTrue($isProcessed);
        self::assertSame('Hello World!', $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test processRequest method for non existing path.
     */
    public function testProcessRequestForNonExistingPath()
    {
        $application = new Application(['DOCUMENT_ROOT' => '/var/www/']);
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/notfound', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        $controller = new BasicTestController();
        $isProcessed = $controller->processRequest($application, $request, $response, 'notfound');

        self::assertFalse($isProcessed);
        self::assertSame('', $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test an action starting with a numeric character.
     */
    public function testActionStartingWithNumericCharacter()
    {
        $application = new Application(['DOCUMENT_ROOT' => '/var/www/']);
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/123numeric', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        $controller = new BasicTestController();
        $isProcessed = $controller->processRequest($application, $request, $response, '123numeric');

        self::assertTrue($isProcessed);
        self::assertSame('Numeric action result', $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test existing action for controller with default action.
     */
    public function testExistingActionForControllerWithDefaultAction()
    {
        $application = new Application(['DOCUMENT_ROOT' => '/var/www/']);
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        $controller = new DefaultActionTestController();
        $isProcessed = $controller->processRequest($application, $request, $response, 'foo');

        self::assertTrue($isProcessed);
        self::assertSame('Foo Action', $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test non-existing action for controller with default action.
     */
    public function testNonExistingActionForControllerWithDefaultAction()
    {
        $application = new Application(['DOCUMENT_ROOT' => '/var/www/']);
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/bar', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        $controller = new DefaultActionTestController();
        $isProcessed = $controller->processRequest($application, $request, $response, 'bar');

        self::assertTrue($isProcessed);
        self::assertSame('Default Action bar', $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test an action returning a ActionResult.
     */
    public function testActionReturningActionResult()
    {
        $application = new Application(['DOCUMENT_ROOT' => '/var/www/']);
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/notfound', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        $controller = new ActionResultTestController();
        $isProcessed = $controller->processRequest($application, $request, $response, 'notfound');

        self::assertTrue($isProcessed);
        self::assertSame('Page was not found', $response->getContent());
        self::assertSame(StatusCode::NOT_FOUND, $response->getStatusCode()->getCode());
    }

    /**
     * Test an action returning a view.
     */
    public function testActionReturningView()
    {
        $DS = DIRECTORY_SEPARATOR;

        $application = new Application(['DOCUMENT_ROOT' => '/var/www/']);
        $application->setViewPath(FilePath::parse(__DIR__ . $DS . 'Helpers' . $DS . 'TestViews' . $DS));
        $application->addViewRenderer(new BasicTestViewRenderer());

        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        $controller = new ViewTestController();
        $isProcessed = $controller->processRequest($application, $request, $response, '');

        self::assertTrue($isProcessed);
        self::assertSame('<html><body><h1>Index</h1><span>' . $application->getDocumentRoot() . '</span><em>http://www.domain.com/</em></body></html>', $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test an action returning a custom view file.
     */
    public function testActionReturningCustomViewFile()
    {
        $DS = DIRECTORY_SEPARATOR;

        $application = new Application(['DOCUMENT_ROOT' => '/var/www/']);
        $application->setViewPath(FilePath::parse(__DIR__ . $DS . 'Helpers' . $DS . 'TestViews' . $DS));
        $application->addViewRenderer(new BasicTestViewRenderer());

        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/withcustomviewfile', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        $controller = new ViewTestController();
        $isProcessed = $controller->processRequest($application, $request, $response, 'withcustomviewfile');

        self::assertTrue($isProcessed);
        self::assertSame('<html><body><h1>Custom view file</h1><span>' . $application->getDocumentRoot() . '</span><em>http://www.domain.com/withcustomviewfile</em><p>This is the model.</p></body></html>', $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test index action with controller with a pre- and post- action event method.
     */
    public function testIndexActionWithControllerWithPreAndPostActionEventMethod()
    {
        $application = new Application(['DOCUMENT_ROOT' => '/var/www/']);
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        $controller = new PreAndPostActionEventController();
        $isProcessed = $controller->processRequest($application, $request, $response, '');

        self::assertTrue($isProcessed);
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
        $application = new Application(['DOCUMENT_ROOT' => '/var/www/']);
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/foo', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        $controller = new PreAndPostActionEventController();
        $isProcessed = $controller->processRequest($application, $request, $response, 'foo');

        self::assertTrue($isProcessed);
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
        $application = new Application(['DOCUMENT_ROOT' => '/var/www/']);
        $request = new Request(['HTTP_HOST' => 'www.domain.com:81', 'SERVER_PORT' => '81', 'REQUEST_URI' => '/', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        $controller = new PreAndPostActionEventController();
        $isProcessed = $controller->processRequest($application, $request, $response, '');

        self::assertTrue($isProcessed);
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
        $application = new Application(['DOCUMENT_ROOT' => '/var/www/']);
        $request = new Request(['HTTP_HOST' => 'www.domain.com:82', 'SERVER_PORT' => '82', 'REQUEST_URI' => '/', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        $controller = new PreAndPostActionEventController();
        $isProcessed = $controller->processRequest($application, $request, $response, '');

        self::assertTrue($isProcessed);
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
        $application = new Application(['DOCUMENT_ROOT' => '/var/www/']);
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/int', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        $controller = new BasicTestController();
        $isProcessed = $controller->processRequest($application, $request, $response, 'int');

        self::assertTrue($isProcessed);
        self::assertSame('42', $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test an action returning false.
     */
    public function testActionReturningFalse()
    {
        $application = new Application(['DOCUMENT_ROOT' => '/var/www/']);
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/false', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        $controller = new BasicTestController();
        $isProcessed = $controller->processRequest($application, $request, $response, 'false');

        self::assertTrue($isProcessed);
        self::assertSame('false', $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test an action returning true.
     */
    public function testActionReturningTrue()
    {
        $application = new Application(['DOCUMENT_ROOT' => '/var/www/']);
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/true', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        $controller = new BasicTestController();
        $isProcessed = $controller->processRequest($application, $request, $response, 'true');

        self::assertTrue($isProcessed);
        self::assertSame('true', $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test an action returning null.
     */
    public function testActionReturningNull()
    {
        $application = new Application(['DOCUMENT_ROOT' => '/var/www/']);
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/null', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        $controller = new BasicTestController();
        $isProcessed = $controller->processRequest($application, $request, $response, 'null');

        self::assertTrue($isProcessed);
        self::assertSame('Content set manually.', $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test an action returning an object.
     */
    public function testActionReturningObject()
    {
        $application = new Application(['DOCUMENT_ROOT' => '/var/www/']);
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/object', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        $controller = new BasicTestController();
        $isProcessed = $controller->processRequest($application, $request, $response, 'object');

        self::assertTrue($isProcessed);
        self::assertSame('object', $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test an action returning a stringable object.
     */
    public function testActionReturningStringable()
    {
        $application = new Application(['DOCUMENT_ROOT' => '/var/www/']);
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/stringable', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        $controller = new BasicTestController();
        $isProcessed = $controller->processRequest($application, $request, $response, 'stringable');

        self::assertTrue($isProcessed);
        self::assertSame('Text is "Bar"', $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test an index action in uppercase.
     */
    public function testUppercaseIndexAction()
    {
        $application = new Application(['DOCUMENT_ROOT' => '/var/www/']);
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        $controller = new UppercaseActionTestController();
        $isProcessed = $controller->processRequest($application, $request, $response, '');

        self::assertTrue($isProcessed);
        self::assertSame('INDEX action', $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test a default action in uppercase.
     */
    public function testUppercaseDefaultAction()
    {
        $application = new Application(['DOCUMENT_ROOT' => '/var/www/']);
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/bar', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        $controller = new UppercaseActionTestController();
        $isProcessed = $controller->processRequest($application, $request, $response, 'bar');

        self::assertTrue($isProcessed);
        self::assertSame('DEFAULT action "bar"', $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test an uppercase action method with uppercase action.
     */
    public function testUppercaseActionMethodWithUppercaseAction()
    {
        $application = new Application(['DOCUMENT_ROOT' => '/var/www/']);
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/FOO', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        $controller = new UppercaseActionTestController();
        $isProcessed = $controller->processRequest($application, $request, $response, 'FOO');

        self::assertTrue($isProcessed);
        self::assertSame('FOO action', $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test an uppercase action method with uppercase action.
     */
    public function testUppercaseActionMethodWithLowercaseAction()
    {
        $application = new Application(['DOCUMENT_ROOT' => '/var/www/']);
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/FOO', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        $controller = new UppercaseActionTestController();
        $isProcessed = $controller->processRequest($application, $request, $response, 'foo');

        self::assertTrue($isProcessed);
        self::assertSame('DEFAULT action "foo"', $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test multi level actions.
     *
     * @dataProvider multiLevelActionsDataProvider
     *
     * @param string $action              The action.
     * @param array  $parameters          The parameters.
     * @param bool   $expectedIsProcessed True if the excepted result is that action is processed, false otherwise.
     * @param string $expectedContent     The expected content.
     */
    public function testMultiLevelActions($action, array $parameters, $expectedIsProcessed, $expectedContent)
    {
        $application = new Application(['DOCUMENT_ROOT' => '/var/www/']);
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/' . $action, 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        $controller = new MultiLevelTestController();
        $isProcessed = $controller->processRequest($application, $request, $response, $action, $parameters);

        self::assertSame($expectedIsProcessed, $isProcessed);
        self::assertSame($expectedContent, $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Data provider for multi level action tests.
     */
    public function multiLevelActionsDataProvider()
    {
        return [
            ['noparams', [], true, 'No Parameters'],
            ['noparams', ['param1'], false, ''],
            ['noparams', ['param1', 'param2'], false, ''],
            ['noparams', ['param1', 'param2', 'param3'], false, ''],
            ['foo', [], false, ''],
            ['foo', ['param1'], true, 'FooAction: Foo=[param1]'],
            ['foo', ['param1', 'param2'], false, ''],
            ['foo', ['param1', 'param2', 'param3'], false, ''],
            ['foobar', [], false, ''],
            ['foobar', ['param1'], false, ''],
            ['foobar', ['param1', 'param2'], true, 'FooBarAction: Foo=[param1], Bar=[param2]'],
            ['foobar', ['param1', 'param2', 'param3'], false, ''],
            ['foobarbaz', [], false, ''],
            ['foobarbaz', ['param1'], false, ''],
            ['foobarbaz', ['param1', 'param2'], false, ''],
            ['foobarbaz', ['param1', 'param2', 'param3'], true, 'FooBarBazAction: Foo=[param1], Bar=[param2], Baz=[param3]'],
            ['foonull', [], true, 'FooNullAction: Foo=[*null*]'],
            ['foonull', ['param1'], true, 'FooNullAction: Foo=[param1]'],
            ['foonull', ['param1', 'param2'], false, ''],
            ['foonull', ['param1', 'param2', 'param3'], false, ''],
            ['foonullbarnull', [], true, 'FooNullBarNullAction: Foo=[*null*], Bar=[*null*]'],
            ['foonullbarnull', ['param1'], true, 'FooNullBarNullAction: Foo=[param1], Bar=[*null*]'],
            ['foonullbarnull', ['param1', 'param2'], true, 'FooNullBarNullAction: Foo=[param1], Bar=[param2]'],
            ['foonullbarnull', ['param1', 'param2', 'param3'], false, ''],
            ['foonullbarnullbaznull', [], true, 'FooNullBarNullBazNullAction: Foo=[*null*], Bar=[*null*], Baz=[*null*]'],
            ['foonullbarnullbaznull', ['param1'], true, 'FooNullBarNullBazNullAction: Foo=[param1], Bar=[*null*], Baz=[*null*]'],
            ['foonullbarnullbaznull', ['param1', 'param2'], true, 'FooNullBarNullBazNullAction: Foo=[param1], Bar=[param2], Baz=[*null*]'],
            ['foonullbarnullbaznull', ['param1', 'param2', 'param3'], true, 'FooNullBarNullBazNullAction: Foo=[param1], Bar=[param2], Baz=[param3]'],
            ['foobarnull', [], false, ''],
            ['foobarnull', ['param1'], true, 'FooBarNullAction: Foo=[param1], Bar=[*null*]'],
            ['foobarnull', ['param1', 'param2'], true, 'FooBarNullAction: Foo=[param1], Bar=[param2]'],
            ['foobarnull', ['param1', 'param2', 'param3'], false, ''],
            ['foobarnullbaznull', [], false, ''],
            ['foobarnullbaznull', ['param1'], true, 'FooBarNullBazNullAction: Foo=[param1], Bar=[*null*], Baz=[*null*]'],
            ['foobarnullbaznull', ['param1', 'param2'], true, 'FooBarNullBazNullAction: Foo=[param1], Bar=[param2], Baz=[*null*]'],
            ['foobarnullbaznull', ['param1', 'param2', 'param3'], true, 'FooBarNullBazNullAction: Foo=[param1], Bar=[param2], Baz=[param3]'],
            ['foobarbazstring', [], false, ''],
            ['foobarbazstring', ['param1'], false, ''],
            ['foobarbazstring', ['param1', 'param2'], true, 'FooBarBazStringAction: Foo=[param1], Bar=[param2], Baz=[default string]'],
            ['foobarbazstring', ['param1', 'param2', 'param3'], true, 'FooBarBazStringAction: Foo=[param1], Bar=[param2], Baz=[param3]'],
            ['nonexisting', [], false, ''],
            ['nonexisting', ['param1'], true, 'DefaultAction: Foo=[nonexisting], Bar=[param1], Baz=[*null*]'],
            ['nonexisting', ['param1', 'param2'], true, 'DefaultAction: Foo=[nonexisting], Bar=[param1], Baz=[param2]'],
            ['nonexisting', ['param1', 'param2', 'param3'], false, ''],
        ];
    }

    /**
     * Test action method visibility.
     *
     * @dataProvider actionMethodVisibilityDataProvider
     *
     * @param string $action              The action.
     * @param bool   $expectedIsProcessed True if the excepted result is that action is processed, false otherwise.
     * @param string $expectedContent     The expected content.
     */
    public function testActionMethodVisibility($action, $expectedIsProcessed, $expectedContent)
    {
        $application = new Application(['DOCUMENT_ROOT' => '/var/www/']);
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/' . $action, 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        $controller = new ActionMethodVisibilityTestController();
        $isProcessed = $controller->processRequest($application, $request, $response, $action, []);

        self::assertSame($expectedIsProcessed, $isProcessed);
        self::assertSame($expectedContent, $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Data provider for action method visibility tests.
     */
    public function actionMethodVisibilityDataProvider()
    {
        return [
            ['public', true, 'Public action'],
            ['protected', true, 'Default action: Action=[protected]'],
            ['private', true, 'Default action: Action=[private]'],
            ['publicStatic', true, 'Public static action'],
            ['protectedStatic', true, 'Default action: Action=[protectedStatic]'],
            ['privateStatic', true, 'Default action: Action=[privateStatic]'],
        ];
    }

    /**
     * Test actions with special names.
     *
     * @dataProvider specialNameActionDataProvider
     *
     * @param string $action              The action.
     * @param bool   $expectedIsProcessed True if the excepted result is that action is processed, false otherwise.
     * @param string $expectedContent     The expected content.
     */
    public function testSpecialNameAction($action, $expectedIsProcessed, $expectedContent)
    {
        $application = new Application(['DOCUMENT_ROOT' => '/var/www/']);
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/' . $action, 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        $controller = new SpecialActionNameTestController();
        $isProcessed = $controller->processRequest($application, $request, $response, $action, []);

        self::assertSame($expectedIsProcessed, $isProcessed);
        self::assertSame($expectedContent, $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Data provider for special action names tests.
     */
    public function specialNameActionDataProvider()
    {
        return [
            ['index', true, '_index action'],
            ['Index', false, ''],
            ['', false, ''],
            ['Default', true, '_Default action'],
            ['default', false, ''],
            ['foo', false, ''],
        ];
    }
}
