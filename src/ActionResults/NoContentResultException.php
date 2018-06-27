<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */
declare(strict_types=1);

namespace BlueMvc\Core\ActionResults;

/**
 * Class representing a 204 No Content action result exception.
 *
 * @since 2.1.0
 */
class NoContentResultException extends ActionResultException
{
    /**
     * Constructs the action result exception.
     *
     * @since 2.1.0
     */
    public function __construct()
    {
        parent::__construct(new NoContentResult());
    }
}
