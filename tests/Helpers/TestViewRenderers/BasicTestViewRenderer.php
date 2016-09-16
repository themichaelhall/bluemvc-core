<?php

use BlueMvc\Core\Base\AbstractViewRenderer;
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
     * @param FilePathInterface $viewsDirectory The views directory.
     * @param FilePathInterface $viewFile       The view file.
     * @param mixed             $model          The model or null if there is no model.
     * @param mixed             $viewData       The view data or null if there is no view data.
     *
     * @return string The rendered view.
     */
    public function renderView(FilePathInterface $viewsDirectory, FilePathInterface $viewFile, $model = null, $viewData = null)
    {
        $fileContent = file_get_contents($viewsDirectory->withFilePath($viewFile));
        $result = str_replace(
            [
                '{MODEL}',
                '{VIEWDATA}',
            ],
            [
                $model !== null ? $model : '',
                $viewData !== null ? (is_array($viewData) ? join(',', $viewData) : $viewData) : '',
            ], $fileContent);

        return $result;
    }
}
