<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */
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
     * Constructs the view renderer.
     *
     * @since 1.0.0
     *
     * @param string $viewFileExtension The view file extension for views compatible with this renderer.
     */
    public function __construct($viewFileExtension)
    {
        // fixme: Validate $getViewFileExtension
        $this->myViewFileExtension = $viewFileExtension;
    }

    /**
     * Returns the file extension for views compatible with this renderer.
     *
     * @since 1.0.0
     *
     * @return string The file extension for views compatible with this renderer.
     */
    public function getViewFileExtension()
    {
        return $this->myViewFileExtension;
    }

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

    /**
     * @var string My file extension for views compatible with this renderer.
     */
    private $myViewFileExtension;
}
