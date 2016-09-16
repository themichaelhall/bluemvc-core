<?php

use DataTypes\FilePath;

require_once __DIR__ . '/Helpers/TestViewRenderers/BasicTestViewRenderer.php';

/**
 * Test ViewRenderer class.
 */
class ViewRendererTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test renderView method with empty model.
     */
    public function testRenderViewWithEmptyModel()
    {
        $viewRenderer = new BasicTestViewRenderer();
        $result = $viewRenderer->renderView(
            FilePath::parse(__DIR__ . DIRECTORY_SEPARATOR . 'Helpers' . DIRECTORY_SEPARATOR . 'TestViews' . DIRECTORY_SEPARATOR),
            FilePath::parse('ViewTest' . DIRECTORY_SEPARATOR . 'index.view')
        );

        $this->assertSame('<html><body><h1>Index</h1></body></html>', $result);
    }

    /**
     * Test renderView method with model.
     */
    public function testRenderViewWithModel()
    {
        $viewRenderer = new BasicTestViewRenderer();
        $result = $viewRenderer->renderView(
            FilePath::parse(__DIR__ . DIRECTORY_SEPARATOR . 'Helpers' . DIRECTORY_SEPARATOR . 'TestViews' . DIRECTORY_SEPARATOR),
            FilePath::parse('ViewTest' . DIRECTORY_SEPARATOR . 'withmodel.view'),
            'This is the model.'
        );

        $this->assertSame('<html><body><h1>With model</h1><p>This is the model.</p></body></html>', $result);
    }
}
