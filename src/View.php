<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core;

use BlueMvc\Core\Exceptions\InvalidViewFileException;
use BlueMvc\Core\Exceptions\MissingViewRendererException;
use BlueMvc\Core\Exceptions\ViewFileNotFoundException;
use BlueMvc\Core\Interfaces\ApplicationInterface;
use BlueMvc\Core\Interfaces\Collections\ViewItemCollectionInterface;
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
     * @throws InvalidViewFileException  If the file is invalid.
     */
    public function __construct($model = null, $file = null)
    {
        $this->myModel = $model;

        if (!is_string($file) && !is_null($file)) {
            throw new \InvalidArgumentException('$file parameter is not a string or null.');
        }

        if (preg_match('/[^a-zA-Z0-9._-]/', $file, $matches)) {
            throw new InvalidViewFileException('View file "' . $file . '" contains invalid character "' . $matches[0] . '".');
        }

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
     * @param string                      $viewPath    The relative path to the view.
     * @param string                      $action      The action.
     * @param ViewItemCollectionInterface $viewItems   The view items.
     *
     * @throws \InvalidArgumentException    If any of the parameters are of invalid type.
     * @throws MissingViewRendererException If no view renderer was added to the application.
     * @throws ViewFileNotFoundException    If a suitable view file could not be found.
     */
    public function updateResponse(ApplicationInterface $application, RequestInterface $request, ResponseInterface $response, $viewPath, $action, ViewItemCollectionInterface $viewItems)
    {
        if (!is_string($viewPath)) {
            throw new \InvalidArgumentException('$viewPath parameter is not a string.');
        }

        if (!is_string($action)) {
            throw new \InvalidArgumentException('$action parameter is not a string.');
        }

        $viewRenderers = $application->getViewRenderers();
        if (count($viewRenderers) === 0) {
            throw new MissingViewRendererException('No view renderer was added to application.');
        }

        // Try the view renderers until a match is found.
        $testedViewFiles = [];
        foreach ($viewRenderers as $viewRenderer) {
            $viewFile = FilePath::parse($viewPath . DIRECTORY_SEPARATOR . ($this->getFile() ?: $action) . '.' . $viewRenderer->getViewFileExtension());
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
