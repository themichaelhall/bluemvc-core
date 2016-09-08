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
     */
    public function __construct()
    {
        $this->myUrl = null;
    }

    /**
     * @return UrlInterface|null The url.
     */
    public function getUrl()
    {
        return $this->myUrl;
    }

    /**
     * Sets the url.
     *
     * @param UrlInterface $url The url.
     */
    protected function setUrl(UrlInterface $url)
    {
        $this->myUrl = $url;
    }

    /**
     * @var UrlInterface|null My url.
     */
    private $myUrl;
}
