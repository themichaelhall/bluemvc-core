<?php

namespace BlueMvc\Core\Interfaces;

/**
 * Interface for RouteMatch class.
 */
interface RouteMatchInterface
{
    /**
     * @return string The action.
     */
    public function getAction();

    /**
     * @return ControllerInterface The controller.
     */
    public function getController();
}
