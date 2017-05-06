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
     * @param ApplicationInterface $application The application.
     * @param RequestInterface     $request     The request.
     * @param FilePathInterface    $viewFile    The view file.
     * @param mixed                $model       The model or null if there is no model.
     * @param mixed                $viewData    The view data or null if there is no view data.
     *
     * @return string The rendered view.
     */
    public function renderView(ApplicationInterface $application, RequestInterface $request, FilePathInterface $viewFile, $model = null, $viewData = null);

    /**
     * Returns the file extension for views compatible with this renderer.
     *
     * @since 1.0.0
     *
     * @return string The file extension for views compatible with this renderer.
     */
    public function getViewFileExtension();
}
