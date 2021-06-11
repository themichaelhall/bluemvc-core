<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests;

use BlueMvc\Core\Collections\ViewItemCollection;
use BlueMvc\Core\Http\Method;
use BlueMvc\Core\Tests\Helpers\TestApplications\BasicTestApplication;
use BlueMvc\Core\Tests\Helpers\TestRequests\BasicTestRequest;
use BlueMvc\Core\Tests\Helpers\TestViewRenderers\BasicTestViewRenderer;
use DataTypes\Net\Url;
use DataTypes\System\FilePath;
use PHPUnit\Framework\TestCase;

/**
 * Test ViewRenderer class.
 */
class ViewRendererTest extends TestCase
{
    /**
     * Test renderView method with empty model.
     */
    public function testRenderViewWithEmptyModel()
    {
        $DS = DIRECTORY_SEPARATOR;

        $application = new BasicTestApplication(FilePath::parse($DS . 'var' . $DS . 'www' . $DS));
        $application->setViewPath(FilePath::parse(__DIR__ . $DS . 'Helpers' . $DS . 'TestViews' . $DS));
        $request = new BasicTestRequest(Url::parse('http://localhost/foo'), new Method('GET'));

        $viewRenderer = new BasicTestViewRenderer();
        $result = $viewRenderer->renderView(
            $application,
            $request,
            FilePath::parse('ViewTest' . $DS . 'index.view')
        );

        self::assertSame('<html><body><h1>Index</h1><span>' . $DS . 'var' . $DS . 'www' . $DS . "</span><em>http://localhost/foo</em></body></html>\n", self::normalizeEndOfLine($result));
    }

    /**
     * Test renderView method with model.
     */
    public function testRenderViewWithModel()
    {
        $DS = DIRECTORY_SEPARATOR;

        $application = new BasicTestApplication(FilePath::parse($DS . 'var' . $DS . 'www' . $DS));
        $application->setViewPath(FilePath::parse(__DIR__ . $DS . 'Helpers' . $DS . 'TestViews' . $DS));
        $request = new BasicTestRequest(Url::parse('http://localhost/bar'), new Method('GET'));

        $viewRenderer = new BasicTestViewRenderer();
        $result = $viewRenderer->renderView(
            $application,
            $request,
            FilePath::parse('ViewTest' . $DS . 'withmodel.view'),
            'This is the model.'
        );

        self::assertSame('<html><body><h1>With model</h1><span>' . $DS . 'var' . $DS . 'www' . $DS . "</span><em>http://localhost/bar</em><p>This is the model.</p></body></html>\n", self::normalizeEndOfLine($result));
    }

    /**
     * Test renderView method with model and view data.
     */
    public function testRenderViewWithViewData()
    {
        $DS = DIRECTORY_SEPARATOR;

        $application = new BasicTestApplication(FilePath::parse($DS . 'var' . $DS . 'www' . $DS));
        $application->setViewPath(FilePath::parse(__DIR__ . $DS . 'Helpers' . $DS . 'TestViews' . $DS));
        $request = new BasicTestRequest(Url::parse('http://localhost/baz'), new Method('GET'));
        $viewItems = new ViewItemCollection();
        $viewItems->set('Foo', 'This is the view data.');

        $viewRenderer = new BasicTestViewRenderer();
        $result = $viewRenderer->renderView(
            $application,
            $request,
            FilePath::parse('ViewTest' . $DS . 'withviewdata.view'),
            'This is the model.',
            $viewItems
        );

        self::assertSame('<html><body><h1>With model and view data</h1><span>' . $DS . 'var' . $DS . 'www' . $DS . "</span><em>http://localhost/baz</em><p>This is the model.</p><i>This is the view data.</i></body></html>\n", self::normalizeEndOfLine($result));
    }

    /**
     * Normalizes the end of line character(s) to \n, so tests will pass even if the newline(s) in tests files are converted, e.g. by Git.
     *
     * @param string $s
     *
     * @return string
     */
    private static function normalizeEndOfLine(string $s): string
    {
        return str_replace("\r\n", "\n", $s);
    }
}
