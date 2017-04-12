<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\Collections;

use BlueMvc\Core\Interfaces\Collections\ParameterCollectionInterface;

/**
 * Class representing a collection of parameters.
 *
 * @since 1.0.0
 */
class ParameterCollection implements ParameterCollectionInterface
{
    /**
     * Constructs the collection of parameters.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->myParameters = [];
    }

    /**
     * Returns the number of parameters.
     *
     * @since 1.0.0
     *
     * @return int The number of parameters.
     */
    public function count()
    {
        return count($this->myParameters);
    }

    /**
     * @var array My parameters.
     */
    private $myParameters;
}
