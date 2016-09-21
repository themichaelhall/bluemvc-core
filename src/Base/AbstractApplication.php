<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */
namespace BlueMvc\Core\Base;

use BlueMvc\Core\Exceptions\InvalidFilePathException;
use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Interfaces\ApplicationInterface;
use BlueMvc\Core\Interfaces\RequestInterface;
use BlueMvc\Core\Interfaces\ResponseInterface;
use BlueMvc\Core\Interfaces\RouteInterface;
use BlueMvc\Core\Interfaces\ViewRendererInterface;
use DataTypes\Exceptions\FilePathLogicException;
use DataTypes\Interfaces\FilePathInterface;

/**
 * Abstract class representing a BlueMvc main application.
 *
 * @since 1.0.0
 */
abstract class AbstractApplication implements ApplicationInterface
{
    /**
     * Constructs the application.
     *
     * @since 1.0.0
     *
     * @param FilePathInterface $documentRoot The document root.
     */
    public function __construct(FilePathInterface $documentRoot)
    {
        $this->setDocumentRoot($documentRoot);
        $this->myRoutes = [];
        $this->myViewRenderers = [];
        $this->myViewPath = null;
    }

    /**
     * Returns the document root or null if no document root is set.
     *
     * @since 1.0.0
     *
     * @return FilePathInterface|null The document root or null if no document root is set.
     */
    public function getDocumentRoot()
    {
        return $this->myDocumentRoot;
    }

    /**
     * Returns the view renderers.
     *
     * @since 1.0.0
     *
     * @return ViewRendererInterface[] The view renderers.
     */
    public function getViewRenderers()
    {
        return $this->myViewRenderers;
    }

    /**
     * Returns The view files path.
     *
     * @since 1.0.0
     *
     * @return FilePathInterface The view files path.
     */
    public function getViewPath()
    {
        if ($this->myViewPath === null) {
            return $this->myDocumentRoot;
        }

        return $this->myViewPath;
    }

    /**
     * Runs a request.
     *
     * @since 1.0.0
     *
     * @param RequestInterface  $request  The request.
     * @param ResponseInterface $response The response.
     */
    public function run(RequestInterface $request, ResponseInterface $response)
    {
        $requestIsProcessed = false;

        foreach ($this->myRoutes as $route) {
            $routeMatch = $route->matches($request);

            if ($routeMatch !== null) {
                $controller = $routeMatch->getController();
                $requestIsProcessed = $controller->processRequest($this, $request, $response, $routeMatch);

                break;
            }
        }

        if (!$requestIsProcessed) {
            $response->setStatusCode(new StatusCode(StatusCode::NOT_FOUND));
        }

        $response->output();
    }

    /**
     * Adds a route.
     *
     * @since 1.0.0
     *
     * @param RouteInterface $route The route.
     */
    protected function addRoute(RouteInterface $route)
    {
        $this->myRoutes[] = $route;
    }

    /**
     * Adds a view renderer.
     *
     * @since 1.0.0
     *
     * @param ViewRendererInterface $viewRenderer The view renderer.
     */
    protected function addViewRenderer(ViewRendererInterface $viewRenderer)
    {
        $this->myViewRenderers[] = $viewRenderer;
    }

    /**
     * Returns the routes.
     *
     * @since 1.0.0
     *
     * @return RouteInterface[] The routes.
     */
    protected function getRoutes()
    {
        return $this->myRoutes;
    }

    /**
     * Sets the document root.
     *
     * @since 1.0.0
     *
     * @param FilePathInterface $documentRoot The document root.
     *
     * @throws InvalidFilePathException If the $documentRoot parameter is invalid.
     */
    protected function setDocumentRoot(FilePathInterface $documentRoot)
    {
        if (!$documentRoot->isDirectory()) {
            throw new InvalidFilePathException('Document root "' . $documentRoot . '" is not a directory.');
        }

        if (!$documentRoot->isAbsolute()) {
            throw new InvalidFilePathException('Document root "' . $documentRoot . '" is not an absolute path.');
        }

        $this->myDocumentRoot = $documentRoot;
    }

    /**
     * Sets the view files path.
     *
     * @since 1.0.0
     *
     * @param FilePathInterface $viewPath The view files path.
     *
     * @throws InvalidFilePathException If the $viewPath parameter is invalid.
     */
    protected function setViewPath(FilePathInterface $viewPath)
    {
        if (!$viewPath->isDirectory()) {
            throw new InvalidFilePathException('View path "' . $viewPath . '" is not a directory.');
        }

        try {
            $this->myViewPath = $this->myDocumentRoot->withFilePath($viewPath);
        } catch (FilePathLogicException $e) {
            throw new InvalidFilePathException($e->getMessage());
        }
    }

    /**
     * @var FilePathInterface|null My document root.
     */
    private $myDocumentRoot;

    /**
     * @var RouteInterface[] My routes.
     */
    private $myRoutes;

    /**
     * @var FilePathInterface My view files path.
     */
    private $myViewPath;

    /**
     * @var ViewRendererInterface[] My view renderers.
     */
    private $myViewRenderers;
}
