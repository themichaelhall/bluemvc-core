<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */
declare(strict_types=1);

namespace BlueMvc\Core\Interfaces;

use BlueMvc\Core\Exceptions\InvalidFilePathException;
use BlueMvc\Core\Interfaces\Collections\CustomItemCollectionInterface;
use BlueMvc\Core\Interfaces\Collections\SessionItemCollectionInterface;
use DataTypes\Interfaces\FilePathInterface;

/**
 * Interface for Application class.
 *
 * @since 1.0.0
 */
interface ApplicationInterface
{
    /**
     * Adds a plugin.
     *
     * @since 1.0.0
     *
     * @param PluginInterface $plugin The plugin.
     */
    public function addPlugin(PluginInterface $plugin): void;

    /**
     * Adds a route.
     *
     * @since 1.0.0
     *
     * @param RouteInterface $route The route.
     */
    public function addRoute(RouteInterface $route): void;

    /**
     * Adds a view renderer.
     *
     * @since 1.0.0
     *
     * @param ViewRendererInterface $viewRenderer The view renderer.
     */
    public function addViewRenderer(ViewRendererInterface $viewRenderer): void;

    /**
     * Returns a custom item by name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The custom item name.
     *
     * @return mixed|null The custom item if it exists, null otherwise.
     */
    public function getCustomItem(string $name);

    /**
     * Returns the custom items.
     *
     * @since 1.0.0
     *
     * @return CustomItemCollectionInterface The custom items.
     */
    public function getCustomItems(): CustomItemCollectionInterface;

    /**
     * Returns the document root.
     *
     * @since 1.0.0
     *
     * @return FilePathInterface The document root.
     */
    public function getDocumentRoot(): FilePathInterface;

    /**
     * Returns the error controller class name or null if not specified.
     *
     * @since 1.0.0
     *
     * @return string|null The error controller class name or null if not specified.
     */
    public function getErrorControllerClass(): ?string;

    /**
     * Returns the plugins.
     *
     * @since 1.0.0
     *
     * @return PluginInterface[] The plugins.
     */
    public function getPlugins(): array;

    /**
     * Returns the routes.
     *
     * @since 1.0.0
     *
     * @return RouteInterface[] The routes.
     */
    public function getRoutes(): array;

    /**
     * Returns a session item by name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The session item name.
     *
     * @return mixed|null The session item if it exists, null otherwise.
     */
    public function getSessionItem(string $name);

    /**
     * Returns the session items.
     *
     * @since 1.0.0
     *
     * @return SessionItemCollectionInterface The session items.
     */
    public function getSessionItems(): SessionItemCollectionInterface;

    /**
     * Returns the path to the application-specific temporary directory.
     *
     * @since 1.0.0
     *
     * @return FilePathInterface The path to the application-specific temporary directory.
     */
    public function getTempPath(): FilePathInterface;

    /**
     * Returns The view files path.
     *
     * @since 1.0.0
     *
     * @return FilePathInterface The view files path.
     */
    public function getViewPath(): FilePathInterface;

    /**
     * Returns the view renderers.
     *
     * @since 1.0.0
     *
     * @return ViewRendererInterface[] The view renderers.
     */
    public function getViewRenderers(): array;

    /**
     * Returns true if in debug mode, false otherwise.
     *
     * @since 1.0.0
     *
     * @return bool True if in debug mode, false otherwise.
     */
    public function isDebug(): bool;

    /**
     * Removes a session item by name.
     *
     * @since 1.0.0
     *
     * @param string $name The session item name.
     */
    public function removeSessionItem(string $name): void;

    /**
     * Runs a request.
     *
     * @since 1.0.0
     *
     * @param RequestInterface  $request  The request.
     * @param ResponseInterface $response The response.
     */
    public function run(RequestInterface $request, ResponseInterface $response): void;

    /**
     * Sets a custom item.
     *
     * @since 1.0.0
     *
     * @param string $name  The custom item name.
     * @param mixed  $value The custom item value.
     */
    public function setCustomItem(string $name, $value): void;

    /**
     * Sets the custom items.
     *
     * @since 1.0.0
     *
     * @param CustomItemCollectionInterface $customItems The custom items.
     */
    public function setCustomItems(CustomItemCollectionInterface $customItems): void;

    /**
     * Sets the error controller class name.
     *
     * @since 1.0.0
     *
     * @param string $errorControllerClass The error controller class name.
     */
    public function setErrorControllerClass(string $errorControllerClass): void;

    /**
     * Sets a session item.
     *
     * @since 1.0.0
     *
     * @param string $name  The session item name.
     * @param mixed  $value The session item value.
     */
    public function setSessionItem(string $name, $value): void;

    /**
     * Sets the path to the application-specific temporary directory.
     *
     * @since 1.0.0
     *
     * @param FilePathInterface $tempPath The path to the application-specific temporary directory.
     *
     * @throws InvalidFilePathException If the $tempPath parameter is invalid.
     */
    public function setTempPath(FilePathInterface $tempPath): void;

    /**
     * Sets the view files path.
     *
     * @since 1.0.0
     *
     * @param FilePathInterface $viewPath The view files path.
     *
     * @throws InvalidFilePathException If the $viewPath parameter is invalid.
     */
    public function setViewPath(FilePathInterface $viewPath): void;
}
