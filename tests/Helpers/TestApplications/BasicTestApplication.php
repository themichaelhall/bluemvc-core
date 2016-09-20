<?php

use BlueMvc\Core\Base\AbstractApplication;
use BlueMvc\Core\Interfaces\RouteInterface;
use BlueMvc\Core\Interfaces\ViewRendererInterface;
use DataTypes\Interfaces\FilePathInterface;

/**
 * A basic test application.
 */
class BasicTestApplication extends AbstractApplication
{
    /**
     * Constructs the application.
     *
     * @param FilePathInterface $documentRoot The document root.
     */
    public function __construct(FilePathInterface $documentRoot)
    {
        parent::__construct($documentRoot);
    }

    /**
     * Adds a route.
     *
     * @since 1.0.0
     *
     * @param RouteInterface $route The route.
     */
    public function addRoute(RouteInterface $route)
    {
        parent::addRoute($route);
    }

    /**
     * Adds a view renderer.
     *
     * @since 1.0.0
     *
     * @param ViewRendererInterface $viewRenderer The view renderer.
     */
    public function addViewRenderer(ViewRendererInterface $viewRenderer)
    {
        parent::addViewRenderer($viewRenderer);
    }

    /**
     * Returns the routes.
     *
     * @since 1.0.0
     *
     * @return RouteInterface[] The routes.
     */
    public function getRoutes()
    {
        return parent::getRoutes();
    }

    /**
     * Sets the document root.
     *
     * @since 1.0.0
     *
     * @param FilePathInterface $documentRoot The document root.
     */
    public function setDocumentRoot(FilePathInterface $documentRoot)
    {
        parent::setDocumentRoot($documentRoot);
    }

    /**
     * Sets the view files path.
     *
     * @since 1.0.0
     *
     * @param FilePathInterface $viewPath The view files path.
     */
    public function setViewPath(FilePathInterface $viewPath)
    {
        parent::setViewPath($viewPath);
    }
}
