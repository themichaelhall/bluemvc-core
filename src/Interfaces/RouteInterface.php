<?php

namespace BlueMvc\Core\Interfaces;

/**
 * Interface for Route class.
 */
interface RouteInterface
{
    /**
     * Check if a route matches a request.
     *
     * @param RequestInterface $request The request.
     *
     * @return RouteMatchInterface|null The route match if rout matches request, false otherwise.
     */
    public function matches(RequestInterface $request);
}
