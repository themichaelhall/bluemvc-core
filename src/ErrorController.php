<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

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

    /**
     * Constructs the controller.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
    }
}
