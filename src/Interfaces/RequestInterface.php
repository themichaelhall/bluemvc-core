<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */
namespace BlueMvc\Core\Interfaces;

use BlueMvc\Core\Interfaces\Http\MethodInterface;
use DataTypes\Interfaces\UrlInterface;

/**
 * Interface for Request class.
 *
 * @since 1.0.0
 */
interface RequestInterface
{
    /**
     * Returns the http method or null if no http method is set.
     *
     * @since 1.0.0
     *
     * @return MethodInterface|null The http method or null if no http method is set.
     */
    public function getMethod();

    /**
     * Returns the url or null of no url is set.
     *
     * @since 1.0.0
     *
     * @return UrlInterface|null The url or null of no url is set.
     */
    public function getUrl();
}
