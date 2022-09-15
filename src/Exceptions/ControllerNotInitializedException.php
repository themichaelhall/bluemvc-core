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
 * Exception thrown when trying to get the application, request or response from an uninitialized controller.
 *
 * @since 3.0.0
 */
class ControllerNotInitializedException extends Exception
{
}
