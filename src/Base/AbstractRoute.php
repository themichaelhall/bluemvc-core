<?php

namespace BlueMvc\Core\Base;

use BlueMvc\Core\Exceptions\RouteInvalidArgumentException;
use BlueMvc\Core\Interfaces\ControllerInterface;
use BlueMvc\Core\Interfaces\RouteInterface;

/**
 * Abstract class representing a route.
 */
abstract class AbstractRoute implements RouteInterface
{
    /**
     * Try to create a controller class.
     *
     * @param string $controllerClassName The controller class name.
     *
     * @throws RouteInvalidArgumentException If the $controllerClassName parameter is invalid.
     *
     * @return ControllerInterface The controller class.
     */
    protected static function tryCreateController($controllerClassName)
    {
        try {
            $controllerClass = new \ReflectionClass($controllerClassName);

            if (!$controllerClass->implementsInterface(ControllerInterface::class)) {
                throw new RouteInvalidArgumentException('Controller class "' . $controllerClassName . '" does not implement "' . ControllerInterface::class . '".');
            }
        } catch (\ReflectionException $e) {
            throw new RouteInvalidArgumentException('Controller class "' . $controllerClassName . '" does not exist.');
        }

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $controllerClass->newInstance();
    }
}
