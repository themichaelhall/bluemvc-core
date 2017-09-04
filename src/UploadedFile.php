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
     * @param int               $size         The size of the file.
     *
     * @throws \InvalidArgumentException If any of the parameters are of invalid type.
     */
    public function __construct(FilePathInterface $path, $originalName = '', $size = 0)
    {
        if (!is_string($originalName)) {
            throw new \InvalidArgumentException('$originalName parameter is not a string.');
        }

        if (!is_int($size)) {
            throw new \InvalidArgumentException('$size parameter is not an integer.');
        }

        $this->myPath = $path;
        $this->myOriginalName = $originalName;
        $this->mySize = $size;
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
     * Returns the size of the file.
     *
     * @since 1.0.0
     *
     * @return int The size of the file.
     */
    public function getSize()
    {
        return $this->mySize;
    }

    /**
     * @var FilePathInterface My path to the file.
     */
    private $myPath;

    /**
     * @var string My original name of the file.
     */
    private $myOriginalName;

    /**
     * @var int My size of the file.
     */
    private $mySize;
}
