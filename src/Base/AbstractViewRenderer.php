<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\Base;

use BlueMvc\Core\Interfaces\ApplicationInterface;
use BlueMvc\Core\Interfaces\Collections\ViewItemCollectionInterface;
use BlueMvc\Core\Interfaces\RequestInterface;
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
     * @param ApplicationInterface        $application The application.
     * @param RequestInterface            $request     The request.
     * @param FilePathInterface           $viewFile    The view file.
     * @param mixed                       $model       The model or null if there is no model.
     * @param ViewItemCollectionInterface $viewItems   The view items or null if there is no view items.
     *
     * @return string The rendered view.
     */
    abstract public function renderView(ApplicationInterface $application, RequestInterface $request, FilePathInterface $viewFile, $model = null, ViewItemCollectionInterface $viewItems = null);

    /**
     * @var string My file extension for views compatible with this renderer.
     */
    private $myViewFileExtension;
}
