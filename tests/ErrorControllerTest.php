<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests;

use BlueMvc\Core\Tests\Helpers\TestControllers\ErrorTestController;
use OutOfBoundsException;
use PHPUnit\Framework\TestCase;

/**
 * Test ErrorController class.
 */
class ErrorControllerTest extends TestCase
{
    /**
     * Test getException method.
     */
    public function testGetException()
    {
        $errorController = new ErrorTestController();

        self::assertNull($errorController->getThrowable());
    }

    /**
     * Test setException method.
     */
    public function testSetException()
    {
        $errorController = new ErrorTestController();
        $exception = new OutOfBoundsException('Test Exception');
        $errorController->setThrowable($exception);

        self::assertSame($exception, $errorController->getThrowable());
    }
}
