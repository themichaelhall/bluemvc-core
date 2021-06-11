<?php

/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

declare(strict_types=1);

namespace BlueMvc\Core\ActionResults;

use BlueMvc\Core\Exceptions\InvalidActionResultContentException;
use BlueMvc\Core\Interfaces\Http\StatusCodeInterface;

/**
 * Class representing a JSON action result exception.
 *
 * @since 2.1.0
 */
class JsonResultException extends ActionResultException
{
    /**
     * Constructs the action result exception.
     *
     * @since 2.1.0
     *
     * @param mixed                    $content    The content.
     * @param StatusCodeInterface|null $statusCode The status code or null for status code 200 OK.
     *
     * @throws InvalidActionResultContentException If the content could not be json-encoded.
     */
    public function __construct($content, ?StatusCodeInterface $statusCode = null)
    {
        parent::__construct(new JsonResult($content, $statusCode));
    }
}
