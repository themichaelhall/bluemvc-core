<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

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
        $this->myUploadedFiles = [];
    }

    /**
     * Returns the number of uploaded files.
     *
     * @since 1.0.0
     *
     * @return int The number of uploaded files.
     */
    public function count()
    {
        return count($this->myUploadedFiles);
    }

    /**
     * Returns the current uploaded file value.
     *
     * @since 1.0.0
     *
     * @return string The current uploaded file value.
     */
    public function current()
    {
        return current($this->myUploadedFiles);
    }

    /**
     * Returns the uploaded file by name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The name.
     *
     * @throws \InvalidArgumentException If the $name parameter is not a string.
     *
     * @return UploadedFileInterface|null The the uploaded file by name if it exists, null otherwise.
     */
    public function get($name)
    {
        if (!is_string($name)) {
            throw new \InvalidArgumentException('$name parameter is not a string.');
        }

        if (!isset($this->myUploadedFiles[$name])) {
            return null;
        }

        return $this->myUploadedFiles[$name];
    }

    /**
     * Returns the current uploaded file name.
     *
     * @since 1.0.0
     *
     * @return string The current uploaded file name.
     */
    public function key()
    {
        return key($this->myUploadedFiles);
    }

    /**
     * Moves forwards to the next uploaded file.
     *
     * @since 1.0.0
     */
    public function next()
    {
        next($this->myUploadedFiles);
    }

    /**
     * Rewinds the uploaded file collection to to first element.
     *
     * @since 1.0.0
     */
    public function rewind()
    {
        reset($this->myUploadedFiles);
    }

    /**
     * Sets an uploaded file by name.
     *
     * @since 1.0.0
     *
     * @param string                $name         The name.
     * @param UploadedFileInterface $uploadedFile The uploaded file.
     *
     * @throws \InvalidArgumentException If the $name parameter is not a string.
     */
    public function set($name, UploadedFileInterface $uploadedFile)
    {
        if (!is_string($name)) {
            throw new \InvalidArgumentException('$name parameter is not a string.');
        }

        $this->myUploadedFiles[$name] = $uploadedFile;
    }

    /**
     * Returns true if the current uploaded file is valid.
     *
     * @since 1.0.0
     *
     * @return bool True if the current uploaded file is valid.
     */
    public function valid()
    {
        return key($this->myUploadedFiles) !== null;
    }

    /**
     * @var UploadedFileInterface[] My uploaded files.
     */
    private $myUploadedFiles;
}
