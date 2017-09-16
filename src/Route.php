<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core;

use BlueMvc\Core\Base\AbstractRoute;
use BlueMvc\Core\Exceptions\InvalidControllerClassException;
use BlueMvc\Core\Exceptions\InvalidRoutePathException;
use BlueMvc\Core\Interfaces\RequestInterface;

/**
 * Class representing a route.
 *
 * @since 1.0.0
 */
class Route extends AbstractRoute
{
    /**
     * Constructs a route.
     *
     * @since 1.0.0
     *
     * @param string $path                The path.
     * @param string $controllerClassName The controller class name.
     *
     * @throws \InvalidArgumentException       If any of the parameters are of invalid type.
     * @throws InvalidControllerClassException If the controller class name is invalid.
     * @throws InvalidRoutePathException       If the path is invalid.
     */
    public function __construct($path, $controllerClassName)
    {
        if (!is_string($path)) {
            throw new \InvalidArgumentException('$path parameter is not a string.');
        }

        parent::__construct($controllerClassName);

        // Validate path.
        self::myValidatePath($path);
        $this->myPath = $path;
    }

    /**
     * Return the path.
     *
     * @since 1.0.0
     *
     * @return string The path.
     */
    public function getPath()
    {
        return $this->myPath;
    }

    /**
     * Check if a route matches a request.
     *
     * @since 1.0.0
     *
     * @param RequestInterface $request The request.
     *
     * @return \BlueMvc\Core\Interfaces\RouteMatchInterface|null The route match if rout matches request, false otherwise.
     */
    public function matches(RequestInterface $request)
    {
        $path = $request->getUrl()->getPath();
        $directoryParts = $path->getDirectoryParts();

        if (count($directoryParts) === 0) {
            // Root path, e.g. "/" or "/foo"
            if ($this->myPath === '') {
                $action = $path->getFilename() !== null ? $path->getFilename() : '';

                return new RouteMatch($this->getControllerClassName(), $action);
            }
        } else {
            // Subdirectory, e.g. "/foo/" or "/foo/bar/"
            if ($directoryParts[0] === $this->myPath) {
                if (count($directoryParts) > 1) {
                    // Path is more than one level, e.g. "/foo/bar/" or "/foo/bar/baz".
                    // This means that the first level directory is the action and the rest, including file name, are the parameters.
                    $action = $directoryParts[1];
                    $parameters = array_merge(array_slice($directoryParts, 2), [$path->getFilename() !== null ? $path->getFilename() : '']);
                } else {
                    // Path is one level, e.g. "/foo/" or "/foo/bar".
                    // This means that the file name is the action and there are no parameters.
                    $action = $path->getFilename() !== null ? $path->getFilename() : '';
                    $parameters = [];
                }

                return new RouteMatch($this->getControllerClassName(), $action, $parameters);
            }
        }

        return null;
    }

    /**
     * Validates the path.
     *
     * @param string $path The path.
     *
     * @throws InvalidRoutePathException If the $path parameter is invalid.
     */
    private static function myValidatePath($path)
    {
        if (preg_match('/[^a-zA-Z0-9._-]/', $path, $matches)) {
            throw new InvalidRoutePathException('Path "' . $path . '" contains invalid character "' . $matches[0] . '".');
        }
    }

    /**
     * @var string My path.
     */
    private $myPath;
}
