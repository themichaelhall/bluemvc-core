<?php

/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

declare(strict_types=1);

namespace BlueMvc\Core\Interfaces;

use BlueMvc\Core\Interfaces\Collections\ViewItemCollectionInterface;
use DataTypes\System\FilePathInterface;

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
     * @param ApplicationInterface             $application The application.
     * @param RequestInterface                 $request     The request.
     * @param FilePathInterface                $viewFile    The view file.
     * @param mixed|null                       $model       The model or null if there is no model.
     * @param ViewItemCollectionInterface|null $viewItems   The view items or null if there is no view items.
     *
     * @return string The rendered view.
     */
    public function renderView(ApplicationInterface $application, RequestInterface $request, FilePathInterface $viewFile, $model = null, ?ViewItemCollectionInterface $viewItems = null): string;

    /**
     * Returns the file extension for views compatible with this renderer.
     *
     * @since 1.0.0
     *
     * @return string The file extension for views compatible with this renderer.
     */
    public function getViewFileExtension(): string;
}
