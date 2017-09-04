<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\Interfaces;

use DataTypes\Interfaces\FilePathInterface;

/**
 * Interface for UploadedFile class.
 *
 * @since 1.0.0
 */
interface UploadedFileInterface
{
    /**
     * Returns the original name of the file.
     *
     * @since 1.0.0
     *
     * @return string The original name of the file.
     */
    public function getOriginalName();

    /**
     * Returns the path to the uploaded file.
     *
     * @since 1.0.0
     *
     * @return FilePathInterface The path to the uploaded file.
     */
    public function getPath();
}
