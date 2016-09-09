<?php

namespace BlueMvc\Core\Interfaces;

use BlueMvc\Core\Interfaces\Http\MethodInterface;
use DataTypes\Interfaces\UrlInterface;

/**
 * Interface for Request class.
 */
interface RequestInterface
{
    /**
     * @return MethodInterface|null The http method.
     */
    public function getMethod();

    /**
     * @return UrlInterface|null The url.
     */
    public function getUrl();
}
