<?php

/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

declare(strict_types=1);

namespace BlueMvc\Core\Exceptions;

use Exception;

/**
 * Exception used when no view renderer was added to the application when using a view.
 *
 * @since 1.0.0
 */
class MissingViewRendererException extends Exception
{
}
