<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\Interfaces;

/**
 * Interface for RouteMatch class.
 *
 * @since 1.0.0
 */
interface RouteMatchInterface
{
    /**
     * Returns the action.
     *
     * @since 1.0.0
     *
     * @return string The action.
     */
    public function getAction();

    /**
     * Returns the controller class name.
     *
     * @since 1.0.0
     *
     * @return string The controller class name.
     */
    public function getControllerClassName();

    /**
     * Returns the parameters.
     *
     * @since 1.0.0
     *
     * @return string[] The parameters.
     */
    public function getParameters();
}
