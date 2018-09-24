<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */
declare(strict_types=1);

namespace BlueMvc\Core;

use BlueMvc\Core\Base\AbstractRequest;
use BlueMvc\Core\Collections\HeaderCollection;
use BlueMvc\Core\Collections\ParameterCollection;
use BlueMvc\Core\Collections\RequestCookieCollection;
use BlueMvc\Core\Collections\SessionItemCollection;
use BlueMvc\Core\Collections\UploadedFileCollection;
use BlueMvc\Core\Exceptions\ServerEnvironmentException;
use BlueMvc\Core\Http\Method;
use BlueMvc\Core\Interfaces\Collections\HeaderCollectionInterface;
use BlueMvc\Core\Interfaces\Collections\ParameterCollectionInterface;
use BlueMvc\Core\Interfaces\Collections\RequestCookieCollectionInterface;
use BlueMvc\Core\Interfaces\Collections\UploadedFileCollectionInterface;
use BlueMvc\Core\Interfaces\UploadedFileInterface;
use DataTypes\FilePath;
use DataTypes\Host;
use DataTypes\Interfaces\UrlInterface;
use DataTypes\IPAddress;
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
     */
    public function __construct()
    {
        parent::__construct(
            self::parseUrl($_SERVER),
            new Method($_SERVER['REQUEST_METHOD']),
            self::parseHeaders($_SERVER),
            self::parseParameters($_GET),
            self::parseParameters($_POST),
            self::parseUploadedFiles($_FILES),
            self::parseCookies($_COOKIE),
            new SessionItemCollection()
        );

        $clientIp = IPAddress::tryParse(isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');
        if ($clientIp !== null) {
            $this->setClientIp($clientIp);
        }

        $this->rawContentIsFetched = false;
    }

    /**
     * Returns the raw content from request.
     *
     * @since 1.0.0
     *
     * @return string The raw content from request.
     */
    public function getRawContent(): string
    {
        if (!$this->rawContentIsFetched) {
            $this->setRawContent(file_get_contents('php://input'));
            $this->rawContentIsFetched = true;
        }

        return parent::getRawContent();
    }

    /**
     * Parses an array with server variables into a url.
     *
     * @param array $serverVars The server variables.
     *
     * @return UrlInterface The url
     */
    private static function parseUrl(array $serverVars): UrlInterface
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
    private static function parseHeaders(array $serverVars): HeaderCollectionInterface
    {
        $headers = new HeaderCollection();
        $headersArray = self::getHeadersArray($serverVars);

        foreach ($headersArray as $name => $value) {
            $headers->add(strval($name), $value);
        }

        return $headers;
    }

    /**
     * Returns all headers as an array.
     *
     * @param array $serverVars The server array.
     *
     * @return array The headers as an array.
     */
    private static function getHeadersArray(array $serverVars): array
    {
        $headersArray = [];

        if (function_exists('getallheaders')) {
            /** @noinspection PhpComposerExtensionStubsInspection */
            return getallheaders();
        }

        foreach ($serverVars as $name => $value) {
            if (substr($name, 0, 5) === 'HTTP_') {
                $headersArray[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }

        return $headersArray;
    }

    /**
     * Parses an array with parameters into a parameter collection.
     *
     * @param array $parametersArray The parameters array.
     *
     * @return ParameterCollectionInterface The parameter collection.
     */
    private static function parseParameters(array $parametersArray): ParameterCollectionInterface
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
    private static function parseCookies(array $cookiesArray): RequestCookieCollectionInterface
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
     * @return UploadedFileCollectionInterface The uploaded files collection.
     */
    private static function parseUploadedFiles(array $filesArray): UploadedFileCollectionInterface
    {
        $uploadedFiles = new UploadedFileCollection();

        foreach ($filesArray as $uploadedFileName => $uploadedFileInfo) {
            $uploadedFile = self::parseUploadedFile($uploadedFileInfo);
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
    private static function parseUploadedFile(array $uploadedFileInfo): ?UploadedFileInterface
    {
        $error = is_array($uploadedFileInfo['error']) ? $uploadedFileInfo['error'][0] : $uploadedFileInfo['error'];
        $error = intval($error);

        if ($error !== 0) {
            if (isset(self::$fileUploadErrors[$error])) {
                throw new ServerEnvironmentException('File upload failed: ' . self::$fileUploadErrors[$error]);
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
    private static $fileUploadErrors = [
        UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder (UPLOAD_ERR_NO_TMP_DIR).',
        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk (UPLOAD_ERR_CANT_WRITE).',
        UPLOAD_ERR_EXTENSION  => 'File upload stopped by extension (UPLOAD_ERR_EXTENSION).',
    ];

    /**
     * @var bool True if raw content is fetched, false otherwise.
     */
    private $rawContentIsFetched;
}
