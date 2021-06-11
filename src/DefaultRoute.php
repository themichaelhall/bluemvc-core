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
use BlueMvc\Core\Interfaces\RequestInterface;
use BlueMvc\Core\Interfaces\RouteMatchInterface;

/**
 * Class representing a default route.
 *
 * @since 1.0.0
 */
class DefaultRoute extends AbstractRoute
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
    public function __construct(string $controllerClassName)
    {
        parent::__construct($controllerClassName);
    }

    /**
     * Check if a route matches a request (which it always does for default route).
     *
     * @since 1.0.0
     *
     * @param RequestInterface $request The request.
     *
     * @return RouteMatchInterface|null The route match.
     */
    public function matches(RequestInterface $request): ?RouteMatchInterface
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
            $parameters = array_slice($directoryParts, 1);
            $parameters[] = $filename;
        }

        return new RouteMatch($this->getControllerClassName(), $action, $parameters);
    }
}
