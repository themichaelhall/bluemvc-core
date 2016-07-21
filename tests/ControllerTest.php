<?php

use BlueMvc\Core\Application;
use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Request;
use BlueMvc\Core\Response;
use BlueMvc\Core\RouteMatch;

require_once __DIR__ . '/Helpers/TestControllers/BasicTestController.php';

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
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/']);
        $response = new Response($request);
        $controller = new BasicTestController();
        $routeMatch = new RouteMatch($controller);
        $controller->processRequest($application, $request, $response, $routeMatch);

        $this->assertSame($application, $controller->getApplication());
    }

    /**
     * Test getRequest method.
     */
    public function testGetRequest()
    {
        $application = new Application(['DOCUMENT_ROOT' => '/var/www/']);
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/']);
        $response = new Response($request);
        $controller = new BasicTestController();
        $routeMatch = new RouteMatch($controller);
        $controller->processRequest($application, $request, $response, $routeMatch);

        $this->assertSame($request, $controller->getRequest());
    }

    /**
     * Test processRequest method for index path.
     */
    public function testProcessRequestForIndexPath()
    {
        $application = new Application(['DOCUMENT_ROOT' => '/var/www/']);
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/']);
        $response = new Response($request);
        $controller = new BasicTestController();
        $routeMatch = new RouteMatch($controller);
        $isProcessed = $controller->processRequest($application, $request, $response, $routeMatch);

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
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/notfound']);
        $response = new Response($request);
        $controller = new BasicTestController();
        $routeMatch = new RouteMatch($controller, 'notfound');
        $isProcessed = $controller->processRequest($application, $request, $response, $routeMatch);

        $this->assertFalse($isProcessed);
        $this->assertSame('', $response->getContent());
        $this->assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }
}
