<?php

namespace BlueMvc\Core;

/**
 * Class representing a route.
 */
class Route
{
    /**
     * Constructs a route.
     *
     * @param string $path The path.
     */
    public function __construct($path)
    {
        assert(is_string($path));

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
     * @var string My path.
     */
    private $myPath;
}
