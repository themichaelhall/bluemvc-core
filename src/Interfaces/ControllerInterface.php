<?php

/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

declare(strict_types=1);

namespace BlueMvc\Core\Interfaces;

/**
 * Interface for Controller class.
 *
 * @since 1.0.0
 */
interface ControllerInterface
{
    /**
     * Processes a request.
     *
     * @since 1.0.0
     *
     * @param ApplicationInterface $application The application.
     * @param RequestInterface     $request     The request.
     * @param ResponseInterface    $response    The response.
     * @param string               $action      The action.
     * @param string[]             $parameters  The parameters.
     */
    public function processRequest(ApplicationInterface $application, RequestInterface $request, ResponseInterface $response, string $action, array $parameters = []): void;
}
