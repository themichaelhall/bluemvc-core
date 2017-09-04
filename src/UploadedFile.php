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
     * @param FilePathInterface $path         The path to the file.
     * @param string            $originalName The original name of the file.
     *
     * @throws \InvalidArgumentException If any of the parameters are of invalid type.
     */
    public function __construct(FilePathInterface $path, $originalName = '')
    {
        if (!is_string($originalName)) {
            throw new \InvalidArgumentException('$originalName parameter is not a string.');
        }

        $this->myPath = $path;
        $this->myOriginalName = $originalName;
    }

    /**
     * Returns the original name of the file.
     *
     * @since 1.0.0
     *
     * @return string The original name of the file.
     */
    public function getOriginalName()
    {
        return $this->myOriginalName;
    }

    /**
     * Returns the path to the file.
     *
     * @since 1.0.0
     *
     * @return FilePathInterface The path to the file.
     */
    public function getPath()
    {
        return $this->myPath;
    }

    /**
     * @var FilePathInterface My path to the file.
     */
    private $myPath;

    /**
     * @var string My original name of the file.
     */
    private $myOriginalName;
}
