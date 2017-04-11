<?php

require_once __DIR__ . '/Helpers/TestControllers/ErrorTestController.php';

/**
 * Test ErrorController class.
 */
class ErrorControllerTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test getException method.
     */
    public function testGetException()
    {
        $errorController = new ErrorTestController();

        $this->assertNull($errorController->getException());
    }
}
