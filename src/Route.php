<?php

namespace BlueMvc\Core;

use BlueMvc\Core\Exceptions\RouteInvalidArgumentException;

/**
 * Class representing a route.
 */
class Route
{
    /**
     * Constructs a route.
     *
     * @param string $path            The path.
     * @param string $controllerClass The controller class.
     *
     * @throws RouteInvalidArgumentException If the $path parameter is invalid.
     */
    public function __construct($path, $controllerClass)
    {
        assert(is_string($path));
        assert(is_string($controllerClass));

        // Validate path.
        $this->myValidatePath($path);

        $this->myPath = $path;
        $this->myControllerClass = $controllerClass;
    }

    /**
     * @return string The controller class.
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
    private function myValidatePath($path)
    {
        if (preg_match('/[^a-zA-Z0-9._-]/', $path, $matches)) {
            throw new RouteInvalidArgumentException('Path "' . $path . '" contains invalid character "' . $matches[0] . '".');
        }
    }

    /**
     * @var string My path.
     */
    private $myPath;

    /**
     * @var string My controller class.
     */
    private $myControllerClass;
}
