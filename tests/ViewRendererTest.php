<?php

use DataTypes\FilePath;

require_once __DIR__ . '/Helpers/TestViewRenderers/BasicTestViewRenderer.php';
require_once __DIR__ . '/Helpers/TestApplications/BasicTestApplication.php';

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
        $DS = DIRECTORY_SEPARATOR;

        $viewRenderer = new BasicTestViewRenderer();
        $result = $viewRenderer->renderView(
            new BasicTestApplication(FilePath::parse($DS . 'var' . $DS . 'www' . $DS)),
            FilePath::parse(__DIR__ . $DS . 'Helpers' . $DS . 'TestViews' . $DS),
            FilePath::parse('ViewTest' . $DS . 'index.view')
        );

        $this->assertSame('<html><body><h1>Index</h1></body></html>', $result);
    }

    /**
     * Test renderView method with model.
     */
    public function testRenderViewWithModel()
    {
        $DS = DIRECTORY_SEPARATOR;

        $viewRenderer = new BasicTestViewRenderer();
        $result = $viewRenderer->renderView(
            new BasicTestApplication(FilePath::parse($DS . 'var' . $DS . 'www' . $DS)),
            FilePath::parse(__DIR__ . $DS . 'Helpers' . $DS . 'TestViews' . $DS),
            FilePath::parse('ViewTest' . $DS . 'withmodel.view'),
            'This is the model.'
        );

        $this->assertSame('<html><body><h1>With model</h1><p>This is the model.</p></body></html>', $result);
    }

    /**
     * Test renderView method with model and view data.
     */
    public function testRenderViewWithViewData()
    {
        $DS = DIRECTORY_SEPARATOR;

        $viewRenderer = new BasicTestViewRenderer();
        $result = $viewRenderer->renderView(
            new BasicTestApplication(FilePath::parse($DS . 'var' . $DS . 'www' . $DS)),
            FilePath::parse(__DIR__ . $DS . 'Helpers' . $DS . 'TestViews' . $DS),
            FilePath::parse('ViewTest' . $DS . 'withviewdata.view'),
            'This is the model.',
            'This is the view data.'
        );

        $this->assertSame('<html><body><h1>With model and view data</h1><p>This is the model.</p><i>This is the view data.</i></body></html>', $result);
    }
}
