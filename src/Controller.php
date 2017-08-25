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
     *
     * @return bool True if request was actually processed, false otherwise.
     */
    public function processRequest(ApplicationInterface $application, RequestInterface $request, ResponseInterface $response, $action, array $parameters = [])
    {
        if (!is_string($action)) {
            throw new \InvalidArgumentException('$action parameter is not a string.');
        }

        parent::processRequest($application, $request, $response, $action, $parameters);

        $isIndex = $action === '';
        $actionName = $isIndex ? 'index' : $action;

        // Try to invoke the action, and if that failed, try to invoke the default action.
        if (!$this->tryInvokeActionMethod($actionName, $parameters, !$isIndex, $result)) {
            $actionName = 'default';

            if (!$this->tryInvokeActionMethod($actionName, [$action], false, $result)) { // fixme: parameters + test
                return false;
            }
        }

        // Handle result.
        if ($result instanceof ViewInterface) {
            $result->updateResponse($application, $request, $response, $this, $actionName, $this->myViewItems);

            return true;
        }

        if ($result instanceof ActionResultInterface) {
            $result->updateResponse($application, $request, $response);

            return true;
        }

        if (is_bool($result)) {
            $response->setContent($result ? 'true' : 'false');

            return true;
        }

        if (is_scalar($result) || (is_object($result) && method_exists($result, '__toString'))) {
            $response->setContent((string) $result);

            return true;
        }

        if ($result !== null) {
            $response->setContent(gettype($result));

            return true;
        }

        return true;
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
     * @var ViewItemCollectionInterface My view items.
     */
    private $myViewItems;
}
