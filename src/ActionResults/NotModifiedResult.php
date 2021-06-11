<?php

/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

declare(strict_types=1);

namespace BlueMvc\Core\ActionResults;

use BlueMvc\Core\Http\StatusCode;

/**
 * Class representing a 304 Not Modified action result.
 *
 * @since 1.0.0
 */
class NotModifiedResult extends ActionResult
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
