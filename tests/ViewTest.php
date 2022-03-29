<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests;

use BlueMvc\Core\Collections\ViewItemCollection;
use BlueMvc\Core\Exceptions\InvalidViewFileException;
use BlueMvc\Core\Exceptions\MissingViewRendererException;
use BlueMvc\Core\Exceptions\ViewFileNotFoundException;
use BlueMvc\Core\Http\Method;
use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Tests\Helpers\TestApplications\BasicTestApplication;
use BlueMvc\Core\Tests\Helpers\TestRequests\BasicTestRequest;
use BlueMvc\Core\Tests\Helpers\TestResponses\BasicTestResponse;
use BlueMvc\Core\Tests\Helpers\TestViewRenderers\BasicTestViewRenderer;
use BlueMvc\Core\Tests\Helpers\TestViewRenderers\JsonTestViewRenderer;
use BlueMvc\Core\View;
use DataTypes\Net\Url;
use DataTypes\System\FilePath;
use PHPUnit\Framework\TestCase;

/**
 * Test View class.
 */
class ViewTest extends TestCase
{
    /**
     * Test create view with no model.
     */
    public function testCreateViewWithNoModel()
    {
        $view = new View();

        self::assertNull($view->getModel());
        self::assertNull($view->getFile());
    }

    /**
     * Test create view with model.
     */
    public function testCreateViewWithModel()
    {
        $view = new View('The Model');

        self::assertSame('The Model', $view->getModel());
        self::assertNull($view->getFile());
    }

    /**
     * Test create view with file.
     */
    public function testCreateViewWithViewFile()
    {
        $view = new View('The Model', '10a_view-File');

        self::assertSame('The Model', $view->getModel());
        self::assertSame('10a_view-File', $view->getFile());
    }

    /**
     * Test update response method with view result.
     */
    public function testUpdateResponseWithViewResult()
    {
        $DS = DIRECTORY_SEPARATOR;

        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $application->setViewPath(FilePath::parse(__DIR__ . $DS . 'Helpers' . $DS . 'TestViews' . $DS));
        $application->addViewRenderer(new BasicTestViewRenderer());

        $request = new BasicTestRequest(Url::parse('https://example.com/withviewdata'), new Method('GET'));
        $response = new BasicTestResponse();
        $view = new View('The Model');
        $viewItems = new ViewItemCollection();
        $viewItems->set('Foo', 'The View Data');

        $view->updateResponse($application, $request, $response, 'ViewTest', 'withviewdata', $viewItems);

        self::assertSame('<html><body><h1>With model and view data</h1><span>' . $application->getDocumentRoot() . '</span><em>' . $request->getUrl() . "</em><p>The Model</p><i>The View Data</i></body></html>\n", self::normalizeEndOfLine($response->getContent()));
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test update response method with missing view file.
     */
    public function testUpdateResponseWithMissingViewFile()
    {
        $DS = DIRECTORY_SEPARATOR;

        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $application->setViewPath(FilePath::parse(__DIR__ . $DS . 'Helpers' . $DS . 'TestViews' . $DS));
        $application->addViewRenderer(new BasicTestViewRenderer());

        $request = new BasicTestRequest(Url::parse('https://example.com/withnoviewfile'), new Method('GET'));
        $response = new BasicTestResponse();
        $view = new View('The Model');
        $viewItems = new ViewItemCollection();

        self::expectException(ViewFileNotFoundException::class);
        self::expectExceptionMessage('Could not find view file "' . $application->getViewPath() . 'ViewTest' . $DS . 'withnoviewfile.view"');

        $view->updateResponse($application, $request, $response, 'ViewTest', 'withnoviewfile', $viewItems);
    }

    /**
     * Test update response method with custom view file.
     */
    public function testUpdateResponseWithCustomViewFile()
    {
        $DS = DIRECTORY_SEPARATOR;

        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $application->setViewPath(FilePath::parse(__DIR__ . $DS . 'Helpers' . $DS . 'TestViews' . $DS));
        $application->addViewRenderer(new BasicTestViewRenderer());

        $request = new BasicTestRequest(Url::parse('https://example.com/'), new Method('GET'));
        $response = new BasicTestResponse();
        $view = new View('The Model', 'custom');
        $viewItems = new ViewItemCollection();

        $view->updateResponse($application, $request, $response, 'ViewTest', 'index', $viewItems);

        self::assertSame('<html><body><h1>Custom view file</h1><span>' . $application->getDocumentRoot() . "</span><em>https://example.com/</em><p>The Model</p></body></html>\n", self::normalizeEndOfLine($response->getContent()));
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test create a view with invalid view file.
     */
    public function testCreateWithInvalidViewFile()
    {
        self::expectException(InvalidViewFileException::class);
        self::expectExceptionMessage('View file "foo$bar" contains invalid character "$".');

        new View([], 'foo$bar');
    }

    /**
     * Test update response method with alternate view renderer (basic test view renderer first).
     */
    public function testUpdateResponseWithAlternativeViewRendererBasicFirst()
    {
        $DS = DIRECTORY_SEPARATOR;

        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $application->setViewPath(FilePath::parse(__DIR__ . $DS . 'Helpers' . $DS . 'TestViews' . $DS));
        $application->addViewRenderer(new BasicTestViewRenderer()); // Use this.
        $application->addViewRenderer(new JsonTestViewRenderer());

        $request = new BasicTestRequest(Url::parse('https://example.com/altername'), new Method('GET'));
        $response = new BasicTestResponse();
        $view = new View('The Model');
        $viewItems = new ViewItemCollection();
        $viewItems->set('Foo', 'The View Data');

        $view->updateResponse($application, $request, $response, 'ViewTest', 'alternate', $viewItems);

        self::assertSame('<html><body><h1>Alternate</h1><span>' . $application->getDocumentRoot() . '</span><em>' . $request->getUrl() . "</em><p>The Model</p><i>The View Data</i></body></html>\n", self::normalizeEndOfLine($response->getContent()));
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test update response method with alternate view renderer (json test view renderer first).
     */
    public function testUpdateResponseWithAlternativeViewRendererJsonFirst()
    {
        $DS = DIRECTORY_SEPARATOR;

        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $application->setViewPath(FilePath::parse(__DIR__ . $DS . 'Helpers' . $DS . 'TestViews' . $DS));
        $application->addViewRenderer(new JsonTestViewRenderer()); // Use this.
        $application->addViewRenderer(new BasicTestViewRenderer());

        $request = new BasicTestRequest(Url::parse('https://example.com/altername'), new Method('GET'));
        $response = new BasicTestResponse();
        $view = new View('The Model');
        $viewItems = new ViewItemCollection();
        $viewItems->set('Foo', 'The View Data');

        $view->updateResponse($application, $request, $response, 'ViewTest', 'alternate', $viewItems);

        self::assertSame("{\"Model\":\"The Model\",\"ViewItems\":{\"Foo\":\"The View Data\"}}\n", self::normalizeEndOfLine($response->getContent()));
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test update response method with alternate view renderer (only json test view renderer).
     */
    public function testUpdateResponseWithAlternativeViewRendererOnlyJson()
    {
        $DS = DIRECTORY_SEPARATOR;

        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $application->setViewPath(FilePath::parse(__DIR__ . $DS . 'Helpers' . $DS . 'TestViews' . $DS));
        $application->addViewRenderer(new BasicTestViewRenderer()); // No view exist for this.
        $application->addViewRenderer(new JsonTestViewRenderer()); // Use this.

        $request = new BasicTestRequest(Url::parse('https://example.com/onlyjson'), new Method('GET'));
        $response = new BasicTestResponse();
        $view = new View('The Model');
        $viewItems = new ViewItemCollection();
        $viewItems->set('Foo', 'The View Data');

        $view->updateResponse($application, $request, $response, 'ViewTest', 'onlyjson', $viewItems);

        self::assertSame("{\"Model\":\"The Model\"}\n", self::normalizeEndOfLine($response->getContent()));
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test update response method with no view renderer.
     */
    public function testUpdateViewWithNoViewRenderer()
    {
        self::expectException(MissingViewRendererException::class);
        self::expectExceptionMessage('No view renderer was added to application.');

        $DS = DIRECTORY_SEPARATOR;

        $application = new BasicTestApplication(FilePath::parse('/var/www/'));
        $application->setViewPath(FilePath::parse(__DIR__ . $DS . 'Helpers' . $DS . 'TestViews' . $DS));

        $request = new BasicTestRequest(Url::parse('https://example.com/'), new Method('GET'));
        $response = new BasicTestResponse();
        $view = new View();
        $viewItems = new ViewItemCollection();

        $view->updateResponse($application, $request, $response, 'ViewTest', 'index', $viewItems);
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
