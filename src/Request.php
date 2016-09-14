<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */
namespace BlueMvc\Core;

use BlueMvc\Core\Base\AbstractRequest;
use BlueMvc\Core\Http\Method;
use DataTypes\Host;
use DataTypes\Scheme;
use DataTypes\Url;
use DataTypes\UrlPath;

/**
 * Class representing a web request.
 *
 * @since 1.0.0
 */
class Request extends AbstractRequest
{
    /**
     * Constructs the request.
     *
     * @since 1.0.0
     *
     * @param array|null $serverVars The server array or null to use the global $_SERVER array.
     */
    public function __construct(array $serverVars = null)
    {
        $this->myServerVars = $serverVars !== null ? $serverVars : $_SERVER;

        parent::__construct(
            Url::fromParts(
                Scheme::parse('http' . (isset($this->myServerVars['HTTPS']) && $this->myServerVars['HTTPS'] !== '' ? 's' : '')),
                Host::parse($this->myServerVars['HTTP_HOST']),
                intval($this->myServerVars['SERVER_PORT']),
                UrlPath::parse($this->myServerVars['REQUEST_URI']),
                isset($this->myServerVars['QUERY_STRING']) ? $this->myServerVars['QUERY_STRING'] : null),
            new Method($this->myServerVars['REQUEST_METHOD'])
        );
    }

    /**
     * @var array My server array.
     */
    private $myServerVars;
}
