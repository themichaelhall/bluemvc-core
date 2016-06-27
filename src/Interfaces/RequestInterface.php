<?php

namespace BlueMvc\Core\Interfaces;

/**
 * Interface for Request class.
 */
interface RequestInterface
{
    /**
     * @return \DataTypes\Interfaces\UrlInterface The url.
     */
    public function getUrl();
}
