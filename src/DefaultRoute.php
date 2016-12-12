<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core;

use BlueMvc\Core\Exceptions\InvalidControllerClassException;
use BlueMvc\Core\Interfaces\RequestInterface;
use BlueMvc\Core\Interfaces\RouteInterface;

/**
 * Class representing a default route.
 *
 * @since 1.0.0
 */
class DefaultRoute implements RouteInterface
{
    /**
     * Constructs a default route.
     *
     * @since 1.0.0
     *
     * @param string $controllerClassName The controller class name.
     *
     * @throws InvalidControllerClassException If the controller class name is invalid.
     */
    public function __construct($controllerClassName)
    {
        $this->myControllerClassName = $controllerClassName;
    }

    /**
     * Returns the controller class name.
     *
     * @since 1.0.0
     *
     * @return string The controller class name.
     */
    public function getControllerClassName()
    {
        return $this->myControllerClassName;
    }

    /**
     * Check if a route matches a request (which it always does for default route).
     *
     * @since 1.0.0
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

        return new RouteMatch($this->myControllerClassName, $action, $parameters);
    }

    /**
     * @var string My controller class name.
     */
    private $myControllerClassName;
}
