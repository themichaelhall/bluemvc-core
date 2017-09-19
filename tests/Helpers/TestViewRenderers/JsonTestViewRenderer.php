<?php

namespace BlueMvc\Core\Tests\Helpers\TestViewRenderers;

use BlueMvc\Core\Base\AbstractViewRenderer;
use BlueMvc\Core\Interfaces\ApplicationInterface;
use BlueMvc\Core\Interfaces\Collections\ViewItemCollectionInterface;
use BlueMvc\Core\Interfaces\RequestInterface;
use DataTypes\Interfaces\FilePathInterface;

/**
 * A basic test json view renderer.
 */
class JsonTestViewRenderer extends AbstractViewRenderer
{
    /**
     * Constructs the view renderer.
     *
     * @param mixed $viewFileExtension The view file extension.
     */
    public function __construct($viewFileExtension = 'json')
    {
        parent::__construct($viewFileExtension);
    }

    /**
     * Renders the view.
     *
     * @param ApplicationInterface        $application The application.
     * @param RequestInterface            $request     The request.
     * @param FilePathInterface           $viewFile    The view file.
     * @param mixed                       $model       The model or null if there is no model.
     * @param ViewItemCollectionInterface $viewItems   The view items or null if there is no view items.
     *
     * @return string The rendered view.
     */
    function renderView(ApplicationInterface $application, RequestInterface $request, FilePathInterface $viewFile, $model = null, ViewItemCollectionInterface $viewItems = null)
    {
        $fileContent = file_get_contents($application->getViewPath()->withFilePath($viewFile));
        $result = str_replace(
            [
                '"%%MODEL%%"',
                '"%%VIEWITEMS%%"',
            ],
            [
                json_encode($model),
                $viewItems = json_encode(iterator_to_array($viewItems)),
            ], $fileContent);

        return $result;
    }
}
