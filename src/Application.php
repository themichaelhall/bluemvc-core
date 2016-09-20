<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */
namespace BlueMvc\Core;

use BlueMvc\Core\Base\AbstractApplication;
use BlueMvc\Core\Interfaces\RouteInterface;
use BlueMvc\Core\Interfaces\ViewRendererInterface;
use DataTypes\FilePath;
use DataTypes\Interfaces\FilePathInterface;

/**
 * Class representing a BlueMvc main application.
 *
 * @since 1.0.0
 */
class Application extends AbstractApplication
{
    /**
     * Constructs the application.
     *
     * @since 1.0.0
     *
     * @param array|null $serverVars The server array or null to use the global $_SERVER array.
     */
    public function __construct(array $serverVars = null)
    {
        $this->myServerVars = $serverVars !== null ? $serverVars : $_SERVER;

        parent::__construct(FilePath::parse($this->myServerVars['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR));
    }

    /**
     * Adds a route.
     *
     * @since 1.0.0
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
     * @since 1.0.0
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
     * @since 1.0.0
     *
     * @return RouteInterface[] The routes.
     */
    public function getRoutes()
    {
        return parent::getRoutes();
    }

    /**
     * Sets the view files path.
     *
     * @since 1.0.0
     *
     * @param FilePathInterface $viewPath The view files path.
     */
    public function setViewPath(FilePathInterface $viewPath)
    {
        parent::setViewPath($viewPath);
    }

    /**
     * @var array My server array.
     */
    private $myServerVars;
}
