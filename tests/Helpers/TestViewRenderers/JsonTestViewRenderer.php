<?php

declare(strict_types=1);

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
     * @param string $viewFileExtension The view file extension.
     */
    public function __construct(string $viewFileExtension = 'json')
    {
        parent::__construct($viewFileExtension);
    }

    /**
     * Renders the view.
     *
     * @param ApplicationInterface             $application The application.
     * @param RequestInterface                 $request     The request.
     * @param FilePathInterface                $viewFile    The view file.
     * @param mixed|null                       $model       The model or null if there is no model.
     * @param ViewItemCollectionInterface|null $viewItems   The view items or null if there is no view items.
     *
     * @return string The rendered view.
     */
    public function renderView(ApplicationInterface $application, RequestInterface $request, FilePathInterface $viewFile, $model = null, ?ViewItemCollectionInterface $viewItems = null): string
    {
        $fileContent = file_get_contents($application->getViewPath()->withFilePath($viewFile)->__toString());
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
