<?php

/** @noinspection PhpDeprecationInspection */

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Helpers\TestActionResults;

use BlueMvc\Core\Base\ActionResults\AbstractActionResult;
use BlueMvc\Core\Interfaces\Http\StatusCodeInterface;

/**
 * A basic test action result.
 */
class BasicTestActionResult extends AbstractActionResult
{
    /**
     * BasicTestActionResult constructor.
     *
     * @param string              $content    The content.
     * @param StatusCodeInterface $statusCode The status code.
     */
    public function __construct(string $content, StatusCodeInterface $statusCode)
    {
        parent::__construct($content, $statusCode);
    }
}
