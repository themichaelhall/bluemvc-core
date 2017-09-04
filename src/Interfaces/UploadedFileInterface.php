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
     * Returns the path to the uploaded file.
     *
     * @since 1.0.0
     *
     * @return FilePathInterface The path to the uploaded file.
     */
    public function getPath();
}
