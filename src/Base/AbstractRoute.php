<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */
namespace BlueMvc\Core\Base;

use BlueMvc\Core\Exceptions\InvalidControllerClassException;
use BlueMvc\Core\Interfaces\ControllerInterface;
use BlueMvc\Core\Interfaces\RouteInterface;

/**
 * Abstract class representing a route.
 *
 * @since 1.0.0
 */
abstract class AbstractRoute implements RouteInterface
{
    /**
     * Try to create a controller class.
     *
     * @since 1.0.0
     *
     * @param string $controllerClassName The controller class name.
     *
     * @throws InvalidControllerClassException If the $controllerClassName parameter is invalid.
     *
     * @return ControllerInterface The controller class.
     */
    protected static function tryCreateController($controllerClassName)
    {
        try {
            $controllerClass = new \ReflectionClass($controllerClassName);

            if (!$controllerClass->implementsInterface(ControllerInterface::class)) {
                throw new InvalidControllerClassException('Controller class "' . $controllerClassName . '" does not implement "' . ControllerInterface::class . '".');
            }
        } catch (\ReflectionException $e) {
            throw new InvalidControllerClassException('Controller class "' . $controllerClassName . '" does not exist.');
        }

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $controllerClass->newInstance();
    }
}
