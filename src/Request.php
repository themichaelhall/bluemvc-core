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
use BlueMvc\Core\Collections\RequestCookieCollection;
use BlueMvc\Core\Collections\UploadedFileCollection;
use BlueMvc\Core\Exceptions\ServerEnvironmentException;
use BlueMvc\Core\Http\Method;
use BlueMvc\Core\Interfaces\Collections\HeaderCollectionInterface;
use BlueMvc\Core\Interfaces\Collections\RequestCookieCollectionInterface;
use BlueMvc\Core\Interfaces\UploadedFileInterface;
use DataTypes\FilePath;
use DataTypes\Host;
use DataTypes\Interfaces\UrlInterface;
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
     * @param array|null $serverVars  The server array or null to use the global $_SERVER array.
     * @param array|null $getVars     The get array or null to use the global $_GET array.
     * @param array|null $postVars    The post array or null to use the global $_POST array.
     * @param array|null $filesVars   The file array or null to use the global $_FILES array.
     * @param array|null $cookiesVars The cookie array or null to use the global $_COOKIES array.
     */
    public function __construct(array $serverVars = null, array $getVars = null, array $postVars = null, array $filesVars = null, array $cookiesVars = null)
    {
        $serverVars = $serverVars ?: $_SERVER;
        $getVars = $getVars ?: $_GET;
        $postVars = $postVars ?: $_POST;
        $filesVars = $filesVars ?: $_FILES;
        $cookiesVars = $cookiesVars ?: $_COOKIE;

        parent::__construct(
            self::myParseUrl($serverVars),
            new Method($serverVars['REQUEST_METHOD']),
            self::myParseHeaders($serverVars),
            self::myParseParameters($getVars),
            self::myParseParameters($postVars),
            self::myParseUploadedFiles($filesVars),
            self::myParseCookies($cookiesVars)
        );
    }

    /**
     * Parses an array with server variables into a url.
     *
     * @param array $serverVars The server variables.
     *
     * @return UrlInterface The url
     */
    private static function myParseUrl(array $serverVars)
    {
        $uriAndQueryString = explode('?', $serverVars['REQUEST_URI'], 2);
        $hostAndPort = explode(':', $serverVars['HTTP_HOST'], 2);

        return Url::fromParts(
            Scheme::parse('http' . (isset($serverVars['HTTPS']) && $serverVars['HTTPS'] !== '' ? 's' : '')),
            Host::parse($hostAndPort[0]),
            count($hostAndPort) > 1 ? intval($hostAndPort[1]) : null,
            UrlPath::parse($uriAndQueryString[0]),
            count($uriAndQueryString) > 1 ? $uriAndQueryString[1] : null
        );
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
            $parameters->set(
                strval($parameterName),
                strval(is_array($parameterValue) ? $parameterValue[0] : $parameterValue)
            );
        }

        return $parameters;
    }

    /**
     * Parses an array with cookies into a cookie collection.
     *
     * @param array $cookiesArray The cookies array.
     *
     * @return RequestCookieCollectionInterface The cookie collection.
     */
    private static function myParseCookies(array $cookiesArray)
    {
        $cookies = new RequestCookieCollection();

        foreach ($cookiesArray as $cookieName => $cookieValue) {
            $cookies->set(
                strval($cookieName),
                new RequestCookie(strval($cookieValue))
            );
        }

        return $cookies;
    }

    /**
     * Parses an array with files info an uploaded files collection.
     *
     * @param array $filesArray The files array.
     *
     * @throws ServerEnvironmentException If file upload failed due to server error or misconfiguration.
     *
     * @return UploadedFileCollection The uploaded files collection.
     */
    private static function myParseUploadedFiles(array $filesArray)
    {
        $uploadedFiles = new UploadedFileCollection();

        foreach ($filesArray as $uploadedFileName => $uploadedFileInfo) {
            $uploadedFile = self::myParseUploadedFile($uploadedFileInfo);
            if ($uploadedFile === null) {
                continue;
            }

            $uploadedFiles->set(strval($uploadedFileName), $uploadedFile);
        }

        return $uploadedFiles;
    }

    /**
     * Parses an array with file info into an uploaded file.
     *
     * @param array $uploadedFileInfo The file info.
     *
     * @throws ServerEnvironmentException If file upload failed due to server error or misconfiguration.
     *
     * @return UploadedFileInterface|null The uploaded file or null if parsing was not successful.
     */
    private static function myParseUploadedFile(array $uploadedFileInfo)
    {
        $error = is_array($uploadedFileInfo['error']) ? $uploadedFileInfo['error'][0] : $uploadedFileInfo['error'];
        $error = intval($error);

        if ($error !== 0) {
            if (isset(self::$myFileUploadErrors[$error])) {
                throw new ServerEnvironmentException('File upload failed: ' . self::$myFileUploadErrors[$error]);
            }

            return null;
        }

        $path = is_array($uploadedFileInfo['tmp_name']) ? $uploadedFileInfo['tmp_name'][0] : $uploadedFileInfo['tmp_name'];
        if (!is_uploaded_file($path)) {
            return null;
        }

        $originalName = is_array($uploadedFileInfo['name']) ? $uploadedFileInfo['name'][0] : $uploadedFileInfo['name'];
        $size = is_array($uploadedFileInfo['size']) ? $uploadedFileInfo['size'][0] : $uploadedFileInfo['size'];

        $result = new UploadedFile(
            FilePath::parse(strval($path)),
            strval($originalName),
            intval($size)
        );

        return $result;
    }

    /**
     * @var array My file upload errors that should result in an exception.
     */
    private static $myFileUploadErrors = [
        UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder (UPLOAD_ERR_NO_TMP_DIR).',
        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk (UPLOAD_ERR_CANT_WRITE).',
        UPLOAD_ERR_EXTENSION  => 'File upload stopped by extension (UPLOAD_ERR_EXTENSION).',
    ];
}
