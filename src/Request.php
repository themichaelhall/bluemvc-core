<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core;

use BlueMvc\Core\Base\AbstractRequest;
use BlueMvc\Core\Collections\HeaderCollection;
use BlueMvc\Core\Http\Method;
use BlueMvc\Core\Interfaces\Collections\HeaderCollectionInterface;
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

        $uriAndQueryString = explode('?', $this->myServerVars['REQUEST_URI'], 2);
        $hostAndPort = explode(':', $this->myServerVars['HTTP_HOST'], 2);

        parent::__construct(
            Url::fromParts(
                Scheme::parse('http' . (isset($this->myServerVars['HTTPS']) && $this->myServerVars['HTTPS'] !== '' ? 's' : '')),
                Host::parse($hostAndPort[0]),
                count($hostAndPort) > 1 ? intval($hostAndPort[1]) : null,
                UrlPath::parse($uriAndQueryString[0]),
                count($uriAndQueryString) > 1 ? $uriAndQueryString[1] : null
            ), new Method($this->myServerVars['REQUEST_METHOD'])
        );

        $this->setHeaders(self::myParseHeaders($this->myServerVars));
    }

    /**
     * Parses an array with headers into a header collection.
     *
     * @param array $serverVars The server array.
     *
     * @return HeaderCollectionInterface The header collection.
     */
    private static function myParseHeaders(array $serverVars)
    {
        $headers = new HeaderCollection();

        foreach ($serverVars as $name => $value) {
            if (substr($name, 0, 5) === 'HTTP_') {
                $headers->add(str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5))))), $value);
            }
        }

        return $headers;
    }

    /**
     * @var array My server array.
     */
    private $myServerVars;
}
