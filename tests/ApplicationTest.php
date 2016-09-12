<?php

use BlueMvc\Core\Application;

require_once __DIR__ . '/Helpers/TestViewRenderers/BasicTestViewRenderer.php';

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
        $this->myApplication->addViewRenderer(new BasicTestViewRenderer());
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
