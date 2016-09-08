<?php

namespace BlueMvc\Core\Interfaces;

use DataTypes\Interfaces\FilePathInterface;

/**
 * Interface for Application class.
 */
interface ApplicationInterface
{
    /**
     * Adds a route to the application.
     *
     * @param RouteInterface $route The route.
     */
    public function addRoute(RouteInterface $route);

    /**
     * @return FilePathInterface|null The document root.
     */
    public function getDocumentRoot();

    /**
     * Runs a request in the application.
     *
     * @param RequestInterface  $request  The request.
     * @param ResponseInterface $response The response.
     */
    public function run(RequestInterface $request, ResponseInterface $response);
}
