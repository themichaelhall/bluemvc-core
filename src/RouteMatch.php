<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

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
     *
     * @throws \InvalidArgumentException If any of the parameters are of invalid type.
     */
    public function __construct($controllerClassName, $action = '', array $parameters = [])
    {
        if (!is_string($controllerClassName)) {
            throw new \InvalidArgumentException('$controllerClassName parameter is not a string.');
        }

        if (!is_string($action)) {
            throw new \InvalidArgumentException('$action parameter is not a string.');
        }

        $this->myControllerClassName = $controllerClassName;
        $this->myAction = $action;
        $this->myParameters = $parameters;
    }

    /**
     * Returns the action.
     *
     * @since 1.0.0
     *
     * @return string The action.
     */
    public function getAction()
    {
        return $this->myAction;
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
     * Returns the parameters.
     *
     * @since 1.0.0
     *
     * @return string[] The parameters.
     */
    public function getParameters()
    {
        return $this->myParameters;
    }

    /**
     * @var string My action.
     */
    private $myAction;

    /**
     * @var string My controller class name.
     */
    private $myControllerClassName;

    /**
     * @var string[] My parameters.
     */
    private $myParameters;
}
