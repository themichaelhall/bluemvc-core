<?php

namespace BlueMvc\Core;

use BlueMvc\Core\Exceptions\RouteInvalidArgumentException;
use BlueMvc\Core\Interfaces\ControllerInterface;

/**
 * Class representing a route.
 */
class Route
{
    /**
     * Constructs a route.
     *
     * @param string $path                The path.
     * @param string $controllerClassName The controller class name.
     *
     * @throws RouteInvalidArgumentException If any of the parameters are invalid.
     */
    public function __construct($path, $controllerClassName)
    {
        assert(is_string($path));
        assert(is_string($controllerClassName));

        // Validate path.
        self::myValidatePath($path);
        $this->myPath = $path;

        // Validate controller class name and save the corresponding controller class.
        $this->myControllerClass = self::myValidateControllerClassName($controllerClassName);
    }

    /**
     * @return \ReflectionClass The controller class.
     */
    public function getControllerClass()
    {
        return $this->myControllerClass;
    }

    /**
     * @return string The path.
     */
    public function getPath()
    {
        return $this->myPath;
    }

    /**
     * Validates the path.
     *
     * @param string $path The path.
     *
     * @throws RouteInvalidArgumentException If the $path parameter is invalid.
     */
    private static function myValidatePath($path)
    {
        if (preg_match('/[^a-zA-Z0-9._-]/', $path, $matches)) {
            throw new RouteInvalidArgumentException('Path "' . $path . '" contains invalid character "' . $matches[0] . '".');
        }
    }

    /**
     * Validates the controller class.
     *
     * @param string $controllerClassName The controller class name.
     *
     * @throws RouteInvalidArgumentException If the $controllerClass parameter is invalid.
     *
     * @return \ReflectionClass The controller class.
     */
    private static function myValidateControllerClassName($controllerClassName)
    {
        try {
            $controllerClass = new \ReflectionClass($controllerClassName);

            if (!$controllerClass->implementsInterface(ControllerInterface::class)) {
                throw new RouteInvalidArgumentException('Controller class "' . $controllerClassName . '" does not implement "' . ControllerInterface::class . '".');
            }
        } catch (\ReflectionException $e) {
            throw new RouteInvalidArgumentException('Controller class "' . $controllerClassName . '" does not exist.');
        }

        return $controllerClass;
    }

    /**
     * @var string My path.
     */
    private $myPath;

    /**
     * @var \ReflectionClass My controller class.
     */
    private $myControllerClass;
}
