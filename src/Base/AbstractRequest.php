<?php

namespace BlueMvc\Core\Base;

use BlueMvc\Core\Interfaces\RequestInterface;
use DataTypes\Interfaces\UrlInterface;

/**
 * Abstract class representing a web request.
 */
abstract class AbstractRequest implements RequestInterface
{
    /**
     * Constructs the request.
     *
     * @param UrlInterface $url The url.
     */
    public function __construct(UrlInterface $url)
    {
        $this->myUrl = $url;
    }

    /**
     * @return UrlInterface The url.
     */
    public function getUrl()
    {
        return $this->myUrl;
    }

    /**
     * @var UrlInterface My url.
     */
    private $myUrl;
}
