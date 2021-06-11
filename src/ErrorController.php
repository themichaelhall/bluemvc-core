<?php

/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

declare(strict_types=1);

namespace BlueMvc\Core;

use BlueMvc\Core\Interfaces\ErrorControllerInterface;
use BlueMvc\Core\Traits\ErrorControllerTrait;

/**
 * Class representing an error handling controller.
 *
 * @since 1.0.0
 */
abstract class ErrorController extends Controller implements ErrorControllerInterface
{
    use ErrorControllerTrait;
}
