<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */
declare(strict_types=1);

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
    public function getOriginalName(): string;

    /**
     * Returns the path to the uploaded file.
     *
     * @since 1.0.0
     *
     * @return FilePathInterface The path to the uploaded file.
     */
    public function getPath(): FilePathInterface;

    /**
     * Returns the size of the file.
     *
     * @since 1.0.0
     *
     * @return int The size of the file.
     */
    public function getSize(): int;
}
