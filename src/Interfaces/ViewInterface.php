<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\Interfaces;

use BlueMvc\Core\Interfaces\Collections\ViewItemCollectionInterface;

/**
 * Interface for View class.
 *
 * @since 1.0.0
 */
interface ViewInterface
{
    /**
     * Updates the response.
     *
     * @since 1.0.0
     *
     * @param ApplicationInterface        $application The application.
     * @param RequestInterface            $request     The request.
     * @param ResponseInterface           $response    The response.
     * @param ControllerInterface         $controller  The controller.
     * @param string                      $action      The action.
     * @param ViewItemCollectionInterface $viewItems   The view items.
     */
    public function updateResponse(ApplicationInterface $application, RequestInterface $request, ResponseInterface $response, ControllerInterface $controller, $action, ViewItemCollectionInterface $viewItems);
}
