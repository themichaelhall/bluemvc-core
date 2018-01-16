<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\Base;

use BlueMvc\Core\Collections\CustomItemCollection;
use BlueMvc\Core\Collections\HeaderCollection;
use BlueMvc\Core\Exceptions\InvalidControllerClassException;
use BlueMvc\Core\Exceptions\InvalidFilePathException;
use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Interfaces\ApplicationInterface;
use BlueMvc\Core\Interfaces\Collections\CustomItemCollectionInterface;
use BlueMvc\Core\Interfaces\Collections\SessionItemCollectionInterface;
use BlueMvc\Core\Interfaces\ControllerInterface;
use BlueMvc\Core\Interfaces\ErrorControllerInterface;
use BlueMvc\Core\Interfaces\PluginInterface;
use BlueMvc\Core\Interfaces\RequestInterface;
use BlueMvc\Core\Interfaces\ResponseInterface;
use BlueMvc\Core\Interfaces\RouteInterface;
use BlueMvc\Core\Interfaces\ViewRendererInterface;
use DataTypes\Exceptions\FilePathLogicException;
use DataTypes\FilePath;
use DataTypes\Interfaces\FilePathInterface;

/**
 * Abstract class representing a BlueMvc main application.
 *
 * @since 1.0.0
 */
abstract class AbstractApplication implements ApplicationInterface
{
    /**
     * Adds a plugin.
     *
     * @since 1.0.0
     *
     * @param PluginInterface $plugin The plugin.
     */
    public function addPlugin(PluginInterface $plugin)
    {
        $this->myPlugins[] = $plugin;
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
        $this->myRoutes[] = $route;
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
        $this->myViewRenderers[] = $viewRenderer;
    }

    /**
     * Returns the custom items.
     *
     * @since 1.0.0
     *
     * @return CustomItemCollectionInterface The custom items.
     */
    public function getCustomItems()
    {
        return $this->myCustomItems;
    }

    /**
     * Returns the document root.
     *
     * @since 1.0.0
     *
     * @return FilePathInterface The document root.
     */
    public function getDocumentRoot()
    {
        return $this->myDocumentRoot;
    }

    /**
     * Returns the error controller class name or null if not specified.
     *
     * @since 1.0.0
     *
     * @return string|null The error controller class name or null if not specified.
     */
    public function getErrorControllerClass()
    {
        return $this->myErrorControllerClass;
    }

    /**
     * Returns the plugins.
     *
     * @since 1.0.0
     *
     * @return PluginInterface[] The plugins.
     */
    public function getPlugins()
    {
        return $this->myPlugins;
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
        return $this->myRoutes;
    }

    /**
     * Returns a session item by name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The name.
     *
     * @throws \InvalidArgumentException If the $name parameter is not a string.
     *
     * @return mixed|null The session item if it exists, null otherwise.
     */
    public function getSessionItem($name)
    {
        return $this->mySessionItems->get($name);
    }

    /**
     * Returns the session items.
     *
     * @since 1.0.0
     *
     * @return SessionItemCollectionInterface The session items.
     */
    public function getSessionItems()
    {
        return $this->mySessionItems;
    }

    /**
     * Returns the path to the application-specific temporary directory.
     *
     * @since 1.0.0
     *
     * @return FilePathInterface The path to the application-specific temporary directory.
     */
    public function getTempPath()
    {
        if ($this->myTempPath === null) {
            // Generate a default temporary directory by document root.
            $this->myTempPath = FilePath::parse(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'bluemvc' . DIRECTORY_SEPARATOR . sha1($this->myDocumentRoot->__toString()) . DIRECTORY_SEPARATOR);
            self::myEnsureDirectoryExists($this->myTempPath);
        }

        return $this->myTempPath;
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
     * Returns true if in debug mode, false otherwise.
     *
     * @since 1.0.0
     *
     * @return bool True if in debug mode, false otherwise.
     */
    public function isDebug()
    {
        return $this->myIsDebug;
    }

    /**
     * Removes a session item by name.
     *
     * @since 1.0.0
     *
     * @param string $name The session item name.
     *
     * @throws \InvalidArgumentException If the $name parameter is not a string.
     */
    public function removeSessionItem($name)
    {
        $this->mySessionItems->remove($name);
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
        $this->myRun($request, $response);
        $response->output();
    }

    /**
     * Sets a custom item.
     *
     * @since 1.0.0
     *
     * @param string $name  The custom item name.
     * @param mixed  $value The custom item value.
     *
     * @throws \InvalidArgumentException If the $name parameter is not a string.
     */
    public function setCustomItem($name, $value)
    {
        $this->myCustomItems->set($name, $value);
    }

    /**
     * Sets the error controller class name.
     *
     * @since 1.0.0
     *
     * @param string $errorControllerClass The error controller class name.
     *
     * @throws \InvalidArgumentException       If the class name is not a string.
     * @throws InvalidControllerClassException If the class name is not a valid controller class.
     */
    public function setErrorControllerClass($errorControllerClass)
    {
        if (!is_string($errorControllerClass)) {
            throw new \InvalidArgumentException('$errorControllerClass parameter is not a string.');
        }

        if (!is_a($errorControllerClass, ErrorControllerInterface::class, true)) {
            throw new InvalidControllerClassException('"' . $errorControllerClass . '" is not a valid error controller class.');
        }

        $this->myErrorControllerClass = $errorControllerClass;
    }

    /**
     * Sets a session item.
     *
     * @since 1.0.0
     *
     * @param string $name  The session item name.
     * @param mixed  $value The session item value.
     *
     * @throws \InvalidArgumentException If the $name parameter is not a string.
     */
    public function setSessionItem($name, $value)
    {
        $this->mySessionItems->set($name, $value);
    }

    /**
     * Sets the path to the application-specific temporary directory.
     *
     * @since 1.0.0
     *
     * @param FilePathInterface $tempPath The path to the application-specific temporary directory.
     *
     * @throws InvalidFilePathException If the $tempPath parameter is invalid.
     */
    public function setTempPath(FilePathInterface $tempPath)
    {
        if (!$tempPath->isDirectory()) {
            throw new InvalidFilePathException('Temp path "' . $tempPath . '" is not a directory.');
        }

        try {
            $this->myTempPath = $this->myDocumentRoot->withFilePath($tempPath);
        } catch (FilePathLogicException $e) {
            throw new InvalidFilePathException($e->getMessage());
        }
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
    public function setViewPath(FilePathInterface $viewPath)
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
     * Constructs the application.
     *
     * @since 1.0.0
     *
     * @param FilePathInterface              $documentRoot The document root.
     * @param SessionItemCollectionInterface $sessionItems The session items.
     *
     * @throws InvalidFilePathException
     */
    protected function __construct(FilePathInterface $documentRoot, SessionItemCollectionInterface $sessionItems)
    {
        $this->setDocumentRoot($documentRoot);
        $this->setSessionItems($sessionItems);
        $this->myRoutes = [];
        $this->myTempPath = null;
        $this->myViewRenderers = [];
        $this->myViewPath = null;
        $this->myIsDebug = false;
        $this->myErrorControllerClass = null;
        $this->myPlugins = [];
        $this->myCustomItems = new CustomItemCollection();
    }

    /**
     * Sets the debug mode.
     *
     * @since 1.0.0
     *
     * @param bool $isDebug The debug mode.
     */
    protected function setDebug($isDebug)
    {
        $this->myIsDebug = $isDebug;
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
     * Sets the session items.
     *
     * @since 1.0.0
     *
     * @param SessionItemCollectionInterface $sessionItems The session items.
     */
    protected function setSessionItems(SessionItemCollectionInterface $sessionItems)
    {
        $this->mySessionItems = $sessionItems;
    }

    /**
     * Runs a request.
     *
     * @param RequestInterface  $request  The request.
     * @param ResponseInterface $response The response.
     */
    private function myRun(RequestInterface $request, ResponseInterface $response)
    {
        $exception = null;

        foreach ($this->myPlugins as $plugin) {
            if ($plugin->onPreRequest($this, $request, $response)) {
                return;
            }
        }

        try {
            $this->myHandleRequest($request, $response);
        } catch (\Exception $e) {
            $this->myExceptionToResponse($e, $response);
            $exception = $e;
        }

        if ($response->getStatusCode()->isError()) {
            $this->myHandleError($request, $response, $exception);
        }

        foreach ($this->myPlugins as $plugin) {
            if ($plugin->onPostRequest($this, $request, $response)) {
                return;
            }
        }
    }

    /**
     * Handles a request.
     *
     * @param RequestInterface  $request  The request.
     * @param ResponseInterface $response The response.
     */
    private function myHandleRequest(RequestInterface $request, ResponseInterface $response)
    {
        foreach ($this->myRoutes as $route) {
            $routeMatch = $route->matches($request);

            if ($routeMatch === null) {
                continue;
            }

            $controllerClass = $routeMatch->getControllerClassName();
            $controller = new $controllerClass();

            /** @var ControllerInterface $controller */
            $controller->processRequest($this, $request, $response, $routeMatch->getAction(), $routeMatch->getParameters());

            return;
        }

        $response->setStatusCode(new StatusCode(StatusCode::NOT_FOUND));
    }

    /**
     * Handles an error.
     *
     * @param RequestInterface  $request   The request.
     * @param ResponseInterface $response  The response.
     * @param \Exception|null   $exception The exception or null if no exception.
     */
    private function myHandleError(RequestInterface $request, ResponseInterface $response, \Exception $exception = null)
    {
        $errorControllerClass = $this->getErrorControllerClass();

        if ($errorControllerClass !== null) {
            /** @var ErrorControllerInterface $errorController */
            $errorController = new $errorControllerClass();
            if ($exception !== null) {
                $errorController->setException($exception);
            }

            try {
                $errorController->processRequest($this, $request, $response, strval($response->getStatusCode()->getCode()), []);
            } catch (\Exception $e) {
                $this->myExceptionToResponse($e, $response);
            }
        }
    }

    /**
     * Outputs an exception to a response.
     *
     * @param \Exception        $exception The exception.
     * @param ResponseInterface $response  The response.
     */
    private function myExceptionToResponse(\Exception $exception, ResponseInterface $response)
    {
        $response->setStatusCode(new StatusCode(StatusCode::INTERNAL_SERVER_ERROR));
        $response->setHeaders(new HeaderCollection());
        $response->setContent($this->myExceptionToHtml($exception));
    }

    /**
     * Converts an exception to html.
     *
     * @param \Exception $exception The exception.
     *
     * @return string The html.
     */
    private function myExceptionToHtml(\Exception $exception)
    {
        if (!$this->myIsDebug) {
            return '';
        }

        return
            "<!DOCTYPE html>\n" .
            "<html>\n" .
            "   <head>\n" .
            "      <meta charset=\"utf-8\">\n" .
            '      <title>' . htmlentities($exception->getMessage()) . "</title>\n" .
            "      <style>\n" .
            "         html, body, h1, p, code, pre {margin:0; padding:0; font-size:16px; font-family:Arial, Helvetica, sans-serif; color:#555;}\n" .
            "         h1 {font-size:2em; margin:.5em; color:#338;}\n" .
            "         p, pre {font-size:1em; margin:1em;}\n" .
            "         pre, code {font-family:monospace; color:#000;}\n" .
            "      </style>\n" .
            "   </head>\n" .
            "   <body>\n" .
            '      <h1>' . htmlentities($exception->getMessage()) . "</h1>\n" .
            "      <p>\n" .
            '         <code>' . htmlentities(get_class($exception)) . '</code> was thrown from <code>' . htmlentities($exception->getFile()) . ':' . htmlentities($exception->getLine()) . '</code> with message <code>' . htmlentities($exception->getMessage()) . ' (' . htmlentities($exception->getCode()) . ")</code>\n" .
            "      </p>\n" .
            '      <pre>' . htmlentities($exception->getTraceAsString()) . "</pre>\n" .
            "   </body>\n" .
            "</html>\n";
    }

    /**
     * Ensures that a specified directory exists.
     *
     * @param FilePathInterface $directory The directory.
     */
    private static function myEnsureDirectoryExists(FilePathInterface $directory)
    {
        if (!is_dir($directory->__toString())) {
            mkdir($directory->__toString(), 0700, true);
        }
    }

    /**
     * @var FilePathInterface My document root.
     */
    private $myDocumentRoot;

    /**
     * @var SessionItemCollectionInterface My session items.
     */
    private $mySessionItems;

    /**
     * @var bool True if in debug mode, false otherwise.
     */
    private $myIsDebug;

    /**
     * @var RouteInterface[] My routes.
     */
    private $myRoutes;

    /**
     * @var FilePathInterface Path to the application-specific temporary directory.
     */
    private $myTempPath;

    /**
     * @var FilePathInterface My view files path.
     */
    private $myViewPath;

    /**
     * @var ViewRendererInterface[] My view renderers.
     */
    private $myViewRenderers;

    /**
     * @var string|null My error controller class name.
     */
    private $myErrorControllerClass;

    /**
     * @var PluginInterface[] My plugins.
     */
    private $myPlugins;

    /**
     * @var CustomItemCollectionInterface My custom items.
     */
    private $myCustomItems;
}
