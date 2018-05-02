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
     * Constructs the test response.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Does nothing.
     */
    public function output(): void
    {
    }
}
