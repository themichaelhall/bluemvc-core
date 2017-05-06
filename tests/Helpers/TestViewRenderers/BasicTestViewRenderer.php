<?php

namespace BlueMvc\Core\Tests\Helpers\TestViewRenderers;

use BlueMvc\Core\Base\AbstractViewRenderer;
use BlueMvc\Core\Interfaces\ApplicationInterface;
use BlueMvc\Core\Interfaces\RequestInterface;
use DataTypes\Interfaces\FilePathInterface;

/**
 * A basic test view renderer.
 */
class BasicTestViewRenderer extends AbstractViewRenderer
{
    /**
     * Constructs the view renderer.
     */
    public function __construct()
    {
        parent::__construct('view');
    }

    /**
     * Renders the view.
     *
     * @param ApplicationInterface $application The application.
     * @param RequestInterface     $request     The request.
     * @param FilePathInterface    $viewFile    The view file.
     * @param mixed                $model       The model or null if there is no model.
     * @param mixed                $viewData    The view data or null if there is no view data.
     *
     * @return string The rendered view.
     */
    public function renderView(ApplicationInterface $application, RequestInterface $request, FilePathInterface $viewFile, $model = null, $viewData = null)
    {
        $fileContent = file_get_contents($application->getViewPath()->withFilePath($viewFile));
        $result = str_replace(
            [
                '{ROOT}',
                '{URL}',
                '{MODEL}',
                '{VIEWDATA}',
            ],
            [
                $application->getDocumentRoot()->__toString(),
                $request->getUrl()->__toString(),
                $model !== null ? $model : '',
                $viewData !== null ? (is_array($viewData) ? implode(',', $viewData) : $viewData) : '',
            ], $fileContent);

        return $result;
    }
}
