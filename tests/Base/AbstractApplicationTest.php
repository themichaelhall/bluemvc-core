<?php

require_once __DIR__ . '/../Helpers/TestApplications/BasicTestApplication.php';
require_once __DIR__ . '/../Helpers/TestViewRenderers/BasicTestViewRenderer.php';
require_once __DIR__ . '/../Helpers/TestControllers/BasicTestController.php';

use BlueMvc\Core\Exceptions\InvalidFilePathException;
use BlueMvc\Core\Route;
use DataTypes\FilePath;

/**
 * Test AbstractApplication class (via derived test class).
 */
class AbstractApplicationTest extends PHPUnit_Framework_TestCase
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

        $this->assertSame($DS . 'var' . $DS . 'www' . $DS, $this->myApplication->getViewPath()->__toString());
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
     * Test setViewPath method with absolute path.
     */
    public function testSetViewPathWithAbsolutePath()
    {
        $DS = DIRECTORY_SEPARATOR;

        $this->myApplication->setViewPath(FilePath::parse($DS . 'bluemvc' . $DS . 'html' . $DS));

        $this->assertSame($DS . 'bluemvc' . $DS . 'html' . $DS, $this->myApplication->getViewPath()->__toString());
    }

    /**
     * Test setViewPath method with relative path.
     */
    public function testSetViewPathWithRelativePath()
    {
        $DS = DIRECTORY_SEPARATOR;

        $this->myApplication->setViewPath(FilePath::parse('..' . $DS . 'bluemvc' . $DS . 'html' . $DS));

        $this->assertSame($DS . 'var' . $DS . 'bluemvc' . $DS . 'html' . $DS, $this->myApplication->getViewPath()->__toString());
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

        $this->assertSame('Document root "' . $DS . 'var' . $DS . 'www' . $DS . 'file.txt" is not a directory.', $exceptionMessage);
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

        $this->assertSame('Document root "' . $DS . 'var' . $DS . 'www' . $DS . 'file.txt" is not a directory.', $exceptionMessage);
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

        $this->assertSame('Document root "var' . $DS . 'www' . $DS . '" is not an absolute path.', $exceptionMessage);
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

        $this->assertSame('Document root "var' . $DS . 'www' . $DS . '" is not an absolute path.', $exceptionMessage);
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

        $this->assertSame('View path "' . $DS . 'views' . $DS . 'file.txt" is not a directory.', $exceptionMessage);
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

        $this->assertSame('File path "' . $DS . 'var' . $DS . 'www' . $DS . '" can not be combined with file path "..' . $DS . '..' . $DS . '..' . $DS . 'views' . $DS . '": Absolute path is above root level.', $exceptionMessage);
    }

    /**
     * Test getTempPath method.
     */
    public function testGetTempPath()
    {
        $DS = DIRECTORY_SEPARATOR;

        $this->assertSame(sys_get_temp_dir() . $DS . 'bluemvc' . $DS . sha1($this->myApplication->getDocumentRoot()->__toString()) . $DS, $this->myApplication->getTempPath()->__toString());
    }

    /**
     * Test setTempPath method with absolute path.
     */
    public function testSetTempPathWithAbsolutePath()
    {
        $DS = DIRECTORY_SEPARATOR;

        $this->myApplication->setTempPath(FilePath::parse($DS . 'tmp' . $DS . 'bluemvc' . $DS));

        $this->assertSame($DS . 'tmp' . $DS . 'bluemvc' . $DS, $this->myApplication->getTempPath()->__toString());
    }

    /**
     * Test setTempPath method with relative path.
     */
    public function testSetTempPathWithRelativePath()
    {
        $DS = DIRECTORY_SEPARATOR;

        $this->myApplication->setTempPath(FilePath::parse('tmp' . $DS . 'bluemvc' . $DS));

        $this->assertSame($DS . 'var' . $DS . 'www' . $DS . 'tmp' . $DS . 'bluemvc' . $DS, $this->myApplication->getTempPath()->__toString());
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

        $this->assertSame('Temp path "' . $DS . 'tmp' . $DS . 'file.txt" is not a directory.', $exceptionMessage);
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

        $this->assertSame('File path "' . $DS . 'var' . $DS . 'www' . $DS . '" can not be combined with file path "..' . $DS . '..' . $DS . '..' . $DS . 'tmp' . $DS . '": Absolute path is above root level.', $exceptionMessage);
    }

    /**
     * Test isDebug method.
     */
    public function testIsDebug()
    {
        $this->assertFalse($this->myApplication->isDebug());
    }

    /**
     * Test setDebug method.
     */
    public function testSetDebug()
    {
        $this->myApplication->setDebug(true);

        $this->assertTrue($this->myApplication->isDebug());
    }

    /**
     * Test getErrorControllerClass method.
     */
    public function testGetErrorControllerClass()
    {
        $this->assertNull($this->myApplication->getErrorControllerClass());
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
