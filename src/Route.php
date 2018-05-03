<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */
declare(strict_types=1);

namespace BlueMvc\Core;

use BlueMvc\Core\Base\AbstractRoute;
use BlueMvc\Core\Exceptions\InvalidControllerClassException;
use BlueMvc\Core\Exceptions\InvalidRoutePathException;
use BlueMvc\Core\Interfaces\RequestInterface;
use BlueMvc\Core\Interfaces\RouteMatchInterface;

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
     * @throws InvalidControllerClassException If the controller class name is invalid.
     * @throws InvalidRoutePathException       If the path is invalid.
     */
    public function __construct(string $path, string $controllerClassName)
    {
        parent::__construct($controllerClassName);

        $this->path = self::splitPath($path);
    }

    /**
     * Check if a route matches a request.
     *
     * @since 1.0.0
     *
     * @param RequestInterface $request The request.
     *
     * @return RouteMatchInterface|null The route match if rout matches request, false otherwise.
     */
    public function matches(RequestInterface $request): ?RouteMatchInterface
    {
        $path = $request->getUrl()->getPath();
        $directoryParts = $path->getDirectoryParts();
        $filename = $path->getFilename() ?: '';

        if (count($this->path) === 0) {
            // My path is empty, i.e. should only match root path.
            if (count($directoryParts) !== 0) {
                return null;
            }

            return new RouteMatch($this->getControllerClassName(), $filename);
        }

        if (count($this->path) > count($directoryParts)) {
            // My path contains more than the path in request, e.g. path /foo/ should not match /bar
            return null;
        }

        // Check each parts.
        $index = 0;
        foreach ($this->path as $pathPart) {
            // Part of path does not match.
            if ($pathPart !== $directoryParts[$index]) {
                return null;
            }

            $index++;
        }

        if (count($directoryParts) > $index) {
            $action = $directoryParts[$index];
            $parameters = array_slice($directoryParts, $index + 1);
            $parameters[] = $filename;
        } else {
            $action = $filename;
            $parameters = [];
        }

        return new RouteMatch($this->getControllerClassName(), $action, $parameters);
    }

    /**
     * Splits a path in parts.
     *
     * @param string $path The path.
     *
     * @throws InvalidRoutePathException If the path is invalid.
     *
     * @return string[] The path as parts.
     */
    private static function splitPath(string $path): array
    {
        if ($path === '') {
            return [];
        }

        $result = explode('/', $path);

        foreach ($result as $pathPart) {
            if ($pathPart === '') {
                throw new InvalidRoutePathException('Path "' . $path . '" contains empty part.');
            }
            if (preg_match('/[^a-zA-Z0-9._-]/', $pathPart, $matches)) {
                throw new InvalidRoutePathException('Path "' . $path . '" contains invalid character "' . $matches[0] . '".');
            }
        }

        return $result;
    }

    /**
     * @var string[] My path.
     */
    private $path;
}
