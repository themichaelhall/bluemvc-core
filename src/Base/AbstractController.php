<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */
declare(strict_types=1);

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
     * Returns the method of the action being processed or null if no action is being processed.
     *
     * @since 1.0.0
     *
     * @return \ReflectionMethod|null The method of the action being processed or null if no action is being processed.
     */
    public function getActionMethod(): ?\ReflectionMethod
    {
        return $this->actionMethod;
    }

    /**
     * Returns the application if controller is processing, null otherwise.
     *
     * @since 1.0.0
     *
     * @return ApplicationInterface|null The application if controller is processing, null otherwise.
     */
    public function getApplication(): ?ApplicationInterface
    {
        return $this->application;
    }

    /**
     * Returns the request if controller is processing, null otherwise.
     *
     * @since 1.0.0
     *
     * @return RequestInterface|null The request if controller is processing, null otherwise.
     */
    public function getRequest(): ?RequestInterface
    {
        return $this->request;
    }

    /**
     * Returns the response if controller is processing, null otherwise.
     *
     * @since 1.0.0
     *
     * @return ResponseInterface|null The response if controller is processing, null otherwise.
     */
    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
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
     */
    public function processRequest(ApplicationInterface $application, RequestInterface $request, ResponseInterface $response, string $action, array $parameters = []): void
    {
        $this->application = $application;
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Constructs the controller.
     *
     * @since 1.0.0
     */
    protected function __construct()
    {
        $this->application = null;
        $this->request = null;
        $this->response = null;
        $this->actionMethod = null;
    }

    /**
     * Returns true if post-action event is enabled, false otherwise.
     *
     * @since 1.1.0
     *
     * @return bool True if post-action event is enabled, false otherwise.
     */
    protected function isPostActionEventEnabled(): bool
    {
        return true;
    }

    /**
     * Returns true if pre-action event is enabled, false otherwise.
     *
     * @since 1.1.0
     *
     * @return bool True if pre-action event is enabled, false otherwise.
     */
    protected function isPreActionEventEnabled(): bool
    {
        return true;
    }

    /**
     * Post-action event.
     *
     * @since 1.0.0
     */
    protected function onPostActionEvent()
    {
    }

    /**
     * Pre-action event.
     *
     * @since 1.0.0
     */
    protected function onPreActionEvent()
    {
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
    protected function tryInvokeActionMethod(string $action, array $parameters, bool $isCaseSensitive, &$result, ?bool &$hasFoundActionMethod = null): bool
    {
        $reflectionClass = new \ReflectionClass($this);

        $actionMethod = self::findActionMethod($reflectionClass, $action, $isCaseSensitive);
        if ($actionMethod === null) {
            // Suitable action method not found.
            $hasFoundActionMethod = false;

            return false;
        }

        $hasFoundActionMethod = true;

        if (!self::actionMethodMatchesParameters($actionMethod, $parameters, $actualParameters)) {
            // Action method found, but parameters did not match.
            return false;
        }

        $result = $this->invokeActionMethod($actionMethod, $actualParameters);

        return true;
    }

    /**
     * Invoke action method.
     *
     * @param \ReflectionMethod $actionMethod The action method.
     * @param array             $parameters   The parameters.
     *
     * @return mixed|null The result.
     */
    private function invokeActionMethod(\ReflectionMethod $actionMethod, array $parameters)
    {
        $this->actionMethod = $actionMethod;

        // Handle pre-action event.
        $preActionResult = $this->isPreActionEventEnabled() ? $this->onPreActionEvent() : null;
        if ($preActionResult !== null) {
            return $preActionResult;
        }

        // Handle action method.
        $result = $actionMethod->invokeArgs($this, $parameters);

        // Handle post-action event.
        $postActionResult = $this->isPostActionEventEnabled() ? $this->onPostActionEvent() : null;
        if ($postActionResult !== null) {
            return $postActionResult;
        }

        return $result;
    }

    /**
     * Check if an action method matches an array of parameters.
     *
     * @param \ReflectionMethod $reflectionMethod The action method.
     * @param array             $parameters       The parameters.
     * @param array|null        $actualParameters The actual parameters, matching action methods actual signature or undefined if check failed.
     *
     * @return bool True if action method matches the parameters, false otherwise.
     */
    private static function actionMethodMatchesParameters(\ReflectionMethod $reflectionMethod, array $parameters, array &$actualParameters = null): bool
    {
        $parametersCount = count($parameters);

        if ($reflectionMethod->getNumberOfParameters() < $parametersCount) {
            return false;
        }

        $actualParameters = [];
        reset($parameters);

        foreach ($reflectionMethod->getParameters() as $reflectionParameter) {
            if (key($parameters) === null) {
                // No more parameters.
                if ($reflectionParameter->isOptional()) {
                    // It's still ok if the action method parameter is optional.
                    continue;
                }

                return false;
            }

            $parameter = current($parameters);

            if ($reflectionParameter->getType() !== null) {
                switch ($reflectionParameter->getType()->getName()) {
                    case 'int':
                        $intVal = intval($parameter);
                        if (strval($intVal) !== $parameter) {
                            return false;
                        }

                        $parameter = $intVal;
                        break;
                    case 'float':
                        if (!is_numeric($parameter)) {
                            return false;
                        }

                        $floatVal = floatval($parameter);
                        if (is_infinite($floatVal) || is_nan($floatVal)) {
                            return false;
                        }

                        $parameter = $floatVal;
                        break;
                    case 'bool':
                        if ($parameter === 'true') {
                            $parameter = true;
                        } elseif ($parameter === 'false') {
                            $parameter = false;
                        } else {
                            return false;
                        }

                        break;
                    case 'string':
                        $parameter = strval($parameter);
                        break;
                }
            }

            $actualParameters[] = $parameter;
            next($parameters);
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
    private static function findActionMethod(\ReflectionClass $reflectionClass, string $action, bool $isCaseSensitive): ?\ReflectionMethod
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
    private $application;

    /**
     * @var RequestInterface|null My request.
     */
    private $request;

    /**
     * @var ResponseInterface|null My response.
     */
    private $response;

    /**
     * @var \ReflectionMethod|null My action method.
     */
    private $actionMethod;
}
