<?php

namespace BlueMvc\Core\Interfaces;

/**
 * Interface for Route class.
 */
interface RouteInterface
{
    /**
     * @return string The controller class.
     */
    public function getControllerClass();
}
