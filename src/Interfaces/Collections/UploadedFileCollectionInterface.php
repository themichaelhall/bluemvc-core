<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */
declare(strict_types=1);

namespace BlueMvc\Core\Interfaces\Collections;

use BlueMvc\Core\Interfaces\UploadedFileInterface;

/**
 * Interface for UploadedFileCollection class.
 *
 * @since 1.0.0
 */
interface UploadedFileCollectionInterface extends \Countable, \Iterator
{
    /**
     * Returns the uploaded file by name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The name.
     *
     * @return UploadedFileInterface|null The the uploaded file by name if it exists, null otherwise.
     */
    public function get(string $name): ?UploadedFileInterface;

    /**
     * Sets an uploaded file by name.
     *
     * @since 1.0.0
     *
     * @param string                $name         The name.
     * @param UploadedFileInterface $uploadedFile The uploaded file.
     */
    public function set(string $name, UploadedFileInterface $uploadedFile): void;
}
