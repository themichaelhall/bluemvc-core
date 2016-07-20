<?php

namespace BlueMvc\Core\Base;

use BlueMvc\Core\Interfaces\ApplicationInterface;
use BlueMvc\Core\Interfaces\RouteInterface;

/**
 * Abstract class representing a BlueMvc main application.
 */
abstract class AbstractApplication implements ApplicationInterface
{
    /**
     * Constructs the application.
     *
     * @param string $documentRoot The document root.
     */
    public function __construct($documentRoot)
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
     * @return string The document root.
     */
    public function getDocumentRoot()
    {
        return $this->myDocumentRoot;
    }

    /**
     * @var string My document root.
     */
    private $myDocumentRoot;

    /**
     * @var RouteInterface[] My routes.
     */
    private $myRoutes;
}
