<?php

use BlueMvc\Core\Application;
use BlueMvc\Core\Request;
use BlueMvc\Core\Response;
use BlueMvc\Core\Route;

require_once __DIR__ . '/Helpers/TestControllers/BasicTestController.php';

/**
 * Test basic routing for a application.
 */
class BasicRoutingTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test get index page.
     */
    public function testGetIndexPage()
    {
        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/']);
        $response = new Response($request);
        ob_start();
        $this->application->run($request, $response);
        $responseOutput = ob_get_contents();
        ob_end_clean();

        $this->assertSame('Hello World!', $responseOutput);
    }

    /**
     * Set up.
     */
    public function setUp()
    {
        $this->application = new Application(['DOCUMENT_ROOT' => '/var/www/']);
        $this->application->addRoute(new Route('', BasicTestController::class));
    }

    /**
     * Tear down.
     */
    public function tearDown()
    {
        $this->application = null;
    }

    /**
     * @var Application My application.
     */
    private $application;
}
