<?php

namespace BlueMvc\Core\Base;

use BlueMvc\Core\Interfaces\ViewRendererInterface;
use DataTypes\Interfaces\FilePathInterface;

/**
 * Abstract class representing a view renderer.
 *
 * @since 1.0.0
 */
abstract class AbstractViewRenderer implements ViewRendererInterface
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
    abstract public function renderView(FilePathInterface $viewsDirectory, FilePathInterface $viewFile, $model = null);
}
