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
        // fixme: Use fromParts method for Url
        // fixme: Handle query string
        parent::__construct(Url::parse('http' . (isset($serverVars['HTTPS']) && $serverVars['HTTPS'] !== '' ? 's' : '') . '://' . $serverVars['HTTP_HOST'] . ':' . $serverVars['SERVER_PORT'] . $serverVars['REQUEST_URI']), new Method($serverVars['REQUEST_METHOD']));

        $this->myServerVars = $serverVars !== null ? $serverVars : $_SERVER;
    }

    /**
     * @var array My server array.
     */
    private $myServerVars;
}
