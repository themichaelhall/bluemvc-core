<?php

require_once __DIR__ . '/../Helpers/TestApplications/BasicTestApplication.php';

use DataTypes\FilePath;

/**
 * Test AbstractApplication class.
 */
class AbstractApplicationTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test getViewPath method.
     */
    public function testGetViewPath()
    {
        $DS = DIRECTORY_SEPARATOR;

        $this->assertSame($DS . 'var' . $DS . 'www' . $DS, $this->myApplication->getViewPath()->__toString());
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
     * Set up.
     */
    public function setUp()
    {
        $DS = DIRECTORY_SEPARATOR;

        $this->myApplication = new BasicTestApplication(FilePath::parse($DS . 'var' . $DS . 'www' . $DS));
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
