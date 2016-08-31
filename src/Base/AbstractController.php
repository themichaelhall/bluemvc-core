<?php

namespace BlueMvc\Core\Base;

use BlueMvc\Core\Interfaces\ApplicationInterface;
use BlueMvc\Core\Interfaces\ControllerInterface;
use BlueMvc\Core\Interfaces\RequestInterface;
use BlueMvc\Core\Interfaces\ResponseInterface;
use BlueMvc\Core\Interfaces\RouteMatchInterface;

/**
 * Abstract class representing a controller.
 */
abstract class AbstractController implements ControllerInterface
{
    /**
     * @return ApplicationInterface|null The application if controller is processing, null otherwise.
     */
    public function getApplication()
    {
        return $this->myApplication;
    }

    /**
     * @return RequestInterface|null The request if controller is processing, null otherwise.
     */
    public function getRequest()
    {
        return $this->myRequest;
    }

    /**
     * @return ResponseInterface|null The response if controller is processing, null otherwise.
     */
    public function getResponse()
    {
        return $this->myResponse;
    }

    /**
     * Processes a request.
     *
     * @param ApplicationInterface $application The application.
     * @param RequestInterface     $request     The request.
     * @param ResponseInterface    $response    The response.
     * @param RouteMatchInterface  $routeMatch  The route match.
     *
     * @return bool True if request was actually processed, false otherwise.
     */
    public function processRequest(ApplicationInterface $application, RequestInterface $request, ResponseInterface $response, RouteMatchInterface $routeMatch)
    {
        $this->myApplication = $application;
        $this->myRequest = $request;
        $this->myResponse = $response;

        return false;
    }

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

    /**
     * @var ApplicationInterface|null My application.
     */
    private $myApplication;

    /**
     * @var RequestInterface|null My request.
     */
    private $myRequest;

    /**
     * @var ResponseInterface|null My response.
     */
    private $myResponse;
}
