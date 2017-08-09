<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core;

use BlueMvc\Core\Base\AbstractController;
use BlueMvc\Core\Exceptions\ViewFileNotFoundException;
use BlueMvc\Core\Interfaces\ActionResults\ActionResultInterface;
use BlueMvc\Core\Interfaces\ApplicationInterface;
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

        $actionName = $action !== '' ? $action : 'index'; // fixme: validate and normalize action name

        // Try to invoke the action, and if that failed, try to invoke the default action.
        if (!$this->tryInvokeActionMethod($actionName, [], $result)) {
            $actionName = 'default';

            if (!$this->tryInvokeActionMethod($actionName, [$action], $result)) {
                return false;
            }
        }

        // Handle result.
        if ($result instanceof ViewInterface) {
            $result->updateResponse($application, $request, $response, $this, $actionName, $this->getViewItems());

            return true;
        }

        if ($result instanceof ActionResultInterface) {
            $result->updateResponse($application, $request, $response);

            return true;
        }

        $response->setContent($result);

        return true;
    }
}
