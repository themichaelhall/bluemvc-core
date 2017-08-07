<?php

namespace BlueMvc\Core\Tests;

use BlueMvc\Core\Application;
use BlueMvc\Core\Collections\ViewItemCollection;
use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Request;
use BlueMvc\Core\Response;
use BlueMvc\Core\Tests\Helpers\TestControllers\ActionResultTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\BasicTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\DefaultActionTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\PreAndPostActionEventController;
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
        self::assertSame('Default action with pre- and post-action event', $response->getContent());
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
     * Test getViewData method.
     */
    public function testGetViewData()
    {
        $controller = new BasicTestController();

        self::assertNull($controller->getViewData('Foo'));
        self::assertNull($controller->getViewData('Bar'));
    }

    /**
     * Test setViewData method.
     */
    public function testSetViewData()
    {
        $controller = new BasicTestController();
        $controller->setViewData('Foo', 42);

        self::assertSame(42, $controller->getViewData('Foo'));
        self::assertNull($controller->getViewData('Bar'));
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
}
