<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core;

use BlueMvc\Core\Exceptions\ViewFileNotFoundException;
use BlueMvc\Core\Interfaces\ApplicationInterface;
use BlueMvc\Core\Interfaces\Collections\ViewItemCollectionInterface;
use BlueMvc\Core\Interfaces\ControllerInterface;
use BlueMvc\Core\Interfaces\RequestInterface;
use BlueMvc\Core\Interfaces\ResponseInterface;
use BlueMvc\Core\Interfaces\ViewInterface;
use DataTypes\FilePath;

/**
 * Class representing a view.
 *
 * @since 1.0.0
 */
class View implements ViewInterface
{
    /**
     * Constructs the view.
     *
     * @since 1.0.0
     *
     * @param mixed       $model The model.
     * @param string|null $file  The file.
     *
     * @throws \InvalidArgumentException If the $file parameter is not a string or null.
     */
    public function __construct($model = null, $file = null)
    {
        $this->myModel = $model;

        if (!is_string($file) && !is_null($file)) {
            throw new \InvalidArgumentException('$file parameter is not a string or null.');
        }

        // fixme: validate $file
        $this->myFile = $file;
    }

    /**
     * Returns the file.
     *
     * @since 1.0.0
     *
     * @return string|null The file.
     */
    public function getFile()
    {
        return $this->myFile;
    }

    /**
     * Returns the model.
     *
     * @since 1.0.0
     *
     * @return mixed The model.
     */
    public function getModel()
    {
        return $this->myModel;
    }

    /**
     * Updates the response.
     *
     * @since 1.0.0
     *
     * @param ApplicationInterface        $application The application.
     * @param RequestInterface            $request     The request.
     * @param ResponseInterface           $response    The response.
     * @param ControllerInterface         $controller  The controller.
     * @param string                      $action      The action.
     * @param ViewItemCollectionInterface $viewItems   The view items.
     *
     * @throws \InvalidArgumentException If the $action parameter is not a string.
     * @throws ViewFileNotFoundException If a suitable view file could not be found.
     */
    public function updateResponse(ApplicationInterface $application, RequestInterface $request, ResponseInterface $response, ControllerInterface $controller, $action, ViewItemCollectionInterface $viewItems)
    {
        if (!is_string($action)) {
            throw new \InvalidArgumentException('$action parameter is not a string.');
        }

        $controllerName = (new \ReflectionClass($controller))->getShortName();
        if (strlen($controllerName) > 10 && substr(strtolower($controllerName), -10) === 'controller') {
            $controllerName = substr($controllerName, 0, -10);
        }

        // Try the view renderers until a match is found.
        $testedViewFiles = [];

        // fixme: Exception if no view renderers are found.
        foreach ($application->getViewRenderers() as $viewRenderer) {
            $viewFile = FilePath::parse($controllerName . DIRECTORY_SEPARATOR . ($this->getFile() ?: $action) . '.' . $viewRenderer->getViewFileExtension());
            $fullViewFile = $application->getViewPath()->withFilePath($viewFile);

            if (file_exists($fullViewFile->__toString())) {
                $response->setContent($viewRenderer->renderView($application, $request, $viewFile, $this->getModel(), $viewItems));

                return;
            }

            $testedViewFiles[] = '"' . $fullViewFile->__toString() . '"';
        }

        throw new ViewFileNotFoundException('Could not find view file ' . implode(' or ', $testedViewFiles));
    }

    /**
     * @var mixed My model.
     */
    private $myModel;

    /**
     * @var string|null My file.
     */
    private $myFile;
}
