<?php

/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

declare(strict_types=1);

namespace BlueMvc\Core;

use BlueMvc\Core\Interfaces\RouteMatchInterface;

/**
 * Class representing a route match.
 *
 * @since 1.0.0
 */
class RouteMatch implements RouteMatchInterface
{
    /**
     * Constructs a route match.
     *
     * @since 1.0.0
     *
     * @param string   $controllerClassName The controller class name.
     * @param string   $action              The action.
     * @param string[] $parameters          The parameters.
     */
    public function __construct(string $controllerClassName, string $action = '', array $parameters = [])
    {
        $this->controllerClassName = $controllerClassName;
        $this->action = $action;
        $this->parameters = $parameters;
    }

    /**
     * Returns the action.
     *
     * @since 1.0.0
     *
     * @return string The action.
     */
    public function getAction(): string
    {
        return $this->action;
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
     * Returns the parameters.
     *
     * @since 1.0.0
     *
     * @return string[] The parameters.
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @var string The action.
     */
    private string $action;

    /**
     * @var string The controller class name.
     */
    private string $controllerClassName;

    /**
     * @var string[] The parameters.
     */
    private array $parameters;
}
