<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */
declare(strict_types=1);

namespace BlueMvc\Core;

use BlueMvc\Core\Collections\ViewItemCollection;
use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Interfaces\ActionResults\ActionResultInterface;
use BlueMvc\Core\Interfaces\ApplicationInterface;
use BlueMvc\Core\Interfaces\Collections\ViewItemCollectionInterface;
use BlueMvc\Core\Interfaces\ControllerInterface;
use BlueMvc\Core\Interfaces\RequestInterface;
use BlueMvc\Core\Interfaces\ResponseInterface;
use BlueMvc\Core\Interfaces\ViewInterface;
use BlueMvc\Core\Traits\ControllerTrait;
use ReflectionClass;

/**
 * Class representing a standard controller.
 *
 * @since 1.0.0
 */
abstract class Controller implements ControllerInterface
{
    use ControllerTrait;

    /**
     * Constructs the controller.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->viewItems = new ViewItemCollection();
    }

    /**
     * Returns a view item value by view item name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The view item name.
     *
     * @return mixed|null The view item value by view item name if it exists, null otherwise.
     */
    public function getViewItem(string $name)
    {
        return $this->viewItems->get($name);
    }

    /**
     * Returns the view items.
     *
     * @since 1.0.0
     *
     * @return ViewItemCollectionInterface The view items.
     */
    public function getViewItems(): ViewItemCollectionInterface
    {
        return $this->viewItems;
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
        $this->init($application, $request, $response);

        $isIndex = $action === '';
        $actionName = self::getActionName($action);

        $resultHandler = function ($result) use (&$actionName) {
            $this->handleResult($result, $actionName);
        };

        // Try to invoke the action, and if that failed, try to invoke the default action.
        if (!$this->tryInvokeActionMethod($actionName, $parameters, !$isIndex, $resultHandler, $hasFoundActionMethod)) {
            if ($hasFoundActionMethod) {
                // If action method was found, but something else failed (e.g. parameter mismatch),
                // do not try to invoke default method.
                $response->setStatusCode(new StatusCode(StatusCode::NOT_FOUND));

                return;
            }

            $actionName = 'default';

            if (!$this->tryInvokeActionMethod($actionName, array_merge([$action], $parameters), false, $resultHandler)) {
                $response->setStatusCode(new StatusCode(StatusCode::NOT_FOUND));

                return;
            }
        }
    }

    /**
     * Sets a view item.
     *
     * @since 1.0.0
     *
     * @param string $name  The view item name.
     * @param mixed  $value The view item value.
     */
    public function setViewItem(string $name, $value): void
    {
        $this->viewItems->set($name, $value);
    }

    /**
     * Sets the view items.
     *
     * @since 1.0.0
     *
     * @param ViewItemCollectionInterface $viewItems The view items.
     */
    public function setViewItems(ViewItemCollectionInterface $viewItems): void
    {
        $this->viewItems = $viewItems;
    }

    /**
     * Returns the path to the view files.
     *
     * @since 1.0.0
     *
     * @return string The path to the view files.
     */
    protected function getViewPath(): string
    {
        $result = (new ReflectionClass($this))->getShortName();
        if (strlen($result) > 10 && substr(strtolower($result), -10) === 'controller') {
            $result = substr($result, 0, -10);
        }

        return $result;
    }

    /**
     * Handles the result.
     *
     * @param mixed  $result     The result.
     * @param string $actionName The action name.
     */
    private function handleResult($result, string $actionName): void
    {
        $application = $this->getApplication();
        $request = $this->getRequest();
        $response = $this->getResponse();
        $viewPath = $this->getViewPath();

        if ($result instanceof ViewInterface) {
            $result->updateResponse($application, $request, $response, $viewPath, $actionName, $this->viewItems);

            return;
        }

        if ($result instanceof ActionResultInterface) {
            $result->updateResponse($application, $request, $response);

            return;
        }

        if (is_bool($result)) {
            $response->setContent($result ? 'true' : 'false');

            return;
        }

        if (is_scalar($result) || (is_object($result) && method_exists($result, '__toString'))) {
            $response->setContent((string) $result);

            return;
        }

        if ($result !== null) {
            $response->setContent(gettype($result));
        }
    }

    /**
     * Returns the action name for a specific action.
     *
     * @param string $action The action.
     *
     * @return string The action name.
     */
    private static function getActionName(string $action): string
    {
        if ($action === '') {
            return 'index';
        }

        if (in_array(strtolower($action), self::$magicActionMethods)) {
            return '_' . $action;
        }

        return $action;
    }

    /**
     * @var ViewItemCollectionInterface My view items.
     */
    private $viewItems;

    /**
     * @var array My magic action methods.
     */
    private static $magicActionMethods = ['index', 'default'];
}
