<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */
namespace BlueMvc\Core;

use BlueMvc\Core\Base\AbstractController;
use BlueMvc\Core\Interfaces\ApplicationInterface;
use BlueMvc\Core\Interfaces\RequestInterface;
use BlueMvc\Core\Interfaces\ResponseInterface;
use BlueMvc\Core\Interfaces\RouteMatchInterface;

/**
 * Class representing a standard controller.
 *
 * @since 1.0.0
 */
abstract class Controller extends AbstractController
{
    /**
     * Processes a request.
     *
     * @since 1.0.0
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
        parent::processRequest($application, $request, $response, $routeMatch);

        $action = $routeMatch->getAction();
        if ($action === '') {
            $action = 'index';
        }

        if ($this->tryInvokeActionMethod($action, [], $result)) {
            $response->setContent($result);

            return true;
        }

        return false;
    }
}
