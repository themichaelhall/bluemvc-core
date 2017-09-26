<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\Base;

use BlueMvc\Core\Interfaces\ApplicationInterface;
use BlueMvc\Core\Interfaces\ControllerInterface;
use BlueMvc\Core\Interfaces\RequestInterface;
use BlueMvc\Core\Interfaces\ResponseInterface;

/**
 * Abstract class representing a controller.
 *
 * @since 1.0.0
 */
abstract class AbstractController implements ControllerInterface
{
    /**
     * Returns the action being processed or nu if no action is being processed.
     *
     * @since 1.0.0
     *
     * @return string|null The action being processed or nu if no action is being processed.
     */
    public function getAction()
    {
        return $this->myAction;
    }

    /**
     * Returns the application if controller is processing, null otherwise.
     *
     * @since 1.0.0
     *
     * @return ApplicationInterface|null The application if controller is processing, null otherwise.
     */
    public function getApplication()
    {
        return $this->myApplication;
    }

    /**
     * Returns the request if controller is processing, null otherwise.
     *
     * @since 1.0.0
     *
     * @return RequestInterface|null The request if controller is processing, null otherwise.
     */
    public function getRequest()
    {
        return $this->myRequest;
    }

    /**
     * Returns the response if controller is processing, null otherwise.
     *
     * @since 1.0.0
     *
     * @return ResponseInterface|null The response if controller is processing, null otherwise.
     */
    public function getResponse()
    {
        return $this->myResponse;
    }

    /**
     * Processes a request.
     *
     * @since 1.0.0
     *
     * @param ApplicationInterface $application The application.
     * @param RequestInterface     $request     The request.
     * @param ResponseInterface    $response    The response.
     * @param string               $action      The action.
     * @param array                $parameters  The parameters.
     *
     * @return bool True if request was actually processed, false otherwise.
     */
    public function processRequest(ApplicationInterface $application, RequestInterface $request, ResponseInterface $response, $action, array $parameters = [])
    {
        $this->myApplication = $application;
        $this->myRequest = $request;
        $this->myResponse = $response;

        return false;
    }

    /**
     * Constructs the controller.
     *
     * @since 1.0.0
     */
    protected function __construct()
    {
        $this->myApplication = null;
        $this->myRequest = null;
        $this->myResponse = null;
        $this->myAction = null;
    }

    /**
     * Post-action event.
     *
     * @since 1.0.0
     *
     * @return null
     */
    protected function onPostActionEvent()
    {
        return null;
    }

    /**
     * Pre-action event.
     *
     * @since 1.0.0
     *
     * @return null
     */
    protected function onPreActionEvent()
    {
        return null;
    }

    /**
     * Try to invoke an action method.
     *
     * @since 1.0.0
     *
     * @param string $action               The action.
     * @param array  $parameters           The parameters.
     * @param bool   $isCaseSensitive      True if action method is case sensitive, false otherwise.
     * @param mixed  $result               The result.
     * @param bool   $hasFoundActionMethod If true, action method was found, false otherwise.
     *
     * @return bool True if action method was invoked successfully, false otherwise.
     */
    protected function tryInvokeActionMethod($action, array $parameters, $isCaseSensitive, &$result, &$hasFoundActionMethod = null)
    {
        $reflectionClass = new \ReflectionClass($this);

        $actionMethod = self::myFindActionMethod($reflectionClass, $action, $isCaseSensitive);
        if ($actionMethod === null) {
            // Suitable action method not found.
            $hasFoundActionMethod = false;

            return false;
        }

        $hasFoundActionMethod = true;

        if (!self::myActionMethodMatchesParameters($actionMethod, $parameters)) {
            // Action method found, but parameters did not match.
            return false;
        }

        $this->myAction = $action;

        // Handle pre-action event.
        $preActionResult = $this->onPreActionEvent();
        if ($preActionResult !== null) {
            $result = $preActionResult;

            return true;
        }

        // Handle action method.
        $result = $actionMethod->invokeArgs($this, $parameters);

        // Handle post-action event.
        $postActionResult = $this->onPostActionEvent();
        if ($postActionResult !== null) {
            $result = $postActionResult;

            return true;
        }

        return true;
    }

    /**
     * Check if an action method matches an array of parameters.
     *
     * @param \ReflectionMethod $reflectionMethod The action method.
     * @param array             $parameters       The parameters.
     *
     * @return bool True if action method matches the parameters, false otherwise.
     */
    private static function myActionMethodMatchesParameters(\ReflectionMethod $reflectionMethod, array $parameters)
    {
        $parametersCount = count($parameters);

        if ($reflectionMethod->getNumberOfParameters() < $parametersCount) {
            return false;
        }

        $index = 0;
        foreach ($reflectionMethod->getParameters() as $reflectionParameter) {
            if ($index >= $parametersCount && !$reflectionParameter->isOptional()) {
                return false;
            }

            $index++;
        }

        return true;
    }

    /**
     * Try to find an action method by action.
     *
     * @param \ReflectionClass $reflectionClass The ReflectionClass.
     * @param string           $action          The action.
     * @param bool             $isCaseSensitive True if action method is case sensitive, false otherwise.
     *
     * @return \ReflectionMethod|null The action method or null if no action method was found.
     */
    private static function myFindActionMethod(\ReflectionClass $reflectionClass, $action, $isCaseSensitive)
    {
        $actionMethod = null;

        // Methods can not begin with a digit, prepend underscore to make it possible.
        if (strlen($action) > 0 && ctype_digit($action[0])) {
            $action = '_' . $action;
        }

        try {
            $actionMethod = $reflectionClass->getMethod($action . 'Action');

            if ($isCaseSensitive && $action !== substr($actionMethod->getName(), 0, strlen($action))) {
                return null;
            }

            if (!$actionMethod->isPublic()) {
                return null;
            }
        } catch (\ReflectionException $e) {
            return null;
        }

        return $actionMethod;
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

    /**
     * @var string|null My action.
     */
    private $myAction;
}
