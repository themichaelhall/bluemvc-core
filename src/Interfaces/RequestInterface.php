<?php

namespace BlueMvc\Core\Interfaces;

use DataTypes\Interfaces\UrlInterface;

/**
 * Interface for Request class.
 */
interface RequestInterface
{
    /**
     * @return UrlInterface|null The url.
     */
    public function getUrl();
}
