<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\Base;

use BlueMvc\Core\Collections\ViewItemCollection;
use BlueMvc\Core\Interfaces\ApplicationInterface;
use BlueMvc\Core\Interfaces\Collections\ViewItemCollectionInterface;
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
     * Constructs the controller.
     *
     * @since 1.0.0
     */
    protected function __construct()
    {
        $this->myViewItems = new ViewItemCollection();
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
            if (strlen($action) > 0 && ctype_digit($action[0])) {
                $action = '_' . $action;
            }

            $actionMethod = $reflectionClass->getMethod($action . 'Action');
        } catch (\ReflectionException $e) {
            return false;
        }

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
     * @var ViewItemCollectionInterface My view items.
     */
    private $myViewItems;
}
