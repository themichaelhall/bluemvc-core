<?php

namespace BlueMvc\Core;

use BlueMvc\Core\Base\AbstractRequest;
use BlueMvc\Core\Http\Method;
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
        $this->myServerVars = $serverVars !== null ? $serverVars : $_SERVER;

        // fixme: Use fromParts method for Url
        // fixme: Handle query string
        parent::__construct(Url::parse('http' . (isset($this->myServerVars['HTTPS']) && $this->myServerVars['HTTPS'] !== '' ? 's' : '') . '://' . $this->myServerVars['HTTP_HOST'] . ':' . $this->myServerVars['SERVER_PORT'] . $this->myServerVars['REQUEST_URI']), new Method($this->myServerVars['REQUEST_METHOD']));
    }

    /**
     * @var array My server array.
     */
    private $myServerVars;
}
