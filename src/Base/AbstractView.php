<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\Base;

use BlueMvc\Core\Interfaces\ViewInterface;

/**
 * Abstract class representing a view.
 *
 * @since 1.0.0
 */
abstract class AbstractView implements ViewInterface
{
    /**
     * Constructs the view.
     *
     * @since 1.0.0
     *
     * @param mixed       $model The model.
     * @param string|null $file  The file.
     *
     * @throws \InvalidArgumentException If the $file parameter is not a string or null.
     */
    public function __construct($model = null, $file = null)
    {
        $this->myModel = $model;

        if (!is_string($file) && !is_null($file)) {
            throw new \InvalidArgumentException('$file parameter is not a string or null.');
        }

        // fixme: validate $file
        $this->myFile = $file;
    }

    /**
     * Returns the file.
     *
     * @since 1.0.0
     *
     * @return string|null The file.
     */
    public function getFile()
    {
        return $this->myFile;
    }

    /**
     * Returns the model.
     *
     * @since 1.0.0
     *
     * @return mixed The model.
     */
    public function getModel()
    {
        return $this->myModel;
    }

    /**
     * @var mixed My model.
     */
    private $myModel;

    /**
     * @var string|null My file.
     */
    private $myFile;
}
