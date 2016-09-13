<?php

use BlueMvc\Core\Base\AbstractViewRenderer;
use DataTypes\Interfaces\FilePathInterface;

/**
 * A basic test view renderer.
 */
class BasicTestViewRenderer extends AbstractViewRenderer
{
    /**
     * Renders the view.
     *
     * @param FilePathInterface $viewsDirectory The views directory.
     * @param FilePathInterface $viewFile       The view file.
     * @param mixed|null        $model          The model or null if there is no model.
     *
     * @return string The rendered view.
     */
    public function renderView(FilePathInterface $viewsDirectory, FilePathInterface $viewFile, $model = null)
    {
        $fileContent = file_get_contents($viewsDirectory->withFilePath($viewFile));
        $result = str_replace('{MODEL}', $model !== null ? $model : '', $fileContent);

        return $result;
    }
}
