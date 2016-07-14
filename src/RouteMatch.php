<?php

namespace BlueMvc\Core;

use BlueMvc\Core\Interfaces\ControllerInterface;
use BlueMvc\Core\Interfaces\RouteMatchInterface;

/**
 * Class representing a route match.
 */
class RouteMatch implements RouteMatchInterface
{
    /**
     * Constructs a route match.
     *
     * @param ControllerInterface $controller The controller.
     * @param string              $action     The action.
     * @param string[]            $parameters The parameters.
     */
    public function __construct(ControllerInterface $controller, $action = '', array $parameters = [])
    {
        assert(is_string($action));

        $this->myController = $controller;
        $this->myAction = $action;
        $this->myParameters = $parameters;
    }

    /**
     * @return string The action.
     */
    public function getAction()
    {
        return $this->myAction;
    }

    /**
     * @return ControllerInterface The controller.
     */
    public function getController()
    {
        return $this->myController;
    }

    /**
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
     * @var ControllerInterface My controller.
     */
    private $myController;

    /**
     * @var string[] My parameters.
     */
    private $myParameters;
}
