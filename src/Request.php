<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core;

use BlueMvc\Core\Base\AbstractRequest;
use BlueMvc\Core\Collections\HeaderCollection;
use BlueMvc\Core\Collections\ParameterCollection;
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
     * @param array|null $getVars    The get array or null to use the global $_GET array.
     * @param array|null $postVars   The post array or null to use the global $_POST array.
     */
    public function __construct(array $serverVars = null, array $getVars = null, array $postVars = null)
    {
        $serverVars = $serverVars ?: $_SERVER;
        $postVars = $postVars ?: $_POST;

        $uriAndQueryString = explode('?', $serverVars['REQUEST_URI'], 2);
        $hostAndPort = explode(':', $serverVars['HTTP_HOST'], 2);

        parent::__construct(
            Url::fromParts(
                Scheme::parse('http' . (isset($serverVars['HTTPS']) && $serverVars['HTTPS'] !== '' ? 's' : '')),
                Host::parse($hostAndPort[0]),
                count($hostAndPort) > 1 ? intval($hostAndPort[1]) : null,
                UrlPath::parse($uriAndQueryString[0]),
                count($uriAndQueryString) > 1 ? $uriAndQueryString[1] : null
            ), new Method($serverVars['REQUEST_METHOD'])
        );

        $this->setHeaders(self::myParseHeaders($serverVars));
        $this->setFormParameters(self::myParseParameters($postVars));
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
     * Parses an array with parameters into a parameter collection.
     *
     * @param array $parametersArray The parameters array.
     *
     * @return ParameterCollection The parameter collection.
     */
    private static function myParseParameters(array $parametersArray)
    {
        $parameters = new ParameterCollection();
        foreach ($parametersArray as $parameterName => $parameterValue) {
            $parameters->set($parameterName, is_array($parameterValue) ? $parameterValue[0] : $parameterValue);
        }

        return $parameters;
    }
}
