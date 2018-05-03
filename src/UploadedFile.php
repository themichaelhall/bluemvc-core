<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */
declare(strict_types=1);

namespace BlueMvc\Core;

use BlueMvc\Core\Exceptions\InvalidFilePathException;
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
     * @throws InvalidFilePathException If the path is not file or an absolute path.
     */
    public function __construct(FilePathInterface $path, string $originalName = '', int $size = 0)
    {
        if (!$path->isFile()) {
            throw new InvalidFilePathException('Path "' . $path . '" is not a file.');
        }

        if (!$path->isAbsolute()) {
            throw new InvalidFilePathException('Path "' . $path . '" is not an absolute path.');
        }

        $this->path = $path;
        $this->originalName = $originalName;
        $this->size = $size;
    }

    /**
     * Returns the original name of the file.
     *
     * @since 1.0.0
     *
     * @return string The original name of the file.
     */
    public function getOriginalName(): string
    {
        return $this->originalName;
    }

    /**
     * Returns the path to the file.
     *
     * @since 1.0.0
     *
     * @return FilePathInterface The path to the file.
     */
    public function getPath(): FilePathInterface
    {
        return $this->path;
    }

    /**
     * Returns the size of the file.
     *
     * @since 1.0.0
     *
     * @return int The size of the file.
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @var FilePathInterface My path to the file.
     */
    private $path;

    /**
     * @var string My original name of the file.
     */
    private $originalName;

    /**
     * @var int My size of the file.
     */
    private $size;
}
