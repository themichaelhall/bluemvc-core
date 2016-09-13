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
     * Check if a route matches a request.
     *
     * @since 1.0.0
     *
     * @param RequestInterface $request The request.
     *
     * @return RouteMatchInterface|null The route match if rout matches request, false otherwise.
     */
    public function matches(RequestInterface $request);
}
