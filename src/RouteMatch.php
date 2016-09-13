<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */
namespace BlueMvc\Core;

use BlueMvc\Core\Interfaces\ControllerInterface;
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
     * Returns the controller.
     *
     * @since 1.0.0
     *
     * @return ControllerInterface The controller.
     */
    public function getController()
    {
        return $this->myController;
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
     * @var ControllerInterface My controller.
     */
    private $myController;

    /**
     * @var string[] My parameters.
     */
    private $myParameters;
}
