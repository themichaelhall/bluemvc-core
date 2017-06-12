<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\ActionResults;

use BlueMvc\Core\Base\ActionResults\AbstractActionResult;
use BlueMvc\Core\Http\StatusCode;

/**
 * Class representing a 304 Not Modified action result.
 *
 * @since 1.0.0
 */
class NotModifiedResult extends AbstractActionResult
{
    /**
     * Constructs the action result.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct('', new StatusCode(StatusCode::NOT_MODIFIED));
    }
}
