<?php

namespace BlueMvc\Core\Interfaces;

/**
 * Interface for Controller class.
 */
interface ControllerInterface
{
    /**
     * Processes a request.
     *
     * @param ApplicationInterface $application The application.
     * @param RequestInterface     $request     The request.
     * @param ResponseInterface    $response    The response.
     * @param RouteMatchInterface  $routeMatch  The route match.
     *
     * @return bool True if request was actually processed, false otherwise.
     */
    public function processRequest(ApplicationInterface $application, RequestInterface $request, ResponseInterface $response, RouteMatchInterface $routeMatch);
}
