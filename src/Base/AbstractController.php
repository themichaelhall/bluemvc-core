<?php

namespace BlueMvc\Core\Base;

use BlueMvc\Core\Interfaces\ControllerInterface;

/**
 * Abstract class representing a controller.
 */
abstract class AbstractController implements ControllerInterface
{
    /**
     * Try to invoke an action method.
     *
     * @param string $action     The action.
     * @param array  $parameters The parameters.
     * @param mixed  $result     The result.
     *
     * @return bool True if action method was invoked successfully, false otherwise.
     */
    protected function tryInvokeActionMethod($action, array $parameters, &$result)
    {
        $reflectionClass = new \ReflectionClass($this);

        try {
            $actionMethod = $reflectionClass->getMethod($action . 'Action');
        } catch (\ReflectionException $e) {
            return false;
        }

        $result = $actionMethod->invokeArgs($this, $parameters);

        return true;
    }
}
