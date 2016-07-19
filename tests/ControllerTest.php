<?php

use BlueMvc\Core\Application;
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
    }
}
