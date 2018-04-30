<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */
declare(strict_types=1);

namespace BlueMvc\Core\ActionResults;

use BlueMvc\Core\Base\ActionResults\AbstractActionResult;
use BlueMvc\Core\Http\StatusCode;

/**
 * Class representing a 204 No Content action result.
 *
 * @since 1.0.0
 */
class NoContentResult extends AbstractActionResult
{
    /**
     * Constructs the action result.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct('', new StatusCode(StatusCode::NO_CONTENT));
    }
}
