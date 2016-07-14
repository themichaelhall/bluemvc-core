<?php

namespace BlueMvc\Core;

use BlueMvc\Core\Exceptions\RouteInvalidArgumentException;
use BlueMvc\Core\Interfaces\ControllerInterface;
use BlueMvc\Core\Interfaces\RequestInterface;
use BlueMvc\Core\Interfaces\RouteInterface;

/**
 * Class representing a default route.
 */
class DefaultRoute implements RouteInterface
{
    /**
     * Constructs a default route.
     *
     * @param string $controllerClassName The controller class name.
     *
     * @throws RouteInvalidArgumentException If any of the parameters are invalid.
     */
    public function __construct($controllerClassName)
    {
        $this->myControllerClassName = $controllerClassName;
    }

    /**
     * @return string The controller class name.
     */
    public function getControllerClassName()
    {
        return $this->myControllerClassName;
    }

    /**
     * Check if a route matches a request (which it always does for default route).
     *
     * @param RequestInterface $request The request.
     *
     * @return \BlueMvc\Core\Interfaces\RouteMatchInterface The route match.
     */
    public function matches(RequestInterface $request)
    {
        $path = $request->getUrl()->getPath();
        $directoryParts = $path->getDirectoryParts();
        $filename = $path->getFilename() !== null ? $path->getFilename() : '';

        if (count($directoryParts) === 0) {
            // Root path, e.g. "/" or "/foo"
            $action = $filename;
            $parameters = [];
        } else {
            // Subdirectory, e.g. "/foo/" or "/foo/bar/"
            $action = $directoryParts[0];
            $parameters = array_merge(array_slice($directoryParts, 1), [$filename]);
        }

        return new RouteMatch(static::myCreateController($this->myControllerClassName), $action, $parameters);
    }

    /**
     * Creates a controller class.
     *
     * @param string $controllerClassName The controller class name.
     *
     * @throws RouteInvalidArgumentException If the $controllerClass parameter is invalid.
     *
     * @return ControllerInterface The controller class.
     */
    private static function myCreateController($controllerClassName)
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

    /**
     * @var string My controller class name.
     */
    private $myControllerClassName;
}
