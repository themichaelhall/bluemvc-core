<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\Interfaces;

use BlueMvc\Core\Exceptions\InvalidFilePathException;
use DataTypes\Interfaces\FilePathInterface;

/**
 * Interface for Application class.
 *
 * @since 1.0.0
 */
interface ApplicationInterface
{
    /**
     * Adds a route.
     *
     * @since 1.0.0
     *
     * @param RouteInterface $route The route.
     */
    public function addRoute(RouteInterface $route);

    /**
     * Adds a view renderer.
     *
     * @since 1.0.0
     *
     * @param ViewRendererInterface $viewRenderer The view renderer.
     */
    public function addViewRenderer(ViewRendererInterface $viewRenderer);

    /**
     * Returns the document root.
     *
     * @since 1.0.0
     *
     * @return FilePathInterface The document root.
     */
    public function getDocumentRoot();

    /**
     * Returns the routes.
     *
     * @since 1.0.0
     *
     * @return RouteInterface[] The routes.
     */
    public function getRoutes();

    /**
     * Returns the path to the application-specific temporary directory.
     *
     * @since 1.0.0
     *
     * @return FilePathInterface The path to the application-specific temporary directory.
     */
    public function getTempPath();

    /**
     * Returns The view files path.
     *
     * @since 1.0.0
     *
     * @return FilePathInterface The view files path.
     */
    public function getViewPath();

    /**
     * Returns the view renderers.
     *
     * @since 1.0.0
     *
     * @return ViewRendererInterface[] The view renderers.
     */
    public function getViewRenderers();

    /**
     * Runs a request.
     *
     * @since 1.0.0
     *
     * @param RequestInterface  $request  The request.
     * @param ResponseInterface $response The response.
     */
    public function run(RequestInterface $request, ResponseInterface $response);

    /**
     * Sets the path to the application-specific temporary directory.
     *
     * @since 1.0.0
     *
     * @param FilePathInterface $tempPath The path to the application-specific temporary directory.
     *
     * @throws InvalidFilePathException If the $tempPath parameter is invalid.
     */
    public function setTempPath(FilePathInterface $tempPath);

    /**
     * Sets the view files path.
     *
     * @since 1.0.0
     *
     * @param FilePathInterface $viewPath The view files path.
     *
     * @throws InvalidFilePathException If the $viewPath parameter is invalid.
     */
    public function setViewPath(FilePathInterface $viewPath);
}
