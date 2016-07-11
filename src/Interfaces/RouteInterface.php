<?php

namespace BlueMvc\Core\Interfaces;

/**
 * Interface for Route class.
 */
interface RouteInterface
{
    /**
     * @return \ReflectionClass The controller class.
     */
    public function getControllerClass();
}
