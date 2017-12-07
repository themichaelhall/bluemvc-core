<?php

namespace BlueMvc\Core\Tests\Helpers\TestApplications;

use BlueMvc\Core\Base\AbstractApplication;
use BlueMvc\Core\Interfaces\Collections\SessionItemCollectionInterface;
use BlueMvc\Core\Interfaces\RouteInterface;
use BlueMvc\Core\Interfaces\ViewRendererInterface;
use BlueMvc\Core\Tests\Helpers\TestCollections\BasicTestSessionItemCollection;
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
        parent::__construct($documentRoot, new BasicTestSessionItemCollection());
    }

    /**
     * Adds a route.
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
     * @param ViewRendererInterface $viewRenderer The view renderer.
     */
    public function addViewRenderer(ViewRendererInterface $viewRenderer)
    {
        parent::addViewRenderer($viewRenderer);
    }

    /**
     * Returns the routes.
     *
     * @return RouteInterface[] The routes.
     */
    public function getRoutes()
    {
        return parent::getRoutes();
    }

    /**
     * Sets the debug mode.
     *
     * @param bool $isDebug The debug mode.
     */
    public function setDebug($isDebug)
    {
        parent::setDebug($isDebug);
    }

    /**
     * Sets the document root.
     *
     * @param FilePathInterface $documentRoot The document root.
     */
    public function setDocumentRoot(FilePathInterface $documentRoot)
    {
        parent::setDocumentRoot($documentRoot);
    }

    /**
     * Sets the session items.
     *
     * @param SessionItemCollectionInterface $sessionItems The session items.
     */
    public function setSessionItems(SessionItemCollectionInterface $sessionItems)
    {
        parent::setSessionItems($sessionItems);
    }

    /**
     * Sets the path to the application-specific temporary directory.
     *
     * @param FilePathInterface $tempPath The path.
     */
    public function setTempPath(FilePathInterface $tempPath)
    {
        parent::setTempPath($tempPath);
    }

    /**
     * Sets the view files path.
     *
     * @param FilePathInterface $viewPath The view files path.
     */
    public function setViewPath(FilePathInterface $viewPath)
    {
        parent::setViewPath($viewPath);
    }
}
