<?php

/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

declare(strict_types=1);

namespace BlueMvc\Core\Base;

use BlueMvc\Core\Exceptions\InvalidControllerClassException;
use BlueMvc\Core\Interfaces\ControllerInterface;
use BlueMvc\Core\Interfaces\RequestInterface;
use BlueMvc\Core\Interfaces\RouteInterface;
use BlueMvc\Core\Interfaces\RouteMatchInterface;

/**
 * Abstract class representing a route.
 *
 * @since 1.0.0
 */
abstract class AbstractRoute implements RouteInterface
{
    /**
     * Constructs a route.
     *
     * @since 1.0.0
     *
     * @param string $controllerClassName The controller class name.
     *
     * @throws InvalidControllerClassException If the controller class name is invalid.
     */
    public function __construct(string $controllerClassName)
    {
        if (!is_a($controllerClassName, ControllerInterface::class, true)) {
            throw new InvalidControllerClassException('"' . $controllerClassName . '" is not a valid controller class.');
        }

        $this->controllerClassName = $controllerClassName;
    }

    /**
     * Returns the controller class name.
     *
     * @since 1.0.0
     *
     * @return string The controller class name.
     */
    public function getControllerClassName(): string
    {
        return $this->controllerClassName;
    }

    /**
     * Check if a route matches a request.
     *
     * @since 1.0.0
     *
     * @param RequestInterface $request The request.
     *
     * @return RouteMatchInterface|null The route match if rout matches request, null otherwise.
     */
    abstract public function matches(RequestInterface $request): ?RouteMatchInterface;

    /**
     * @var string The controller class name.
     */
    private string $controllerClassName;
}
