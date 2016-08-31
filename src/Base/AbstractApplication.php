<?php

namespace BlueMvc\Core\Base;

use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Interfaces\ApplicationInterface;
use BlueMvc\Core\Interfaces\RequestInterface;
use BlueMvc\Core\Interfaces\ResponseInterface;
use BlueMvc\Core\Interfaces\RouteInterface;
use DataTypes\Interfaces\FilePathInterface;

/**
 * Abstract class representing a BlueMvc main application.
 */
abstract class AbstractApplication implements ApplicationInterface
{
    /**
     * Constructs the application.
     *
     * @param FilePathInterface $documentRoot The document root.
     */
    public function __construct(FilePathInterface $documentRoot)
    {
        $this->myDocumentRoot = $documentRoot;
        $this->myRoutes = [];
    }

    /**
     * Adds a route to the application.
     *
     * @param RouteInterface $route The route.
     */
    public function addRoute(RouteInterface $route)
    {
        $this->myRoutes[] = $route;
    }

    /**
     * @return FilePathInterface The document root.
     */
    public function getDocumentRoot()
    {
        return $this->myDocumentRoot;
    }

    /**
     * Runs a request in the application.
     *
     * @param RequestInterface  $request  The request.
     * @param ResponseInterface $response The response.
     */
    public function run(RequestInterface $request, ResponseInterface $response)
    {
        $requestIsProcessed = false;

        foreach ($this->myRoutes as $route) {
            $routeMatch = $route->matches($request);

            if ($routeMatch !== null) {
                $controller = $routeMatch->getController();
                $requestIsProcessed = $controller->processRequest($this, $request, $response, $routeMatch);

                break;
            }
        }

        if (!$requestIsProcessed) {
            $response->setStatusCode(new StatusCode(StatusCode::NOT_FOUND));
        }

        $response->output();
    }

    /**
     * @var FilePathInterface My document root.
     */
    private $myDocumentRoot;

    /**
     * @var RouteInterface[] My routes.
     */
    private $myRoutes;
}
