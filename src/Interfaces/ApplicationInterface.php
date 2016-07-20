<?php

namespace BlueMvc\Core\Interfaces;

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
     * @return string The document root.
     */
    public function getDocumentRoot();
}
