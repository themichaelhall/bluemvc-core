<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\Base;

use BlueMvc\Core\Exceptions\InvalidControllerClassException;
use BlueMvc\Core\Exceptions\InvalidFilePathException;
use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Interfaces\ApplicationInterface;
use BlueMvc\Core\Interfaces\ControllerInterface;
use BlueMvc\Core\Interfaces\ErrorControllerInterface;
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
            $tempPath = FilePath::parse(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'bluemvc' . DIRECTORY_SEPARATOR . sha1($this->myDocumentRoot->__toString()) . DIRECTORY_SEPARATOR);
            self::myEnsureDirectoryExists($tempPath);

            return $tempPath;
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
     * Runs a request.
     *
     * @since 1.0.0
     *
     * @param RequestInterface  $request  The request.
     * @param ResponseInterface $response The response.
     */
    public function run(RequestInterface $request, ResponseInterface $response)
    {
        $exception = null;

        try {
            $requestIsProcessed = false;

            foreach ($this->myRoutes as $route) {
                $routeMatch = $route->matches($request);

                if ($routeMatch !== null) {
                    $controllerClass = $routeMatch->getControllerClassName();
                    $controller = new $controllerClass();

                    /** @var ControllerInterface $controller */
                    $requestIsProcessed = $controller->processRequest($this, $request, $response, $routeMatch->getAction(), $routeMatch->getParameters());

                    break;
                }
            }

            if (!$requestIsProcessed) {
                $response->setStatusCode(new StatusCode(StatusCode::NOT_FOUND));
            }
        } catch (\Exception $e) {
            $this->myExceptionToResponse($e, $response);
            $exception = $e;
        }

        // Error controller handling.
        $responseCode = $response->getStatusCode();
        if ($responseCode->isError()) {
            $errorControllerClass = $this->getErrorControllerClass();

            if ($errorControllerClass !== null) {
                /** @var ErrorControllerInterface $errorController */
                $errorController = new $errorControllerClass();
                if ($exception !== null) {
                    $errorController->setException($exception);
                }

                try {
                    $errorController->processRequest($this, $request, $response, strval($responseCode->getCode()), []);
                } catch (\Exception $e) {
                    $this->myExceptionToResponse($e, $response);
                }
            }
        }

        $response->output();
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
     * @param FilePathInterface $documentRoot The document root.
     */
    protected function __construct(FilePathInterface $documentRoot)
    {
        $this->setDocumentRoot($documentRoot);
        $this->myRoutes = [];
        $this->myTempPath = null;
        $this->myViewRenderers = [];
        $this->myViewPath = null;
        $this->myIsDebug = false;
        $this->myErrorControllerClass = null;
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
     * Outputs an exception to a response.
     *
     * @param \Exception        $exception The exception.
     * @param ResponseInterface $response  The response.
     */
    private function myExceptionToResponse(\Exception $exception, ResponseInterface $response)
    {
        // fixme: clear headers.
        $response->setStatusCode(new StatusCode(StatusCode::INTERNAL_SERVER_ERROR));
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
            "         html, body, h1, p, code, pre {\n" .
            "            margin:0;\n" .
            "            padding:0;\n" .
            "            font-size:16px;\n" .
            "            font-family:Arial, Helvetica, sans-serif;\n" .
            "            color:#555;\n" .
            "         }\n" .
            "         h1 {\n" .
            "            font-size:2em;\n" .
            "            margin:.5em;\n" .
            "            color:#338;\n" .
            "         }\n" .
            "         p, pre {\n" .
            "            font-size:1em;\n" .
            "            margin:1em;\n" .
            "         }\n" .
            "         pre, code {\n" .
            "            font-family:monospace;\n" .
            "            color:#000;\n" .
            "         }\n" .
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
}
