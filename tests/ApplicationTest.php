<?php

use BlueMvc\Core\Application;
use BlueMvc\Core\Route;
use DataTypes\FilePath;

require_once __DIR__ . '/Helpers/TestViewRenderers/BasicTestViewRenderer.php';
require_once __DIR__ . '/Helpers/TestControllers/BasicTestController.php';

/**
 * Test Application class.
 */
class ApplicationTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test getDocumentRoot method.
     */
    public function testGetDocumentRoot()
    {
        $DS = DIRECTORY_SEPARATOR;

        $this->assertSame($DS . 'var' . $DS . 'www' . $DS, $this->myApplication->getDocumentRoot()->__toString());
    }

    /**
     * Test get viewRenderers method.
     */
    public function testGetViewRenderers()
    {
        $viewRenderers = $this->myApplication->getViewRenderers();

        $this->assertSame(1, count($viewRenderers));
        $this->assertInstanceOf(BasicTestViewRenderer::class, $viewRenderers[0]);
    }

    /**
     * Test getViewPath method.
     */
    public function testGetViewPath()
    {
        $DS = DIRECTORY_SEPARATOR;

        $this->assertSame($DS . 'var' . $DS . 'www' . $DS . 'views' . $DS, $this->myApplication->getViewPath()->__toString());
    }

    /**
     * Test getRoutes method.
     */
    public function testGetRoutes()
    {
        $routes = $this->myApplication->getRoutes();

        $this->assertSame(1, count($routes));
    }

    /**
     * Set up.
     */
    public function setUp()
    {
        $DS = DIRECTORY_SEPARATOR;

        $this->myApplication = new Application(
            [
                'DOCUMENT_ROOT' => $DS . 'var' . $DS . 'www',
            ]
        );

        $this->myApplication->setViewPath(FilePath::parse('views' . $DS));
        $this->myApplication->addViewRenderer(new BasicTestViewRenderer());
        $this->myApplication->addRoute(new Route('', BasicTestController::class));
    }

    /**
     * Tear down.
     */
    public function tearDown()
    {
        $this->myApplication = null;
    }

    /**
     * @var Application My application.
     */
    private $myApplication;
}
