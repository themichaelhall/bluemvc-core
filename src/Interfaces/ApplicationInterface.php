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
     * Adds a view renderer to the application.
     *
     * @version 1.0.0
     *
     * @param ViewRendererInterface $viewRenderer The view renderer.
     */
    public function addViewRenderer(ViewRendererInterface $viewRenderer);

    /**
     * @return FilePathInterface|null The document root.
     */
    public function getDocumentRoot();

    /**
     * @version 1.0.0
     *
     * @return ViewRendererInterface[] The view renderers.
     */
    public function getViewRenderers();

    /**
     * Runs a request in the application.
     *
     * @param RequestInterface  $request  The request.
     * @param ResponseInterface $response The response.
     */
    public function run(RequestInterface $request, ResponseInterface $response);
}
