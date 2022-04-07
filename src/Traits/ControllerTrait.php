<?php

/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

declare(strict_types=1);

namespace BlueMvc\Core\Traits;

use BlueMvc\Core\Interfaces\ActionResults\ActionResultExceptionInterface;
use BlueMvc\Core\Interfaces\ApplicationInterface;
use BlueMvc\Core\Interfaces\RequestInterface;
use BlueMvc\Core\Interfaces\ResponseInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;

/**
 * Trait for standard controller functionality.
 *
 * @since 2.2.0
 */
trait ControllerTrait
{
    /**
     * Returns the method of the action being processed or null if no action is being processed.
     *
     * @since 2.2.0
     *
     * @return ReflectionMethod|null The method of the action being processed or null if no action is being processed.
     */
    public function getActionMethod(): ?ReflectionMethod
    {
        return $this->actionMethod;
    }

    /**
     * Returns the application if controller is processing, null otherwise.
     *
     * @since 2.2.0
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
     * @since 2.2.0
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
     * @since 2.2.0
     *
     * @return ResponseInterface|null The response if controller is processing, null otherwise.
     */
    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }

    /**
     * Returns true if post-action event is enabled, false otherwise.
     *
     * @since 2.2.0
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
     * @since 2.2.0
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
     * @since 2.2.0
     */
    protected function onPostActionEvent()
    {
    }

    /**
     * Pre-action event.
     *
     * @since 2.2.0
     */
    protected function onPreActionEvent()
    {
    }

    /**
     * Initializes the controller.
     *
     * @param ApplicationInterface $application The application.
     * @param RequestInterface     $request     The request.
     * @param ResponseInterface    $response    The response.
     */
    private function init(ApplicationInterface $application, RequestInterface $request, ResponseInterface $response): void
    {
        $this->application = $application;
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Try to invoke an action method.
     *
     * @param string   $action               The action.
     * @param array    $parameters           The parameters.
     * @param bool     $isCaseSensitive      True if action method is case-sensitive, false otherwise.
     * @param callable $resultHandler        A callable that takes the result (mixed) from the action method call as a parameter.
     * @param bool     $hasFoundActionMethod If true, action method was found, false otherwise.
     *
     * @return bool True if action method was invoked successfully, false otherwise.
     */
    private function tryInvokeActionMethod(string $action, array $parameters, bool $isCaseSensitive, callable $resultHandler, ?bool &$hasFoundActionMethod = null): bool
    {
        $reflectionClass = new ReflectionClass($this);

        $actionMethod = self::findActionMethod($reflectionClass, $action, $isCaseSensitive);
        if ($actionMethod === null) {
            // Suitable action method not found.
            $hasFoundActionMethod = false;

            return false;
        }

        $hasFoundActionMethod = true;

        if (!self::actionMethodMatchesParameters($actionMethod, $parameters, $adjustedParameters)) {
            // Action method found, but parameters did not match.
            return false;
        }

        $this->invokeActionMethod($actionMethod, $adjustedParameters, $resultHandler);

        return true;
    }

    /**
     * Try to find an action method by action.
     *
     * @param ReflectionClass $reflectionClass The ReflectionClass.
     * @param string          $action          The action.
     * @param bool            $isCaseSensitive True if action method is case-sensitive, false otherwise.
     *
     * @return ReflectionMethod|null The action method or null if no action method was found.
     */
    private static function findActionMethod(ReflectionClass $reflectionClass, string $action, bool $isCaseSensitive): ?ReflectionMethod
    {
        // Methods can not begin with a digit, prepend underscore to make it possible.
        if (strlen($action) > 0 && ctype_digit($action[0])) {
            $action = '_' . $action;
        }

        try {
            $actionMethod = $reflectionClass->getMethod($action . 'Action');

            if ($isCaseSensitive && !str_starts_with($actionMethod->getName(), $action)) {
                return null;
            }

            if (!$actionMethod->isPublic()) {
                return null;
            }

            return $actionMethod;
        } catch (ReflectionException) {
            return null;
        }
    }

    /**
     * Invoke action method.
     *
     * @param ReflectionMethod $actionMethod  The action method.
     * @param array            $parameters    The parameters.
     * @param callable         $resultHandler A callable that takes the result (mixed) from the action method call as a parameter.
     */
    private function invokeActionMethod(ReflectionMethod $actionMethod, array $parameters, callable $resultHandler): void
    {
        $this->actionMethod = $actionMethod;

        // Handle pre-action event.
        try {
            /** @noinspection PhpVoidFunctionResultUsedInspection */
            $preActionResult = $this->isPreActionEventEnabled() ? $this->onPreActionEvent() : null;
        } catch (ActionResultExceptionInterface $exception) {
            $preActionResult = $exception->getActionResult();
        }

        if ($preActionResult !== null) {
            $resultHandler($preActionResult);

            return;
        }

        // Handle action method.
        try {
            $result = $actionMethod->invokeArgs($this, $parameters);
        } catch (ActionResultExceptionInterface $exception) {
            $result = $exception->getActionResult();
        }

        $resultHandler($result);

        // Handle post-action event.
        try {
            /** @noinspection PhpVoidFunctionResultUsedInspection */
            $postActionResult = $this->isPostActionEventEnabled() ? $this->onPostActionEvent() : null;
        } catch (ActionResultExceptionInterface $exception) {
            $postActionResult = $exception->getActionResult();
        }
        if ($postActionResult !== null) {
            $resultHandler($postActionResult);
        }
    }

    /**
     * Check if an action method matches an array of parameters.
     *
     * @param ReflectionMethod $reflectionMethod   The action method.
     * @param array            $parameters         The parameters.
     * @param array|null       $adjustedParameters The actual parameters, matching action methods actual signature or undefined if check failed.
     *
     * @return bool True if action method matches the parameters, false otherwise.
     */
    private static function actionMethodMatchesParameters(ReflectionMethod $reflectionMethod, array $parameters, array &$adjustedParameters = null): bool
    {
        $parametersCount = count($parameters);

        if ($reflectionMethod->getNumberOfParameters() < $parametersCount) {
            return false;
        }

        $adjustedParameters = [];
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
            if (!self::actionMethodParameterMatchesParameter($reflectionParameter, $parameter, $adjustedParameter)) {
                return false;
            }

            $adjustedParameters[] = $adjustedParameter;
            next($parameters);
        }

        return true;
    }

    /**
     * Check if an action method parameter matches a parameter.
     *
     * @param ReflectionParameter $reflectionParameter The action method parameter.
     * @param mixed               $parameter           The parameter.
     * @param mixed               $adjustedParameter   The adjusted parameter.
     *
     * @return bool True if parameter matches, false otherwise.
     */
    private static function actionMethodParameterMatchesParameter(ReflectionParameter $reflectionParameter, mixed $parameter, mixed &$adjustedParameter = null): bool
    {
        $adjustedParameter = $parameter;

        $reflectionParameterType = $reflectionParameter->getType();
        if (!$reflectionParameterType instanceof ReflectionNamedType) {
            // Union types will be handled correctly in next major version.
            return $reflectionParameterType === null;
        }

        switch ($reflectionParameterType->getName()) {
            case 'int':
                $intVal = intval($parameter);
                if (strval($intVal) !== $parameter) {
                    return false;
                }

                $adjustedParameter = $intVal;
                break;
            case 'float':
                if (!is_numeric($parameter)) {
                    return false;
                }

                $floatVal = floatval($parameter);
                if (is_infinite($floatVal) || is_nan($floatVal)) {
                    return false;
                }

                $adjustedParameter = $floatVal;
                break;
            case 'bool':
                if ($parameter === 'true') {
                    $adjustedParameter = true;
                } elseif ($parameter === 'false') {
                    $adjustedParameter = false;
                } else {
                    return false;
                }

                break;
            case 'string':
                $adjustedParameter = strval($parameter);
                break;
            default:
                return false;
        }

        return true;
    }

    /**
     * @var ApplicationInterface|null The application.
     */
    private ?ApplicationInterface $application = null;

    /**
     * @var RequestInterface|null The request.
     */
    private ?RequestInterface $request = null;

    /**
     * @var ResponseInterface|null The response.
     */
    private ?ResponseInterface $response = null;

    /**
     * @var ReflectionMethod|null The action method.
     */
    private ?ReflectionMethod $actionMethod = null;
}
