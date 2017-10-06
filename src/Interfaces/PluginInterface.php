<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\Interfaces;

/**
 * Interface for plugins.
 *
 * @since 1.0.0
 */
interface PluginInterface
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
    public function onPreRequest(ApplicationInterface $application, RequestInterface $request, ResponseInterface $response);

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
    public function onPostRequest(ApplicationInterface $application, RequestInterface $request, ResponseInterface $response);
}
