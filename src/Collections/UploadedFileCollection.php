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
     * @var UploadedFileInterface[] My uploaded files.
     */
    private $myUploadedFiles;
}
