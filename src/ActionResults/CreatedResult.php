<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\ActionResults;

use BlueMvc\Core\Base\ActionResults\AbstractLocationActionResult;
use BlueMvc\Core\Http\StatusCode;

/**
 * Class representing a 201 Created action result.
 *
 * @since 1.0.0
 */
class CreatedResult extends AbstractLocationActionResult
{
    /**
     * Constructs the action result.
     *
     * @since 1.0.0
     *
     * @param string $location The url as an absolute or relative url.
     *
     * @throws \InvalidArgumentException If the location parameter is not a string.
     */
    public function __construct($location = '')
    {
        parent::__construct(new StatusCode(StatusCode::CREATED), $location);
    }
}
