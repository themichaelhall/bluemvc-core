<?php

/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

declare(strict_types=1);

namespace BlueMvc\Core\ActionResults;

/**
 * Class representing a 404 Not Found action result exception.
 *
 * @since 2.1.0
 */
class NotFoundResultException extends ActionResultException
{
    /**
     * Constructs the action result exception.
     *
     * @since 2.1.0
     *
     * @param string $content The content.
     */
    public function __construct(string $content = '')
    {
        parent::__construct(new NotFoundResult($content));
    }
}
