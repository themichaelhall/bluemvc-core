<?php

use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Response;
use BlueMvc\Core\RouteMatch;
use BlueMvc\Fakes\FakeApplication;
use BlueMvc\Fakes\FakeRequest;
use DataTypes\Url;

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
        $request = new FakeRequest(Url::parse('http://www.domain.com/'));
        $response = new Response($request);
        $controller = new BasicTestController();
        $routeMatch = new RouteMatch($controller);
        $controller->processRequest($this->application, $request, $response, $routeMatch);

        $this->assertSame($this->application, $controller->getApplication());
    }

    /**
     * Test getRequest method.
     */
    public function testGetRequest()
    {
        $request = new FakeRequest(Url::parse('http://www.domain.com/'));
        $response = new Response($request);
        $controller = new BasicTestController();
        $routeMatch = new RouteMatch($controller);
        $controller->processRequest($this->application, $request, $response, $routeMatch);

        $this->assertSame($request, $controller->getRequest());
    }

    /**
     * Test getResponse method.
     */
    public function testGetResponse()
    {
        $request = new FakeRequest(Url::parse('http://www.domain.com/'));
        $response = new Response($request);
        $controller = new BasicTestController();
        $routeMatch = new RouteMatch($controller);
        $controller->processRequest($this->application, $request, $response, $routeMatch);

        $this->assertSame($response, $controller->getResponse());
    }

    /**
     * Test processRequest method for index path.
     */
    public function testProcessRequestForIndexPath()
    {
        $request = new FakeRequest(Url::parse('http://www.domain.com/'));
        $response = new Response($request);
        $controller = new BasicTestController();
        $routeMatch = new RouteMatch($controller);
        $isProcessed = $controller->processRequest($this->application, $request, $response, $routeMatch);

        $this->assertTrue($isProcessed);
        $this->assertSame('Hello World!', $response->getContent());
        $this->assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test processRequest method for non existing path.
     */
    public function testProcessRequestForNonExistingPath()
    {
        $request = new FakeRequest(Url::parse('http://www.domain.com/notfound'));
        $response = new Response($request);
        $controller = new BasicTestController();
        $routeMatch = new RouteMatch($controller, 'notfound');
        $isProcessed = $controller->processRequest($this->application, $request, $response, $routeMatch);

        $this->assertFalse($isProcessed);
        $this->assertSame('', $response->getContent());
        $this->assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Set up.
     */
    public function setUp()
    {
        $this->application = new FakeApplication();
    }

    /**
     * Tear down.
     */
    public function tearDown()
    {
        $this->application = null;
    }

    /**
     * @var FakeApplication My application.
     */
    private $application;
}
