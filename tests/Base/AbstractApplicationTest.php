<?php

namespace BlueMvc\Core\Tests\Base;

use BlueMvc\Core\Collections\CustomItemCollection;
use BlueMvc\Core\Exceptions\InvalidFilePathException;
use BlueMvc\Core\Route;
use BlueMvc\Core\Tests\Helpers\TestApplications\BasicTestApplication;
use BlueMvc\Core\Tests\Helpers\TestCollections\BasicTestSessionItemCollection;
use BlueMvc\Core\Tests\Helpers\TestControllers\BasicTestController;
use BlueMvc\Core\Tests\Helpers\TestControllers\ErrorTestController;
use BlueMvc\Core\Tests\Helpers\TestRequests\BasicTestRequest;
use BlueMvc\Core\Tests\Helpers\TestViewRenderers\BasicTestViewRenderer;
use DataTypes\FilePath;
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

        self::assertSame($DS . 'var' . $DS . 'www' . $DS, $this->myApplication->getDocumentRoot()->__toString());
    }

    /**
     * Test get viewRenderers method.
     */
    public function testGetViewRenderers()
    {
        $viewRenderers = $this->myApplication->getViewRenderers();

        self::assertSame(1, count($viewRenderers));
        self::assertInstanceOf(BasicTestViewRenderer::class, $viewRenderers[0]);
    }

    /**
     * Test getViewPath method.
     */
    public function testGetViewPath()
    {
        $DS = DIRECTORY_SEPARATOR;

        self::assertSame($DS . 'var' . $DS . 'www' . $DS, $this->myApplication->getViewPath()->__toString());
    }

    /**
     * Test getRoutes method.
     */
    public function testGetRoutes()
    {
        $routes = $this->myApplication->getRoutes();

        self::assertSame(1, count($routes));
    }

    /**
     * Test setViewPath method with absolute path.
     */
    public function testSetViewPathWithAbsolutePath()
    {
        $DS = DIRECTORY_SEPARATOR;

        $this->myApplication->setViewPath(FilePath::parse($DS . 'bluemvc' . $DS . 'html' . $DS));

        self::assertSame($DS . 'bluemvc' . $DS . 'html' . $DS, $this->myApplication->getViewPath()->__toString());
    }

    /**
     * Test setViewPath method with relative path.
     */
    public function testSetViewPathWithRelativePath()
    {
        $DS = DIRECTORY_SEPARATOR;

        $this->myApplication->setViewPath(FilePath::parse('..' . $DS . 'bluemvc' . $DS . 'html' . $DS));

        self::assertSame($DS . 'var' . $DS . 'bluemvc' . $DS . 'html' . $DS, $this->myApplication->getViewPath()->__toString());
    }

    /**
     * Test that creating an application with a path to file throws exception.
     */
    public function testCreateWithFileAsDocumentRootThrowsException()
    {
        $DS = DIRECTORY_SEPARATOR;
        $exceptionMessage = '';

        try {
            new BasicTestApplication(FilePath::parse($DS . 'var' . $DS . 'www' . $DS . 'file.txt'));
        } catch (InvalidFilePathException $e) {
            $exceptionMessage = $e->getMessage();
        }

        self::assertSame('Document root "' . $DS . 'var' . $DS . 'www' . $DS . 'file.txt" is not a directory.', $exceptionMessage);
    }

    /**
     * Test that calling setDocumentRoot method with a path to file throws exception.
     */
    public function testSetDocumentRootWithFileAsDocumentRootThrowsException()
    {
        $DS = DIRECTORY_SEPARATOR;
        $exceptionMessage = '';

        try {
            $this->myApplication->setDocumentRoot(FilePath::parse($DS . 'var' . $DS . 'www' . $DS . 'file.txt'));
        } catch (InvalidFilePathException $e) {
            $exceptionMessage = $e->getMessage();
        }

        self::assertSame('Document root "' . $DS . 'var' . $DS . 'www' . $DS . 'file.txt" is not a directory.', $exceptionMessage);
    }

    /**
     * Test that creating an application with a relative path throws exception.
     */
    public function testCreateWithRelativePathAsDocumentRootThrowsException()
    {
        $DS = DIRECTORY_SEPARATOR;
        $exceptionMessage = '';

        try {
            new BasicTestApplication(FilePath::parse('var' . $DS . 'www' . $DS));
        } catch (InvalidFilePathException $e) {
            $exceptionMessage = $e->getMessage();
        }

        self::assertSame('Document root "var' . $DS . 'www' . $DS . '" is not an absolute path.', $exceptionMessage);
    }

    /**
     * Test that calling setDocumentRoot with a relative path throws exception.
     */
    public function testSetDocumentRootWithRelativePathAsDocumentRootThrowsException()
    {
        $DS = DIRECTORY_SEPARATOR;
        $exceptionMessage = '';

        try {
            $this->myApplication->setDocumentRoot(FilePath::parse('var' . $DS . 'www' . $DS));
        } catch (InvalidFilePathException $e) {
            $exceptionMessage = $e->getMessage();
        }

        self::assertSame('Document root "var' . $DS . 'www' . $DS . '" is not an absolute path.', $exceptionMessage);
    }

    /**
     * Test that calling setViewPath with a path to file throws exception.
     */
    public function testSetViewPathWithFileAsViewPathThrowsException()
    {
        $DS = DIRECTORY_SEPARATOR;
        $exceptionMessage = '';

        try {
            $this->myApplication->setViewPath(FilePath::parse($DS . 'views' . $DS . 'file.txt'));
        } catch (InvalidFilePathException $e) {
            $exceptionMessage = $e->getMessage();
        }

        self::assertSame('View path "' . $DS . 'views' . $DS . 'file.txt" is not a directory.', $exceptionMessage);
    }

    /**
     * Test that calling setViewPath with a path that can not be combined with document root throws exception.
     */
    public function testSetViewPathWithViewPathThatCanNotBeCombinedWithDocumentRootThrowsException()
    {
        $DS = DIRECTORY_SEPARATOR;
        $exceptionMessage = '';

        try {
            $this->myApplication->setViewPath(FilePath::parse('..' . $DS . '..' . $DS . '..' . $DS . 'views' . $DS));
        } catch (InvalidFilePathException $e) {
            $exceptionMessage = $e->getMessage();
        }

        self::assertSame('File path "' . $DS . 'var' . $DS . 'www' . $DS . '" can not be combined with file path "..' . $DS . '..' . $DS . '..' . $DS . 'views' . $DS . '": Absolute path is above root level.', $exceptionMessage);
    }

    /**
     * Test getTempPath method.
     */
    public function testGetTempPath()
    {
        $DS = DIRECTORY_SEPARATOR;

        self::assertSame(sys_get_temp_dir() . $DS . 'bluemvc' . $DS . sha1($this->myApplication->getDocumentRoot()->__toString()) . $DS, $this->myApplication->getTempPath()->__toString());
    }

    /**
     * Test setTempPath method with absolute path.
     */
    public function testSetTempPathWithAbsolutePath()
    {
        $DS = DIRECTORY_SEPARATOR;

        $this->myApplication->setTempPath(FilePath::parse($DS . 'tmp' . $DS . 'bluemvc' . $DS));

        self::assertSame($DS . 'tmp' . $DS . 'bluemvc' . $DS, $this->myApplication->getTempPath()->__toString());
    }

    /**
     * Test setTempPath method with relative path.
     */
    public function testSetTempPathWithRelativePath()
    {
        $DS = DIRECTORY_SEPARATOR;

        $this->myApplication->setTempPath(FilePath::parse('tmp' . $DS . 'bluemvc' . $DS));

        self::assertSame($DS . 'var' . $DS . 'www' . $DS . 'tmp' . $DS . 'bluemvc' . $DS, $this->myApplication->getTempPath()->__toString());
    }

    /**
     * Test that calling setTempPath with a path to file throws exception.
     */
    public function testSetTempPathWithFileAsTempPathThrowsException()
    {
        $DS = DIRECTORY_SEPARATOR;
        $exceptionMessage = '';

        try {
            $this->myApplication->setTempPath(FilePath::parse($DS . 'tmp' . $DS . 'file.txt'));
        } catch (InvalidFilePathException $e) {
            $exceptionMessage = $e->getMessage();
        }

        self::assertSame('Temp path "' . $DS . 'tmp' . $DS . 'file.txt" is not a directory.', $exceptionMessage);
    }

    /**
     * Test that calling setTempPath with a path that can not be combined with document root throws exception.
     */
    public function testSetTempPathWithTempPathThatCanNotBeCombinedWithDocumentRootThrowsException()
    {
        $DS = DIRECTORY_SEPARATOR;
        $exceptionMessage = '';

        try {
            $this->myApplication->setTempPath(FilePath::parse('..' . $DS . '..' . $DS . '..' . $DS . 'tmp' . $DS));
        } catch (InvalidFilePathException $e) {
            $exceptionMessage = $e->getMessage();
        }

        self::assertSame('File path "' . $DS . 'var' . $DS . 'www' . $DS . '" can not be combined with file path "..' . $DS . '..' . $DS . '..' . $DS . 'tmp' . $DS . '": Absolute path is above root level.', $exceptionMessage);
    }

    /**
     * Test isDebug method.
     */
    public function testIsDebug()
    {
        self::assertFalse($this->myApplication->isDebug());
    }

    /**
     * Test setDebug method.
     */
    public function testSetDebug()
    {
        $this->myApplication->setDebug(true);

        self::assertTrue($this->myApplication->isDebug());
    }

    /**
     * Test getErrorControllerClass method.
     */
    public function testGetErrorControllerClass()
    {
        self::assertNull($this->myApplication->getErrorControllerClass());
    }

    /**
     * Test setErrorControllerClass method.
     */
    public function testSetErrorControllerClass()
    {
        $this->myApplication->setErrorControllerClass(ErrorTestController::class);

        self::assertSame(ErrorTestController::class, $this->myApplication->getErrorControllerClass());
    }

    /**
     * Test setErrorControllerClass method with non-existing class name.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $errorControllerClass parameter is not a string.
     */
    public function testSetErrorControllerClassWithInvalidParameterType()
    {
        $this->myApplication->setErrorControllerClass(false);
    }

    /**
     * Test setErrorControllerClass method with non-existing class name.
     *
     * @expectedException \BlueMvc\Core\Exceptions\InvalidControllerClassException
     * @expectedExceptionMessage "BlueMvc\Core\FooBar" is not a valid error controller class.
     */
    public function testSetErrorControllerClassWithNonExistingClassName()
    {
        $this->myApplication->setErrorControllerClass('BlueMvc\\Core\\FooBar');
    }

    /**
     * Test setErrorControllerClass method with invalid class name.
     *
     * @expectedException \BlueMvc\Core\Exceptions\InvalidControllerClassException
     * @expectedExceptionMessage "BlueMvc\Core\Tests\Helpers\TestRequests\BasicTestRequest" is not a valid error controller class.
     */
    public function testSetErrorControllerClassWithInvalidClassName()
    {
        $this->myApplication->setErrorControllerClass(BasicTestRequest::class);
    }

    /**
     * Test setErrorControllerClass method with ordinary controller class name.
     *
     * @expectedException \BlueMvc\Core\Exceptions\InvalidControllerClassException
     * @expectedExceptionMessage "BlueMvc\Core\Tests\Helpers\TestControllers\BasicTestController" is not a valid error controller class.
     */
    public function testSetErrorControllerClassWithOrdinaryControllerClassName()
    {
        $this->myApplication->setErrorControllerClass(BasicTestController::class);
    }

    /**
     * Test getSessionItems method.
     */
    public function testGetSessionItems()
    {
        $sessionItems = $this->myApplication->getSessionItems();

        self::assertSame([], iterator_to_array($sessionItems));
    }

    /**
     * Test setSessionItem method.
     */
    public function testSetSessionItem()
    {
        $this->myApplication->setSessionItem('Foo', [1, 2]);
        $this->myApplication->setSessionItem('Bar', true);

        $sessionItems = $this->myApplication->getSessionItems();

        self::assertSame(['Foo' => [1, 2], 'Bar' => true], iterator_to_array($sessionItems));
    }

    /**
     * Test getSessionItem method.
     */
    public function testGetSessionItem()
    {
        $this->myApplication->setSessionItem('Foo', [1, 2]);
        $this->myApplication->setSessionItem('Bar', true);

        self::assertSame([1, 2], $this->myApplication->getSessionItem('Foo'));
        self::assertSame(true, $this->myApplication->getSessionItem('Bar'));
        self::assertNull($this->myApplication->getSessionItem('Baz'));
        self::assertNull($this->myApplication->getSessionItem('bar'));
    }

    /**
     * Test removeSessionItem method.
     */
    public function testRemoveSessionItem()
    {
        $this->myApplication->setSessionItem('Foo', [1, 2]);
        $this->myApplication->setSessionItem('Bar', true);

        $this->myApplication->removeSessionItem('Bar');
        $this->myApplication->removeSessionItem('Baz');

        self::assertSame([1, 2], $this->myApplication->getSessionItem('Foo'));
        self::assertNull($this->myApplication->getSessionItem('Bar'));
        self::assertNull($this->myApplication->getSessionItem('Baz'));
        self::assertNull($this->myApplication->getSessionItem('bar'));
    }

    /**
     * Test setSessionItems method.
     */
    public function testSetSessionItems()
    {
        $sessionItems = new BasicTestSessionItemCollection();
        $sessionItems->set('Foo', 1);
        $sessionItems->set('Bar', 2);

        $this->myApplication->setSessionItems($sessionItems);

        self::assertSame(['Foo' => 1, 'Bar' => 2], iterator_to_array($this->myApplication->getSessionItems()));
    }

    /**
     * Test getCustomItems method.
     */
    public function testGetCustomItems()
    {
        $customItems = $this->myApplication->getCustomItems();

        self::assertSame([], iterator_to_array($customItems));
    }

    /**
     * Test setCustomItem method.
     */
    public function testSetCustomItem()
    {
        $this->myApplication->setCustomItem('foo', 'bar');
        $this->myApplication->setCustomItem('baz', [1, 2]);

        $customItems = $this->myApplication->getCustomItems();

        self::assertSame(['foo' => 'bar', 'baz' => [1, 2]], iterator_to_array($customItems));
    }

    /**
     * Test getCustomItem method.
     */
    public function testGetCustomItem()
    {
        $this->myApplication->setCustomItem('foo', 'bar');
        $this->myApplication->setCustomItem('baz', [1, 2]);

        self::assertSame('bar', $this->myApplication->getCustomItem('foo'));
        self::assertSame([1, 2], $this->myApplication->getCustomItem('baz'));
        self::assertNull($this->myApplication->getCustomItem('Foo'));
        self::assertNull($this->myApplication->getCustomItem('bar'));
    }

    /**
     * Test setCustomItems method.
     */
    public function testSetCustomItems()
    {
        $customItems = new CustomItemCollection();
        $customItems->set('foo', 1);
        $customItems->set('bar', ['baz']);

        $this->myApplication->setCustomItems($customItems);

        self::assertSame(['foo' => 1, 'bar' => ['baz']], iterator_to_array($this->myApplication->getCustomItems()));
    }

    /**
     * Set up.
     */
    public function setUp()
    {
        $DS = DIRECTORY_SEPARATOR;

        $this->myApplication = new BasicTestApplication(FilePath::parse($DS . 'var' . $DS . 'www' . $DS));
        $this->myApplication->addViewRenderer(new BasicTestViewRenderer());
        $this->myApplication->addRoute(new Route('', BasicTestController::class));
        rmdir($this->myApplication->getTempPath());
    }

    /**
     * Tear down.
     */
    public function tearDown()
    {
        $this->myApplication = null;
    }

    /**
     * @var BasicTestApplication My application.
     */
    private $myApplication;
}
