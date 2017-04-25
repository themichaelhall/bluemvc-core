<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\Interfaces\Collections;

/**
 * Interface for ParameterCollection class.
 *
 * @since 1.0.0
 */
interface ParameterCollectionInterface extends \Countable, \Iterator
{
    /**
     * Returns the parameter value by parameter name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The parameter name.
     *
     * @return string|null The parameter value by parameter name if it exists, null otherwise.
     */
    public function get($name);

    /**
     * Sets a parameter value by parameter name.
     *
     * @since 1.0.0
     *
     * @param string $name  The parameter name.
     * @param string $value The parameter value.
     */
    public function set($name, $value);
}
