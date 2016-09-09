<?php

namespace BlueMvc\Core;

use BlueMvc\Core\Base\AbstractRequest;
use BlueMvc\Core\Http\Method;
use BlueMvc\Core\Interfaces\Http\MethodInterface;
use DataTypes\Interfaces\UrlInterface;
use DataTypes\Url;

/**
 * Class representing a web request.
 */
class Request extends AbstractRequest
{
    /**
     * Constructs the request.
     *
     * @param array|null $serverVars The server array or null to use the global $_SERVER array.
     */
    public function __construct(array $serverVars = null)
    {
        parent::__construct();

        $this->myServerVars = $serverVars !== null ? $serverVars : $_SERVER;
    }

    /**
     * @return UrlInterface The url.
     */
    public function getUrl()
    {
        if (parent::getUrl() === null) {
            // fixme: Use fromParts method for Url
            // fixme: Handle query string
            parent::setUrl(
                Url::parse('http' . (isset($this->myServerVars['HTTPS']) && $this->myServerVars['HTTPS'] !== '' ? 's' : '') . '://' . $this->myServerVars['HTTP_HOST'] . ':' . $this->myServerVars['SERVER_PORT'] . $this->myServerVars['REQUEST_URI'])
            );
        }

        return parent::getUrl();
    }

    /**
     * @return MethodInterface The http method.
     */
    public function getMethod()
    {
        if (parent::getMethod() === null) {
            parent::setMethod(new Method($this->myServerVars['REQUEST_METHOD']));
        }

        return parent::getMethod();
    }

    /**
     * @var array My server array.
     */
    private $myServerVars;
}
