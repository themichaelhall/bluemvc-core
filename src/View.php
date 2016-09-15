<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */
namespace BlueMvc\Core;

use BlueMvc\Core\Base\AbstractView;

/**
 * Class representing a view.
 *
 * @since 1.0.0
 */
class View extends AbstractView
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
        parent::__construct($model);
    }
}
