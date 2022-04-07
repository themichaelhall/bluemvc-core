<?php

/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

declare(strict_types=1);

namespace BlueMvc\Core\Collections;

use BlueMvc\Core\Interfaces\Collections\UploadedFileCollectionInterface;
use BlueMvc\Core\Interfaces\UploadedFileInterface;

/**
 * Class representing a collection of uploaded files.
 *
 * @since 1.0.0
 */
class UploadedFileCollection implements UploadedFileCollectionInterface
{
    /**
     * Constructs the collection of uploaded files.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->uploadedFiles = [];
    }

    /**
     * Returns the number of uploaded files.
     *
     * @since 1.0.0
     *
     * @return int The number of uploaded files.
     */
    public function count(): int
    {
        return count($this->uploadedFiles);
    }

    /**
     * Returns the current uploaded file value.
     *
     * @since 1.0.0
     *
     * @return UploadedFileInterface The current uploaded file value.
     */
    public function current(): UploadedFileInterface
    {
        return current($this->uploadedFiles);
    }

    /**
     * Returns the uploaded file by name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The name.
     *
     * @return UploadedFileInterface|null The uploaded file by name if it exists, null otherwise.
     */
    public function get(string $name): ?UploadedFileInterface
    {
        if (!isset($this->uploadedFiles[$name])) {
            return null;
        }

        return $this->uploadedFiles[$name];
    }

    /**
     * Returns the current uploaded file name.
     *
     * @since 1.0.0
     *
     * @return string The current uploaded file name.
     */
    public function key(): string
    {
        return strval(key($this->uploadedFiles));
    }

    /**
     * Moves forwards to the next uploaded file.
     *
     * @since 1.0.0
     */
    public function next(): void
    {
        next($this->uploadedFiles);
    }

    /**
     * Rewinds the uploaded file collection to first element.
     *
     * @since 1.0.0
     */
    public function rewind(): void
    {
        reset($this->uploadedFiles);
    }

    /**
     * Sets an uploaded file by name.
     *
     * @since 1.0.0
     *
     * @param string                $name         The name.
     * @param UploadedFileInterface $uploadedFile The uploaded file.
     */
    public function set(string $name, UploadedFileInterface $uploadedFile): void
    {
        $this->uploadedFiles[$name] = $uploadedFile;
    }

    /**
     * Returns true if the current uploaded file is valid.
     *
     * @since 1.0.0
     *
     * @return bool True if the current uploaded file is valid.
     */
    public function valid(): bool
    {
        return key($this->uploadedFiles) !== null;
    }

    /**
     * @var UploadedFileInterface[] The uploaded files.
     */
    private array $uploadedFiles;
}
