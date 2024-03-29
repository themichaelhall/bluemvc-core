<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Base;

use BlueMvc\Core\Collections\CustomItemCollection;
use BlueMvc\Core\Exceptions\InvalidControllerClassException;
use BlueMvc\Core\Exceptions\InvalidFilePathException;
use BlueMvc\Core\Route;
use BlueMvc\Core\Tests\Helpers\TestApplications\BasicTestApplication;
use BlueMvc\Core\Tests\Helpers\TestControllers\BasicTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\ErrorTestController;
use BlueMvc\Core\Tests\Helpers\TestRequests\BasicTestRequest;
use BlueMvc\Core\Tests\Helpers\TestViewRenderers\BasicTestViewRenderer;
use DataTypes\System\FilePath;
use PHPUnit\Framework\TestCase;

/**
 * Test AbstractApplication class (via derived test class).
 */
class AbstractApplicationTest extends TestCase
{
    /**
     * Test getDocumentRoot method.
     */
    public function testGetDocumentRoot()
    {
        $DS = DIRECTORY_SEPARATOR;

        self::assertSame($DS . 'var' . $DS . 'www' . $DS, $this->application->getDocumentRoot()->__toString());
    }

    /**
     * Test get viewRenderers method.
     */
    public function testGetViewRenderers()
    {
        $viewRenderers = $this->application->getViewRenderers();

        self::assertSame(1, count($viewRenderers));
        self::assertInstanceOf(BasicTestViewRenderer::class, $viewRenderers[0]);
    }

    /**
     * Test getViewPath method.
     *
     * @noinspection PhpDeprecationInspection
     */
    public function testGetViewPath()
    {
        $DS = DIRECTORY_SEPARATOR;

        self::assertSame($DS . 'var' . $DS . 'www' . $DS, $this->application->getViewPath()->__toString());
    }

    /**
     * Test getViewPaths method.
     */
    public function testGetViewPaths()
    {
        $DS = DIRECTORY_SEPARATOR;

        $viewPaths = $this->application->getViewPaths();

        self::assertCount(1, $viewPaths);
        self::assertSame($DS . 'var' . $DS . 'www' . $DS, $this->application->getViewPaths()[0]->__toString());
    }

    /**
     * Test getRoutes method.
     */
    public function testGetRoutes()
    {
        $routes = $this->application->getRoutes();

        self::assertSame(1, count($routes));
    }

    /**
     * Test setViewPath method with absolute path.
     */
    public function testSetViewPathWithAbsolutePath()
    {
        $DS = DIRECTORY_SEPARATOR;

        $this->application->setViewPath(FilePath::parse($DS . 'bluemvc' . $DS . 'html' . $DS));

        self::assertCount(1, $this->application->getViewPaths());
        self::assertSame($DS . 'bluemvc' . $DS . 'html' . $DS, $this->application->getViewPaths()[0]->__toString());
    }

    /**
     * Test setViewPath method with relative path.
     */
    public function testSetViewPathWithRelativePath()
    {
        $DS = DIRECTORY_SEPARATOR;

        $this->application->setViewPath(FilePath::parse('..' . $DS . 'bluemvc' . $DS . 'html' . $DS));

        self::assertCount(1, $this->application->getViewPaths());
        self::assertSame($DS . 'var' . $DS . 'bluemvc' . $DS . 'html' . $DS, $this->application->getViewPaths()[0]->__toString());
    }

    /**
     * Test setViewPaths method.
     */
    public function testSetViewPaths()
    {
        $DS = DIRECTORY_SEPARATOR;

        $this->application->setViewPaths(
            [
                FilePath::parse($DS . 'bluemvc' . $DS . 'html' . $DS),
                FilePath::parse('..' . $DS . 'bluemvc' . $DS . 'html' . $DS),
            ]
        );

        $viewPaths = $this->application->getViewPaths();

        self::assertCount(2, $viewPaths);
        self::assertSame($DS . 'bluemvc' . $DS . 'html' . $DS, $this->application->getViewPaths()[0]->__toString());
        self::assertSame($DS . 'var' . $DS . 'bluemvc' . $DS . 'html' . $DS, $this->application->getViewPaths()[1]->__toString());
    }

    /**
     * Test that creating an application with a path to file throws exception.
     */
    public function testCreateWithFileAsDocumentRootThrowsException()
    {
        $DS = DIRECTORY_SEPARATOR;

        self::expectException(InvalidFilePathException::class);
        self::expectExceptionMessage('Document root "' . $DS . 'var' . $DS . 'www' . $DS . 'file.txt" is not a directory.');

        new BasicTestApplication(FilePath::parse($DS . 'var' . $DS . 'www' . $DS . 'file.txt'));
    }

    /**
     * Test that calling setDocumentRoot method with a path to file throws exception.
     */
    public function testSetDocumentRootWithFileAsDocumentRootThrowsException()
    {
        $DS = DIRECTORY_SEPARATOR;

        self::expectException(InvalidFilePathException::class);
        self::expectExceptionMessage('Document root "' . $DS . 'var' . $DS . 'www' . $DS . 'file.txt" is not a directory.');

        $this->application->setDocumentRoot(FilePath::parse($DS . 'var' . $DS . 'www' . $DS . 'file.txt'));
    }

    /**
     * Test that creating an application with a relative path throws exception.
     */
    public function testCreateWithRelativePathAsDocumentRootThrowsException()
    {
        $DS = DIRECTORY_SEPARATOR;

        self::expectException(InvalidFilePathException::class);
        self::expectExceptionMessage('Document root "var' . $DS . 'www' . $DS . '" is not an absolute path.');

        new BasicTestApplication(FilePath::parse('var' . $DS . 'www' . $DS));
    }

    /**
     * Test that calling setDocumentRoot with a relative path throws exception.
     */
    public function testSetDocumentRootWithRelativePathAsDocumentRootThrowsException()
    {
        $DS = DIRECTORY_SEPARATOR;

        self::expectException(InvalidFilePathException::class);
        self::expectExceptionMessage('Document root "var' . $DS . 'www' . $DS . '" is not an absolute path.');

        $this->application->setDocumentRoot(FilePath::parse('var' . $DS . 'www' . $DS));
    }

    /**
     * Test that calling setViewPath with a path to file throws exception.
     */
    public function testSetViewPathWithFileAsViewPathThrowsException()
    {
        $DS = DIRECTORY_SEPARATOR;

        self::expectException(InvalidFilePathException::class);
        self::expectExceptionMessage('View path "' . $DS . 'views' . $DS . 'file.txt" is not a directory.');

        $this->application->setViewPath(FilePath::parse($DS . 'views' . $DS . 'file.txt'));
    }

    /**
     * Test that calling setViewPath with a path that can not be combined with document root throws exception.
     */
    public function testSetViewPathWithViewPathThatCanNotBeCombinedWithDocumentRootThrowsException()
    {
        $DS = DIRECTORY_SEPARATOR;

        self::expectException(InvalidFilePathException::class);
        self::expectExceptionMessage('File path "' . $DS . 'var' . $DS . 'www' . $DS . '" can not be combined with file path "..' . $DS . '..' . $DS . '..' . $DS . 'views' . $DS . '": Absolute path is above root level.');

        $this->application->setViewPath(FilePath::parse('..' . $DS . '..' . $DS . '..' . $DS . 'views' . $DS));
    }

    /**
     * Test that calling setViewPaths with a path to file throws exception.
     */
    public function testSetViewPathsWithFileAsViewPathThrowsException()
    {
        $DS = DIRECTORY_SEPARATOR;

        self::expectException(InvalidFilePathException::class);
        self::expectExceptionMessage('View path "' . $DS . 'views' . $DS . 'file.txt" is not a directory.');

        $this->application->setViewPaths([FilePath::parse($DS . 'views' . $DS . 'file.txt')]);
    }

    /**
     * Test that calling setViewPaths with a path that can not be combined with document root throws exception.
     */
    public function testSetViewPathsWithViewPathThatCanNotBeCombinedWithDocumentRootThrowsException()
    {
        $DS = DIRECTORY_SEPARATOR;

        self::expectException(InvalidFilePathException::class);
        self::expectExceptionMessage('File path "' . $DS . 'var' . $DS . 'www' . $DS . '" can not be combined with file path "..' . $DS . '..' . $DS . '..' . $DS . 'views' . $DS . '": Absolute path is above root level.');

        $this->application->setViewPaths([FilePath::parse('..' . $DS . '..' . $DS . '..' . $DS . 'views' . $DS)]);
    }

    /**
     * Test getTempPath method.
     */
    public function testGetTempPath()
    {
        $DS = DIRECTORY_SEPARATOR;

        self::assertSame(sys_get_temp_dir() . $DS . 'bluemvc' . $DS . sha1($this->application->getDocumentRoot()->__toString()) . $DS, $this->application->getTempPath()->__toString());
    }

    /**
     * Test setTempPath method with absolute path.
     */
    public function testSetTempPathWithAbsolutePath()
    {
        $DS = DIRECTORY_SEPARATOR;

        $this->application->setTempPath(FilePath::parse($DS . 'tmp' . $DS . 'bluemvc' . $DS));

        self::assertSame($DS . 'tmp' . $DS . 'bluemvc' . $DS, $this->application->getTempPath()->__toString());
    }

    /**
     * Test setTempPath method with relative path.
     */
    public function testSetTempPathWithRelativePath()
    {
        $DS = DIRECTORY_SEPARATOR;

        $this->application->setTempPath(FilePath::parse('tmp' . $DS . 'bluemvc' . $DS));

        self::assertSame($DS . 'var' . $DS . 'www' . $DS . 'tmp' . $DS . 'bluemvc' . $DS, $this->application->getTempPath()->__toString());
    }

    /**
     * Test that calling setTempPath with a path to file throws exception.
     */
    public function testSetTempPathWithFileAsTempPathThrowsException()
    {
        $DS = DIRECTORY_SEPARATOR;

        self::expectException(InvalidFilePathException::class);
        self::expectExceptionMessage('Temp path "' . $DS . 'tmp' . $DS . 'file.txt" is not a directory.');

        $this->application->setTempPath(FilePath::parse($DS . 'tmp' . $DS . 'file.txt'));
    }

    /**
     * Test that calling setTempPath with a path that can not be combined with document root throws exception.
     */
    public function testSetTempPathWithTempPathThatCanNotBeCombinedWithDocumentRootThrowsException()
    {
        $DS = DIRECTORY_SEPARATOR;

        self::expectException(InvalidFilePathException::class);
        self::expectExceptionMessage('File path "' . $DS . 'var' . $DS . 'www' . $DS . '" can not be combined with file path "..' . $DS . '..' . $DS . '..' . $DS . 'tmp' . $DS . '": Absolute path is above root level.');

        $this->application->setTempPath(FilePath::parse('..' . $DS . '..' . $DS . '..' . $DS . 'tmp' . $DS));
    }

    /**
     * Test isDebug method.
     */
    public function testIsDebug()
    {
        self::assertFalse($this->application->isDebug());
    }

    /**
     * Test setDebug method.
     */
    public function testSetDebug()
    {
        $this->application->setDebug(true);

        self::assertTrue($this->application->isDebug());
    }

    /**
     * Test getErrorControllerClass method.
     */
    public function testGetErrorControllerClass()
    {
        self::assertNull($this->application->getErrorControllerClass());
    }

    /**
     * Test setErrorControllerClass method.
     */
    public function testSetErrorControllerClass()
    {
        $this->application->setErrorControllerClass(ErrorTestController::class);

        self::assertSame(ErrorTestController::class, $this->application->getErrorControllerClass());
    }

    /**
     * Test setErrorControllerClass method with non-existing class name.
     */
    public function testSetErrorControllerClassWithNonExistingClassName()
    {
        self::expectException(InvalidControllerClassException::class);
        self::expectExceptionMessage('"BlueMvc\Core\FooBar" is not a valid error controller class.');

        $this->application->setErrorControllerClass('BlueMvc\\Core\\FooBar');
    }

    /**
     * Test setErrorControllerClass method with invalid class name.
     */
    public function testSetErrorControllerClassWithInvalidClassName()
    {
        self::expectException(InvalidControllerClassException::class);
        self::expectExceptionMessage('"BlueMvc\Core\Tests\Helpers\TestRequests\BasicTestRequest" is not a valid error controller class.');

        $this->application->setErrorControllerClass(BasicTestRequest::class);
    }

    /**
     * Test setErrorControllerClass method with ordinary controller class name.
     */
    public function testSetErrorControllerClassWithOrdinaryControllerClassName()
    {
        self::expectException(InvalidControllerClassException::class);
        self::expectExceptionMessage('"BlueMvc\Core\Tests\Helpers\TestControllers\BasicTestController" is not a valid error controller class.');

        $this->application->setErrorControllerClass(BasicTestController::class);
    }

    /**
     * Test getCustomItems method.
     */
    public function testGetCustomItems()
    {
        $customItems = $this->application->getCustomItems();

        self::assertSame([], iterator_to_array($customItems));
    }

    /**
     * Test setCustomItem method.
     */
    public function testSetCustomItem()
    {
        $this->application->setCustomItem('foo', 'bar');
        $this->application->setCustomItem('baz', [1, 2]);

        $customItems = $this->application->getCustomItems();

        self::assertSame(['foo' => 'bar', 'baz' => [1, 2]], iterator_to_array($customItems));
    }

    /**
     * Test getCustomItem method.
     */
    public function testGetCustomItem()
    {
        $this->application->setCustomItem('foo', 'bar');
        $this->application->setCustomItem('baz', [1, 2]);

        self::assertSame('bar', $this->application->getCustomItem('foo'));
        self::assertSame([1, 2], $this->application->getCustomItem('baz'));
        self::assertNull($this->application->getCustomItem('Foo'));
        self::assertNull($this->application->getCustomItem('bar'));
    }

    /**
     * Test setCustomItems method.
     */
    public function testSetCustomItems()
    {
        $customItems = new CustomItemCollection();
        $customItems->set('foo', 1);
        $customItems->set('bar', ['baz']);

        $this->application->setCustomItems($customItems);

        self::assertSame(['foo' => 1, 'bar' => ['baz']], iterator_to_array($this->application->getCustomItems()));
    }

    /**
     * Set up.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $DS = DIRECTORY_SEPARATOR;

        $this->application = new BasicTestApplication(FilePath::parse($DS . 'var' . $DS . 'www' . $DS));
        $this->application->addViewRenderer(new BasicTestViewRenderer());
        $this->application->addRoute(new Route('', BasicTestController::class));
        rmdir($this->application->getTempPath()->__toString());
    }

    /**
     * @var BasicTestApplication The application.
     */
    private BasicTestApplication $application;
}
