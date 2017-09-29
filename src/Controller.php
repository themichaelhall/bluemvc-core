<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core;

use BlueMvc\Core\Base\AbstractController;
use BlueMvc\Core\Collections\ViewItemCollection;
use BlueMvc\Core\Exceptions\ViewFileNotFoundException;
use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Interfaces\ActionResults\ActionResultInterface;
use BlueMvc\Core\Interfaces\ApplicationInterface;
use BlueMvc\Core\Interfaces\Collections\ViewItemCollectionInterface;
use BlueMvc\Core\Interfaces\RequestInterface;
use BlueMvc\Core\Interfaces\ResponseInterface;
use BlueMvc\Core\Interfaces\ViewInterface;

/**
 * Class representing a standard controller.
 *
 * @since 1.0.0
 */
abstract class Controller extends AbstractController
{
    /**
     * Constructs the controller.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();

        $this->myViewItems = new ViewItemCollection();
    }

    /**
     * Returns a view item value by view item name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The view item name.
     *
     * @throws \InvalidArgumentException If the $name parameter is not a string.
     *
     * @return mixed|null The view item value by view item name if it exists, null otherwise.
     */
    public function getViewItem($name)
    {
        return $this->myViewItems->get($name);
    }

    /**
     * Returns the view items.
     *
     * @since 1.0.0
     *
     * @return ViewItemCollectionInterface The view items.
     */
    public function getViewItems()
    {
        return $this->myViewItems;
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
     * @throws \InvalidArgumentException If the $action parameter is not a string.
     * @throws ViewFileNotFoundException If no suitable view file was found.
     */
    public function processRequest(ApplicationInterface $application, RequestInterface $request, ResponseInterface $response, $action, array $parameters = [])
    {
        if (!is_string($action)) {
            throw new \InvalidArgumentException('$action parameter is not a string.');
        }

        parent::processRequest($application, $request, $response, $action, $parameters);

        $isIndex = $action === '';
        $actionName = self::myGetActionName($action);

        // Try to invoke the action, and if that failed, try to invoke the default action.
        if (!$this->tryInvokeActionMethod($actionName, $parameters, !$isIndex, $result, $hasFoundActionMethod)) {
            if ($hasFoundActionMethod) {
                // If action method was found, but something else failed (e.g. parameter mismatch),
                // do not try to invoke default method.
                $response->setStatusCode(new StatusCode(StatusCode::NOT_FOUND));

                return;
            }

            $actionName = 'default';

            if (!$this->tryInvokeActionMethod($actionName, array_merge([$action], $parameters), false, $result)) {
                $response->setStatusCode(new StatusCode(StatusCode::NOT_FOUND));

                return;
            }
        }

        // Handle result.
        if ($result instanceof ViewInterface) {
            $result->updateResponse($application, $request, $response, $this, $actionName, $this->myViewItems);

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
     * Sets a view item.
     *
     * @since 1.0.0
     *
     * @param string $name  The view item name.
     * @param mixed  $value The view item value.
     *
     * @throws \InvalidArgumentException If the $name parameter is not a string.
     */
    public function setViewItem($name, $value)
    {
        $this->myViewItems->set($name, $value);
    }

    /**
     * Sets the view items.
     *
     * @since 1.0.0
     *
     * @param ViewItemCollectionInterface $viewItems The view items.
     */
    public function setViewItems(ViewItemCollectionInterface $viewItems)
    {
        $this->myViewItems = $viewItems;
    }

    /**
     * Returns the action name for a specific action.
     *
     * @param string $action The action.
     *
     * @return string The action name.
     */
    private static function myGetActionName($action)
    {
        if ($action === '') {
            return 'index';
        }

        if (in_array(strtolower($action), self::$myMagicActionMethods)) {
            return '_' . $action;
        }

        return $action;
    }

    /**
     * @var ViewItemCollectionInterface My view items.
     */
    private $myViewItems;

    /**
     * @var array My magic action methods.
     */
    private static $myMagicActionMethods = ['index', 'default'];
}
