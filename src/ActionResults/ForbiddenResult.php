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
 * Class representing a 403 Forbidden action result.
 *
 * @since 1.0.0
 */
class ForbiddenResult extends ActionResult
{
    /**
     * Constructs the action result.
     *
     * @since 1.0.0
     *
     * @param string $content The content.
     */
    public function __construct(string $content = '')
    {
        parent::__construct($content, new StatusCode(StatusCode::FORBIDDEN));
    }
}
