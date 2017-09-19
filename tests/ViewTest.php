<?php

namespace BlueMvc\Core\Tests;

use BlueMvc\Core\Application;
use BlueMvc\Core\Collections\ViewItemCollection;
use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Request;
use BlueMvc\Core\Response;
use BlueMvc\Core\Tests\Helpers\TestControllers\ViewTestController;
use BlueMvc\Core\Tests\Helpers\TestViewRenderers\BasicTestViewRenderer;
use BlueMvc\Core\Tests\Helpers\TestViewRenderers\JsonTestViewRenderer;
use BlueMvc\Core\View;
use DataTypes\FilePath;

/**
 * Test View class.
 */
class ViewTest extends \PHPUnit_Framework_TestCase
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
     * Test create view with invalid file parameter.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $file parameter is not a string or null.
     */
    public function testCreateViewWithInvalidViewFileParameter()
    {
        new View('The Model', false);
    }

    /**
     * Test update response method with invalid action parameter.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $action parameter is not a string.
     */
    public function testUpdateResponseWithInvalidActionParameter()
    {
        $DS = DIRECTORY_SEPARATOR;

        $application = new Application(['DOCUMENT_ROOT' => '/var/www/']);
        $application->setViewPath(FilePath::parse(__DIR__ . $DS . 'Helpers' . $DS . 'TestViews' . $DS));
        $application->addViewRenderer(new BasicTestViewRenderer());

        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        $controller = new ViewTestController();
        $view = new View('The Model');
        $viewItems = new ViewItemCollection();

        $view->updateResponse($application, $request, $response, $controller, 0, $viewItems);
    }

    /**
     * Test update response method with view result.
     */
    public function testUpdateResponseWithViewResult()
    {
        $DS = DIRECTORY_SEPARATOR;

        $application = new Application(['DOCUMENT_ROOT' => '/var/www/']);
        $application->setViewPath(FilePath::parse(__DIR__ . $DS . 'Helpers' . $DS . 'TestViews' . $DS));
        $application->addViewRenderer(new BasicTestViewRenderer());

        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/withviewdata', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        $controller = new ViewTestController();
        $view = new View('The Model');
        $viewItems = new ViewItemCollection();
        $viewItems->set('Foo', 'The View Data');

        $view->updateResponse($application, $request, $response, $controller, 'withviewdata', $viewItems);

        self::assertSame('<html><body><h1>With model and view data</h1><span>' . $application->getDocumentRoot() . '</span><em>' . $request->getUrl() . '</em><p>The Model</p><i>The View Data</i></body></html>', $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test update response method with missing view file.
     */
    public function testUpdateResponseWithMissingViewFile()
    {
        $DS = DIRECTORY_SEPARATOR;

        $application = new Application(['DOCUMENT_ROOT' => '/var/www/']);
        $application->setViewPath(FilePath::parse(__DIR__ . $DS . 'Helpers' . $DS . 'TestViews' . $DS));
        $application->addViewRenderer(new BasicTestViewRenderer());

        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/withnoviewfile', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        $controller = new ViewTestController();
        $view = new View('The Model');
        $viewItems = new ViewItemCollection();
        $exception = null;

        try {
            $view->updateResponse($application, $request, $response, $controller, 'withnoviewfile', $viewItems);
        } catch (\Exception $exception) {
        }

        self::assertSame('BlueMvc\Core\Exceptions\ViewFileNotFoundException', get_class($exception));
        self::assertSame('Could not find view file "' . $application->getViewPath() . 'ViewTest' . $DS . 'withnoviewfile.view"', $exception->getMessage());
    }

    /**
     * Test update response method with custom view file.
     */
    public function testUpdateResponseWithCustomViewFile()
    {
        $DS = DIRECTORY_SEPARATOR;

        $application = new Application(['DOCUMENT_ROOT' => '/var/www/']);
        $application->setViewPath(FilePath::parse(__DIR__ . $DS . 'Helpers' . $DS . 'TestViews' . $DS));
        $application->addViewRenderer(new BasicTestViewRenderer());

        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        $controller = new ViewTestController();
        $view = new View('The Model', 'custom');
        $viewItems = new ViewItemCollection();

        $view->updateResponse($application, $request, $response, $controller, 'index', $viewItems);

        self::assertSame('<html><body><h1>Custom view file</h1><span>' . $application->getDocumentRoot() . '</span><em>http://www.domain.com/</em><p>The Model</p></body></html>', $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test create a view with invalid view file.
     *
     * @expectedException \BlueMvc\Core\Exceptions\InvalidViewFileException
     * @expectedExceptionMessage View file "foo$bar" contains invalid character "$".
     */
    public function testCreateWithInvalidViewFile()
    {
        new View([], 'foo$bar');
    }

    /**
     * Test update response method with alternate view renderer (basic test view renderer first).
     */
    public function testUpdateResponseWithAlternativeViewRendererBasicFirst()
    {
        $DS = DIRECTORY_SEPARATOR;

        $application = new Application(['DOCUMENT_ROOT' => '/var/www/']);
        $application->setViewPath(FilePath::parse(__DIR__ . $DS . 'Helpers' . $DS . 'TestViews' . $DS));
        $application->addViewRenderer(new BasicTestViewRenderer());
        $application->addViewRenderer(new JsonTestViewRenderer());

        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/alternate', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        $controller = new ViewTestController();
        $view = new View('The Model');
        $viewItems = new ViewItemCollection();
        $viewItems->set('Foo', 'The View Data');

        $view->updateResponse($application, $request, $response, $controller, 'alternate', $viewItems);

        self::assertSame('<html><body><h1>Alternate</h1><span>' . $application->getDocumentRoot() . '</span><em>' . $request->getUrl() . '</em><p>The Model</p><i>The View Data</i></body></html>', $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }

    /**
     * Test update response method with alternate view renderer (json test view renderer first).
     */
    public function testUpdateResponseWithAlternativeViewRendererJsonFirst()
    {
        $DS = DIRECTORY_SEPARATOR;

        $application = new Application(['DOCUMENT_ROOT' => '/var/www/']);
        $application->setViewPath(FilePath::parse(__DIR__ . $DS . 'Helpers' . $DS . 'TestViews' . $DS));
        $application->addViewRenderer(new JsonTestViewRenderer());
        $application->addViewRenderer(new BasicTestViewRenderer());

        $request = new Request(['HTTP_HOST' => 'www.domain.com', 'SERVER_PORT' => '80', 'REQUEST_URI' => '/alternate', 'REQUEST_METHOD' => 'GET']);
        $response = new Response($request);
        $controller = new ViewTestController();
        $view = new View('The Model');
        $viewItems = new ViewItemCollection();
        $viewItems->set('Foo', 'The View Data');

        $view->updateResponse($application, $request, $response, $controller, 'alternate', $viewItems);

        self::assertSame('{"Model":"The Model","ViewItems":{"Foo":"The View Data"}}', $response->getContent());
        self::assertSame(StatusCode::OK, $response->getStatusCode()->getCode());
    }
}
