<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */
declare(strict_types=1);

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
    public function addPlugin(PluginInterface $plugin): void
    {
        $this->plugins[] = $plugin;
    }

    /**
     * Adds a route.
     *
     * @since 1.0.0
     *
     * @param RouteInterface $route The route.
     */
    public function addRoute(RouteInterface $route): void
    {
        $this->routes[] = $route;
    }

    /**
     * Adds a view renderer.
     *
     * @since 1.0.0
     *
     * @param ViewRendererInterface $viewRenderer The view renderer.
     */
    public function addViewRenderer(ViewRendererInterface $viewRenderer): void
    {
        $this->viewRenderers[] = $viewRenderer;
    }

    /**
     * Returns a custom item by name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The custom item name.
     *
     * @return mixed|null The custom item if it exists, null otherwise.
     */
    public function getCustomItem(string $name)
    {
        return $this->customItems->get($name);
    }

    /**
     * Returns the custom items.
     *
     * @since 1.0.0
     *
     * @return CustomItemCollectionInterface The custom items.
     */
    public function getCustomItems(): CustomItemCollectionInterface
    {
        return $this->customItems;
    }

    /**
     * Returns the document root.
     *
     * @since 1.0.0
     *
     * @return FilePathInterface The document root.
     */
    public function getDocumentRoot(): FilePathInterface
    {
        return $this->documentRoot;
    }

    /**
     * Returns the error controller class name or null if not specified.
     *
     * @since 1.0.0
     *
     * @return string|null The error controller class name or null if not specified.
     */
    public function getErrorControllerClass(): ?string
    {
        return $this->errorControllerClass;
    }

    /**
     * Returns the plugins.
     *
     * @since 1.0.0
     *
     * @return PluginInterface[] The plugins.
     */
    public function getPlugins(): array
    {
        return $this->plugins;
    }

    /**
     * Returns the routes.
     *
     * @since 1.0.0
     *
     * @return RouteInterface[] The routes.
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * Returns a session item by name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The name.
     *
     * @return mixed|null The session item if it exists, null otherwise.
     */
    public function getSessionItem(string $name)
    {
        return $this->sessionItems->get($name);
    }

    /**
     * Returns the session items.
     *
     * @since 1.0.0
     *
     * @return SessionItemCollectionInterface The session items.
     */
    public function getSessionItems(): SessionItemCollectionInterface
    {
        return $this->sessionItems;
    }

    /**
     * Returns the path to the application-specific temporary directory.
     *
     * @since 1.0.0
     *
     * @return FilePathInterface The path to the application-specific temporary directory.
     */
    public function getTempPath(): FilePathInterface
    {
        if ($this->tempPath === null) {
            // Generate a default temporary directory by document root.
            $this->tempPath = FilePath::parse(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'bluemvc' . DIRECTORY_SEPARATOR . sha1($this->documentRoot->__toString()) . DIRECTORY_SEPARATOR);
            self::ensureDirectoryExists($this->tempPath);
        }

        return $this->tempPath;
    }

    /**
     * Returns The view files path.
     *
     * @since 1.0.0
     *
     * @return FilePathInterface The view files path.
     */
    public function getViewPath(): FilePathInterface
    {
        if ($this->viewPath === null) {
            return $this->documentRoot;
        }

        return $this->viewPath;
    }

    /**
     * Returns the view renderers.
     *
     * @since 1.0.0
     *
     * @return ViewRendererInterface[] The view renderers.
     */
    public function getViewRenderers(): array
    {
        return $this->viewRenderers;
    }

    /**
     * Returns true if in debug mode, false otherwise.
     *
     * @since 1.0.0
     *
     * @return bool True if in debug mode, false otherwise.
     */
    public function isDebug(): bool
    {
        return $this->isDebug;
    }

    /**
     * Removes a session item by name.
     *
     * @since 1.0.0
     *
     * @param string $name The session item name.
     */
    public function removeSessionItem(string $name): void
    {
        $this->sessionItems->remove($name);
    }

    /**
     * Runs a request.
     *
     * @since 1.0.0
     *
     * @param RequestInterface  $request  The request.
     * @param ResponseInterface $response The response.
     */
    public function run(RequestInterface $request, ResponseInterface $response): void
    {
        $this->doRun($request, $response);
        $response->output();
    }

    /**
     * Sets a custom item.
     *
     * @since 1.0.0
     *
     * @param string $name  The custom item name.
     * @param mixed  $value The custom item value.
     */
    public function setCustomItem(string $name, $value): void
    {
        $this->customItems->set($name, $value);
    }

    /**
     * Sets the custom items.
     *
     * @since 1.0.0
     *
     * @param CustomItemCollectionInterface $customItems The custom items.
     */
    public function setCustomItems(CustomItemCollectionInterface $customItems): void
    {
        $this->customItems = $customItems;
    }

    /**
     * Sets the error controller class name.
     *
     * @since 1.0.0
     *
     * @param string $errorControllerClass The error controller class name.
     *
     * @throws InvalidControllerClassException If the class name is not a valid controller class.
     */
    public function setErrorControllerClass(string $errorControllerClass): void
    {
        if (!is_a($errorControllerClass, ErrorControllerInterface::class, true)) {
            throw new InvalidControllerClassException('"' . $errorControllerClass . '" is not a valid error controller class.');
        }

        $this->errorControllerClass = $errorControllerClass;
    }

    /**
     * Sets a session item.
     *
     * @since 1.0.0
     *
     * @param string $name  The session item name.
     * @param mixed  $value The session item value.
     */
    public function setSessionItem(string $name, $value): void
    {
        $this->sessionItems->set($name, $value);
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
    public function setTempPath(FilePathInterface $tempPath): void
    {
        if (!$tempPath->isDirectory()) {
            throw new InvalidFilePathException('Temp path "' . $tempPath . '" is not a directory.');
        }

        try {
            $this->tempPath = $this->documentRoot->withFilePath($tempPath);
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
    public function setViewPath(FilePathInterface $viewPath): void
    {
        if (!$viewPath->isDirectory()) {
            throw new InvalidFilePathException('View path "' . $viewPath . '" is not a directory.');
        }

        try {
            $this->viewPath = $this->documentRoot->withFilePath($viewPath);
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
        $this->routes = [];
        $this->tempPath = null;
        $this->viewRenderers = [];
        $this->viewPath = null;
        $this->isDebug = false;
        $this->errorControllerClass = null;
        $this->plugins = [];
        $this->customItems = new CustomItemCollection();
    }

    /**
     * Sets the debug mode.
     *
     * @since 1.0.0
     *
     * @param bool $isDebug The debug mode.
     */
    protected function setDebug(bool $isDebug): void
    {
        $this->isDebug = $isDebug;
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
    protected function setDocumentRoot(FilePathInterface $documentRoot): void
    {
        if (!$documentRoot->isDirectory()) {
            throw new InvalidFilePathException('Document root "' . $documentRoot . '" is not a directory.');
        }

        if (!$documentRoot->isAbsolute()) {
            throw new InvalidFilePathException('Document root "' . $documentRoot . '" is not an absolute path.');
        }

        $this->documentRoot = $documentRoot;
    }

    /**
     * Sets the session items.
     *
     * @since 1.0.0
     *
     * @param SessionItemCollectionInterface $sessionItems The session items.
     */
    protected function setSessionItems(SessionItemCollectionInterface $sessionItems): void
    {
        $this->sessionItems = $sessionItems;
    }

    /**
     * Runs a request.
     *
     * @param RequestInterface  $request  The request.
     * @param ResponseInterface $response The response.
     */
    private function doRun(RequestInterface $request, ResponseInterface $response): void
    {
        $exception = null;

        foreach ($this->plugins as $plugin) {
            if ($plugin->onPreRequest($this, $request, $response)) {
                return;
            }
        }

        try {
            $this->handleRequest($request, $response);
        } catch (\Exception $e) {
            $this->exceptionToResponse($e, $response);
            $exception = $e;
        }

        if ($response->getStatusCode()->isError()) {
            $this->handleError($request, $response, $exception);
        }

        foreach ($this->plugins as $plugin) {
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
    private function handleRequest(RequestInterface $request, ResponseInterface $response): void
    {
        foreach ($this->routes as $route) {
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
    private function handleError(RequestInterface $request, ResponseInterface $response, \Exception $exception = null): void
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
                $this->exceptionToResponse($e, $response);
            }
        }
    }

    /**
     * Outputs an exception to a response.
     *
     * @param \Exception        $exception The exception.
     * @param ResponseInterface $response  The response.
     */
    private function exceptionToResponse(\Exception $exception, ResponseInterface $response): void
    {
        $response->setStatusCode(new StatusCode(StatusCode::INTERNAL_SERVER_ERROR));
        $response->setHeaders(new HeaderCollection());
        $response->setContent($this->exceptionToHtml($exception));
    }

    /**
     * Converts an exception to html.
     *
     * @param \Exception $exception The exception.
     *
     * @return string The html.
     */
    private function exceptionToHtml(\Exception $exception): string
    {
        if (!$this->isDebug) {
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
            '         <code>' . htmlentities(get_class($exception)) . '</code> was thrown from <code>' . htmlentities($exception->getFile()) . ':' . htmlentities(strval($exception->getLine())) . '</code> with message <code>' . htmlentities($exception->getMessage()) . ' (' . htmlentities(strval($exception->getCode())) . ")</code>\n" .
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
    private static function ensureDirectoryExists(FilePathInterface $directory): void
    {
        if (!is_dir($directory->__toString())) {
            mkdir($directory->__toString(), 0700, true);
        }
    }

    /**
     * @var FilePathInterface My document root.
     */
    private $documentRoot;

    /**
     * @var SessionItemCollectionInterface My session items.
     */
    private $sessionItems;

    /**
     * @var bool True if in debug mode, false otherwise.
     */
    private $isDebug;

    /**
     * @var RouteInterface[] My routes.
     */
    private $routes;

    /**
     * @var FilePathInterface Path to the application-specific temporary directory.
     */
    private $tempPath;

    /**
     * @var FilePathInterface My view files path.
     */
    private $viewPath;

    /**
     * @var ViewRendererInterface[] My view renderers.
     */
    private $viewRenderers;

    /**
     * @var string|null My error controller class name.
     */
    private $errorControllerClass;

    /**
     * @var PluginInterface[] My plugins.
     */
    private $plugins;

    /**
     * @var CustomItemCollectionInterface My custom items.
     */
    private $customItems;
}
