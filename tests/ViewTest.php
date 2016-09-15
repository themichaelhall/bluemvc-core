<?php

use BlueMvc\Core\View;

/**
 * Test View class.
 */
class ViewTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test create view with no model.
     */
    public function testCreateViewWithNoModel()
    {
        $view = new View();

        $this->assertNull($view->getModel());
    }

    /**
     * Test create view with model.
     */
    public function testCreateViewWithModel()
    {
        $view = new View('The Model');

        $this->assertSame('The Model', $view->getModel());
    }
}
