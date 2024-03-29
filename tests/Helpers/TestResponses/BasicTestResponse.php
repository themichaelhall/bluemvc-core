<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Helpers\TestResponses;

use BlueMvc\Core\Base\AbstractResponse;

/**
 * Basic test response class.
 */
class BasicTestResponse extends AbstractResponse
{
    /**
     * Does nothing.
     */
    public function output(): void
    {
    }
}
