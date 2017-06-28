<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\Interfaces;

/**
 * Interface for View class.
 *
 * @since 1.0.0
 */
interface ViewInterface
{
    /**
     * Returns the file.
     *
     * @since 1.0.0
     *
     * @return string|null The file.
     */
    public function getFile();

    /**
     * Returns the model.
     *
     * @since 1.0.0
     *
     * @return mixed The model.
     */
    public function getModel();
}
