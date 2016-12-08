<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\ActionResults;

use BlueMvc\Core\Base\ActionResults\AbstractRedirectActionResult;
use BlueMvc\Core\Http\StatusCode;

/**
 * Class representing a 302 Found action result.
 *
 * @since 1.0.0
 */
class RedirectResult extends AbstractRedirectActionResult
{
    /**
     * Constructs the action result.
     *
     * @since 1.0.0
     *
     * @param string $url The url as an absolute or relative url.
     *
     * @throws \InvalidArgumentException If the url parameter is not a string.
     */
    public function __construct($url = '')
    {
        parent::__construct(new StatusCode(StatusCode::FOUND), $url);
    }
}
