<?php

namespace BlueMvc\Core\Interfaces;

/**
 * Interface for RouteMatch class.
 */
interface RouteMatchInterface
{
    /**
     * @return ControllerInterface The controller.
     */
    public function getController();
}
