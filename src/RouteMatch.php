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
     */
    public function __construct(ControllerInterface $controller, $action = '')
    {
        $this->myController = $controller;
        $this->myAction = $action;
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
     * @var string My action.
     */
    private $myAction;

    /**
     * @var ControllerInterface My controller.
     */
    private $myController;
}
