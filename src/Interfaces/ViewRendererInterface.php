<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */
namespace BlueMvc\Core\Interfaces;

use DataTypes\Interfaces\FilePathInterface;

/**
 * Interface for ViewRenderer class.
 *
 * @since 1.0.0
 */
interface ViewRendererInterface
{
    /**
     * Renders the view.
     *
     * @since 1.0.0
     *
     * @param FilePathInterface $viewsDirectory The views directory.
     * @param FilePathInterface $viewFile       The view file.
     * @param mixed|null        $model          The model or null if there is no model.
     *
     * @return string The rendered view.
     */
    public function renderView(FilePathInterface $viewsDirectory, FilePathInterface $viewFile, $model = null);
}
