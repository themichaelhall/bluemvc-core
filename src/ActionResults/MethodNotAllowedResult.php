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
 * Class representing a 405 Method Not Allowed action result.
 *
 * @since 1.0.0
 */
class MethodNotAllowedResult extends AbstractActionResult
{
    /**
     * Constructs the action result.
     *
     * @since 1.0.0
     *
     * @param string $content The content.
     *
     * @throws \InvalidArgumentException If the content parameter is not a string.
     */
    public function __construct($content = '')
    {
        parent::__construct($content, new StatusCode(StatusCode::METHOD_NOT_ALLOWED));
    }
}
