<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests;

use BlueMvc\Core\Application;
use BlueMvc\Core\Collections\CustomItemCollection;
use BlueMvc\Core\Exceptions\InvalidControllerClassException;
use BlueMvc\Core\Exceptions\InvalidFilePathException;
use BlueMvc\Core\Route;
use BlueMvc\Core\Tests\Helpers\TestControllers\BasicTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\ErrorTestController;
use BlueMvc\Core\Tests\Helpers\TestRequests\BasicTestRequest;
use BlueMvc\Core\Tests\Helpers\TestViewRenderers\BasicTestViewRenderer;
use DataTypes\System\FilePath;
use PHPUnit\Framework\TestCase;

/**
 * Test Application class.
 */
class ApplicationTest extends TestCase
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
     */
    public function testGetViewPath()
    {
        $DS = DIRECTORY_SEPARATOR;

        self::assertSame($DS . 'var' . $DS . 'www' . $DS . 'views' . $DS, $this->application->getViewPath()->__toString());
    }

    /**
     * Test getViewPaths method.
     */
    public function testGetViewPaths()
    {
        $DS = DIRECTORY_SEPARATOR;

        $viewPaths = $this->application->getViewPaths();

        self::assertCount(1, $viewPaths);
        self::assertSame($DS . 'var' . $DS . 'www' . $DS . 'views' . $DS, $this->application->getViewPaths()[0]->__toString());
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
     * Test getRoutes method.
     */
    public function testGetRoutes()
    {
        $routes = $this->application->getRoutes();

        self::assertSame(1, count($routes));
    }

    /**
     * Test that creating an application with a relative path throws exception.
     */
    public function testCreateWithRelativePathAsDocumentRootThrowsException()
    {
        $DS = DIRECTORY_SEPARATOR;

        $_SERVER = [
            'DOCUMENT_ROOT' => 'var' . $DS . 'www',
        ];

        self::expectException(InvalidFilePathException::class);
        self::expectExceptionMessage('Document root "var' . $DS . 'www' . $DS . '" is not an absolute path.');

        new Application();
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
     * Test isDebug with debug mode on.
     */
    public function testIsDebugWithDebugModeOn()
    {
        $DS = DIRECTORY_SEPARATOR;

        $_SERVER = [
            'DOCUMENT_ROOT' => $DS . 'var' . $DS . 'www',
            'BLUEMVC_DEBUG' => '1',
        ];

        $this->application = new Application();

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
        $this->application->setCustomItem('Foo', false);
        $this->application->setCustomItem('Bar', true);

        $customItems = $this->application->getCustomItems();

        self::assertSame(['Foo' => false, 'Bar' => true], iterator_to_array($customItems));
    }

    /**
     * Test getCustomItem method.
     */
    public function testGetCustomItem()
    {
        $this->application->setCustomItem('Foo', false);
        $this->application->setCustomItem('Bar', true);

        self::assertFalse($this->application->getCustomItem('Foo'));
        self::assertTrue($this->application->getCustomItem('Bar'));
        self::assertNull($this->application->getCustomItem('FOO'));
        self::assertNull($this->application->getCustomItem('Baz'));
    }

    /**
     * Test setCustomItems method.
     */
    public function testSetCustomItems()
    {
        $customItems = new CustomItemCollection();
        $customItems->set('Foo', 'Bar');

        $this->application->setCustomItems($customItems);

        self::assertSame(['Foo' => 'Bar'], iterator_to_array($this->application->getCustomItems()));
    }

    /**
     * Set up.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->originalServerArray = $_SERVER;

        $DS = DIRECTORY_SEPARATOR;

        $_SERVER = [
            'DOCUMENT_ROOT' => $DS . 'var' . $DS . 'www',
        ];

        $this->application = new Application();

        $this->application->setViewPath(FilePath::parse('views' . $DS));
        $this->application->addViewRenderer(new BasicTestViewRenderer());
        $this->application->addRoute(new Route('', BasicTestController::class));
        rmdir($this->application->getTempPath()->__toString());
    }

    /**
     * Tear down.
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $_SERVER = $this->originalServerArray;
    }

    /**
     * @var Application The application.
     */
    private Application $application;

    /**
     * @var array The original server array.
     */
    private array $originalServerArray;
}
