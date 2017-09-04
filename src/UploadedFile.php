<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core;

use BlueMvc\Core\Interfaces\UploadedFileInterface;
use DataTypes\Interfaces\FilePathInterface;

/**
 * Class representing an uploaded file.
 *
 * @since 1.0.0
 */
class UploadedFile implements UploadedFileInterface
{
    /**
     * Constructs an uploaded file.
     *
     * @since 1.0.0
     *
     * @param FilePathInterface $path The path to the uploaded file.
     */
    public function __construct(FilePathInterface $path)
    {
        $this->myPath = $path;
    }

    /**
     * Returns the path to the uploaded file.
     *
     * @since 1.0.0
     *
     * @return FilePathInterface The path to the uploaded file.
     */
    public function getPath()
    {
        return $this->myPath;
    }

    /**
     * @var FilePathInterface My path to the uploaded file.
     */
    private $myPath;
}
