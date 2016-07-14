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
     */
    public function __construct(ControllerInterface $controller)
    {
        $this->myController = $controller;
    }

    /**
     * @return ControllerInterface The controller.
     */
    public function getController()
    {
        return $this->myController;
    }

    /**
     * @var ControllerInterface My controller.
     */
    private $myController;
}
