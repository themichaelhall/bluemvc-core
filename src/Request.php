<?php

namespace BlueMvc\Core;

use BlueMvc\Core\Base\AbstractRequest;
use DataTypes\Url;

/**
 * Class representing a web request.
 */
class Request extends AbstractRequest
{
    /**
     * Constructs the request.
     *
     * @param array $serverVars The $_SERVER array.
     */
    public function __construct(array $serverVars)
    {
        parent::__construct(static::createUrl($serverVars));
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
}
