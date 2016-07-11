<?php

namespace BlueMvc\Core;

use BlueMvc\Core\Exceptions\RouteInvalidArgumentException;

/**
 * Class representing a route.
 */
class Route
{
    /**
     * Constructs a route.
     *
     * @param string $path The path.
     *
     * @throws RouteInvalidArgumentException If the $path parameter is invalid.
     */
    public function __construct($path)
    {
        assert(is_string($path));

        // Validate path.
        $this->myValidatePath($path);

        $this->myPath = $path;
    }

    /**
     * @return string The path.
     */
    public function getPath()
    {
        return $this->myPath;
    }

    /**
     * Validates the path.
     *
     * @param string $path The path.
     *
     * @throws RouteInvalidArgumentException If the $path parameter is invalid.
     */
    private function myValidatePath($path)
    {
        if (preg_match('/[^a-zA-Z0-9._-]/', $path, $matches)) {
            throw new RouteInvalidArgumentException('Path "' . $path . '" contains invalid character "' . $matches[0] . '".');
        }
    }

    /**
     * @var string My path.
     */
    private $myPath;
}
