<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\ActionResults;

use BlueMvc\Core\ActionResults\ActionResult;
use BlueMvc\Core\ActionResults\ActionResultException;
use BlueMvc\Core\Http\StatusCode;
use PHPUnit\Framework\TestCase;

/**
 * Test ActionResultException class.
 */
class ActionResultExceptionTest extends TestCase
{
    /**
     * Test getActionResult method.
     */
    public function testGetActionResult()
    {
        $actionResult = new ActionResult('Action result content', new StatusCode(StatusCode::NO_CONTENT));
        $actionResultException = new ActionResultException($actionResult);

        self::assertSame($actionResult, $actionResultException->getActionResult());
    }
}
