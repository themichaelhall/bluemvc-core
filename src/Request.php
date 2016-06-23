<?php

namespace BlueMvc\Core;

use DataTypes\Url;

/**
 * Class representing a web request.
 */
class Request
{
    /**
     * Constructs the request.
     *
     * @param array $serverVars The $_SERVER array.
     */
    public function __construct(array $serverVars)
    {
        $this->myUrl = static::createUrl($serverVars);
    }

    /**
     * @return \DataTypes\Interfaces\UrlInterface The url.
     */
    public function getUrl()
    {
        return $this->myUrl;
    }

    /**
     * Creates a url from $_SERVER array.
     *
     * @param array $serverVars The $_SERVER array.
     *
     * @return \DataTypes\Interfaces\UrlInterface The url.
     */
    private static function createUrl(array $serverVars)
    {
        // fixme: Use fromParts method for Url
        // fixme: Handle query string
        return Url::parse('http' . (isset($serverVars['HTTPS']) && $serverVars['HTTPS'] !== '' ? 's' : '') . '://' . $serverVars['HTTP_HOST'] . ':' . $serverVars['SERVER_PORT'] .
            $serverVars['REQUEST_URI']);
    }

    /**
     * @var \DataTypes\Interfaces\UrlInterface My url.
     */
    private $myUrl;
}
