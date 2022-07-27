<?php

/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

declare(strict_types=1);

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
        $this->parameters = [];
    }

    /**
     * Returns the number of parameters.
     *
     * @since 1.0.0
     *
     * @return int The number of parameters.
     */
    public function count(): int
    {
        return count($this->parameters);
    }

    /**
     * Returns the current parameter value.
     *
     * @since 1.0.0
     *
     * @return string|false The current parameter value.
     */
    public function current(): string|false
    {
        return current($this->parameters);
    }

    /**
     * Returns the parameter value by parameter name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The parameter name.
     *
     * @return string|null The parameter value by parameter name if it exists, null otherwise.
     */
    public function get(string $name): ?string
    {
        if (!isset($this->parameters[$name])) {
            return null;
        }

        return $this->parameters[$name];
    }

    /**
     * Returns the current parameter name.
     *
     * @since 1.0.0
     *
     * @return string The current parameter name.
     */
    public function key(): string
    {
        return strval(key($this->parameters));
    }

    /**
     * Moves forwards to the next parameter.
     *
     * @since 1.0.0
     */
    public function next(): void
    {
        next($this->parameters);
    }

    /**
     * Sets a parameter value by parameter name.
     *
     * @since 1.0.0
     *
     * @param string $name  The parameter name.
     * @param string $value The parameter value.
     */
    public function set(string $name, string $value): void
    {
        $this->parameters[$name] = $value;
    }

    /**
     * Rewinds the parameter collection to first element.
     *
     * @since 1.0.0
     */
    public function rewind(): void
    {
        reset($this->parameters);
    }

    /**
     * Returns true if the current parameter is valid.
     *
     * @since 1.0.0
     *
     * @return bool True if the current parameter is valid.
     */
    public function valid(): bool
    {
        return current($this->parameters) !== false;
    }

    /**
     * @var array<string, string> The parameters.
     */
    private array $parameters;
}
