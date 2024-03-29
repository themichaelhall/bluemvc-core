<?php

/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

declare(strict_types=1);

namespace BlueMvc\Core\ActionResults;

use BlueMvc\Core\Base\ActionResults\AbstractLocationActionResult;
use BlueMvc\Core\Http\StatusCode;

/**
 * Class representing a 301 Moved Permanently action result.
 *
 * @since 1.0.0
 */
class PermanentRedirectResult extends AbstractLocationActionResult
{
    /**
     * Constructs the action result.
     *
     * @since 1.0.0
     *
     * @param string $location The location as an absolute or relative url.
     */
    public function __construct(string $location = '')
    {
        parent::__construct($location, new StatusCode(StatusCode::MOVED_PERMANENTLY));
    }
}
