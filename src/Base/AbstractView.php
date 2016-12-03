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
     * @param mixed $model The model.
     */
    public function __construct($model = null)
    {
        $this->myModel = $model;
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
}
