<?php

/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

declare(strict_types=1);

namespace BlueMvc\Core\ActionResults;

/**
 * Class representing a 401 Unauthorized action result exception.
 *
 * @since 2.1.0
 */
class UnauthorizedResultException extends ActionResultException
{
    /**
     * Constructs the action result exception.
     *
     * @since 2.1.0
     *
     * @param string $wwwAuthenticate The WWW-Authenticate header.
     */
    public function __construct(string $wwwAuthenticate = 'Basic')
    {
        parent::__construct(new UnauthorizedResult($wwwAuthenticate));
    }
}
