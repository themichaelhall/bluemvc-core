<?php

/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

declare(strict_types=1);

namespace BlueMvc\Core\ActionResults;

/**
 * Class representing a 201 Created action result exception.
 *
 * @since 2.1.0
 */
class CreatedResultException extends ActionResultException
{
    /**
     * Construct the action result exception.
     *
     * @since 2.1.0
     *
     * @param string $location The url as an absolute or relative url.
     */
    public function __construct(string $location = '')
    {
        parent::__construct(new CreatedResult($location));
    }
}
