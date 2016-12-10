<?php

use BlueMvc\Core\Application;
use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Request;
use BlueMvc\Core\Response;

require_once __DIR__ . '/Helpers/TestControllers/ActionResultTestController.php';
require_once __DIR__ . '/Helpers/TestControllers/BasicTestController.php';
require_once __DIR__ . '/Helpers/TestControllers/DefaultActionTestController.php';

/**
 * Test Controller class.
 */
class Controller extends PHPUnit_Framework_TestCase
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

        $this->assertSame($application, $controller->getApplication());
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

        $this->assertSame($request, $controller->getRequest());
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

        $this->assertSame($response, $controller->getResponse());
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

        $this->assertTrue($isProcessed);
        $this->assertSame('Hello World!', $response->getContent());
        $this->assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
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

        $this->assertFalse($isProcessed);
        $this->assertSame('', $response->getContent());
        $this->assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
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

        $this->assertTrue($isProcessed);
        $this->assertSame('Foo Action', $response->getContent());
        $this->assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
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

        $this->assertTrue($isProcessed);
        $this->assertSame('Default Action bar', $response->getContent());
        $this->assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
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

        $this->assertTrue($isProcessed);
        $this->assertSame('Page was not found', $response->getContent());
        $this->assertSame(StatusCode::NOT_FOUND, $response->getStatusCode()->getCode());
    }

    /**
     * Test getViewData method.
     */
    public function testGetViewData()
    {
        $controller = new BasicTestController();

        $this->assertNull($controller->getViewData('Foo'));
        $this->assertNull($controller->getViewData('Bar'));
    }

    /**
     * Test setViewData method.
     */
    public function testSetViewData()
    {
        $controller = new BasicTestController();
        $controller->setViewData('Foo', 42);

        $this->assertSame(42, $controller->getViewData('Foo'));
        $this->assertNull($controller->getViewData('Bar'));
    }
}
