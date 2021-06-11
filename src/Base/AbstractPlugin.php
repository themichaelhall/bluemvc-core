<?php

/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

declare(strict_types=1);

namespace BlueMvc\Core\Base;

use BlueMvc\Core\Interfaces\ApplicationInterface;
use BlueMvc\Core\Interfaces\PluginInterface;
use BlueMvc\Core\Interfaces\RequestInterface;
use BlueMvc\Core\Interfaces\ResponseInterface;

/**
 * Abstract class representing a plugin.
 *
 * @since 1.0.0
 */
abstract class AbstractPlugin implements PluginInterface
{
    /**
     * Called before a request is processed.
     *
     * @since 1.0.0
     *
     * @param ApplicationInterface $application The application.
     * @param RequestInterface     $request     The request.
     * @param ResponseInterface    $response    The response.
     *
     * @return bool True if request should stop processing, false otherwise.
     */
    public function onPreRequest(ApplicationInterface $application, RequestInterface $request, ResponseInterface $response): bool
    {
        return false;
    }

    /**
     * Called after a request is processed.
     *
     * @since 1.0.0
     *
     * @param ApplicationInterface $application The application.
     * @param RequestInterface     $request     The request.
     * @param ResponseInterface    $response    The response.
     *
     * @return bool True if request should stop processing, false otherwise.
     */
    public function onPostRequest(ApplicationInterface $application, RequestInterface $request, ResponseInterface $response): bool
    {
        return false;
    }
}
