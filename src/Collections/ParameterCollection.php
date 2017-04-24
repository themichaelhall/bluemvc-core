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
     * Returns the parameter value by parameter name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The parameter name.
     *
     * @throws \InvalidArgumentException If the $name parameter is not a string.
     *
     * @return string|null The parameter value by parameter name if it exists, null otherwise.
     */
    public function get($name)
    {
        if (!is_string($name)) {
            throw new \InvalidArgumentException('$name parameter is not a string.');
        }

        if (!isset($this->myParameters[$name])) {
            return null;
        }

        return $this->myParameters[$name];
    }

    /**
     * Sets a parameter value by parameter name.
     *
     * @since 1.0.0
     *
     * @param string $name  The parameter name.
     * @param string $value The parameter value.
     *
     * @throws \InvalidArgumentException If any of the parameters are of invalid type.
     */
    public function set($name, $value)
    {
        if (!is_string($name)) {
            throw new \InvalidArgumentException('$name parameter is not a string.');
        }

        if (!is_string($value)) {
            throw new \InvalidArgumentException('$value parameter is not a string.');
        }

        $this->myParameters[$name] = $value;
    }

    /**
     * @var array My parameters.
     */
    private $myParameters;
}
