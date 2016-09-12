<?php

namespace BlueMvc\Core\Base;

use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Interfaces\ApplicationInterface;
use BlueMvc\Core\Interfaces\RequestInterface;
use BlueMvc\Core\Interfaces\ResponseInterface;
use BlueMvc\Core\Interfaces\RouteInterface;
use BlueMvc\Core\Interfaces\ViewRendererInterface;
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
        $this->setDocumentRoot($documentRoot);
        $this->myRoutes = [];
        $this->myViewRenderers = [];
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
     * Adds a view renderer to the application.
     *
     * @version 1.0.0
     *
     * @param ViewRendererInterface $viewRenderer The view renderer.
     */
    public function addViewRenderer(ViewRendererInterface $viewRenderer)
    {
        $this->myViewRenderers[] = $viewRenderer;
    }

    /**
     * @return FilePathInterface|null The document root.
     */
    public function getDocumentRoot()
    {
        return $this->myDocumentRoot;
    }

    /**
     * @version 1.0.0
     *
     * @return ViewRendererInterface[] The view renderers.
     */
    public function getViewRenderers()
    {
        return $this->myViewRenderers;
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
     * Sets the document root.
     *
     * @param FilePathInterface $documentRoot The document root.
     */
    protected function setDocumentRoot(FilePathInterface $documentRoot)
    {
        $this->myDocumentRoot = $documentRoot;
    }

    /**
     * @var FilePathInterface|null My document root.
     */
    private $myDocumentRoot;

    /**
     * @var RouteInterface[] My routes.
     */
    private $myRoutes;

    /**
     * @var ViewRendererInterface[] My view renderers.
     */
    private $myViewRenderers;
}
