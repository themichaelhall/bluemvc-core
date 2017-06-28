<?php

namespace BlueMvc\Core\Tests;

use BlueMvc\Core\View;

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
}
