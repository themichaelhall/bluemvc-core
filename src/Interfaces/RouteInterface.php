<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\Interfaces;

/**
 * Interface for Route class.
 *
 * @since 1.0.0
 */
interface RouteInterface
{
    /**
     * Returns the controller class name.
     *
     * @since 1.0.0
     *
     * @return string The controller class name.
     */
    public function getControllerClassName();

    /**
     * Check if a route matches a request.
     *
     * @since 1.0.0
     *
     * @param RequestInterface $request The request.
     *
     * @return RouteMatchInterface|null The route match if route matches request, null otherwise.
     */
    public function matches(RequestInterface $request);
}
