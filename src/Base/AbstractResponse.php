<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\Base;

use BlueMvc\Core\Collections\HeaderCollection;
use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Interfaces\Collections\HeaderCollectionInterface;
use BlueMvc\Core\Interfaces\Http\StatusCodeInterface;
use BlueMvc\Core\Interfaces\RequestInterface;
use BlueMvc\Core\Interfaces\ResponseInterface;

/**
 * Abstract class representing a web response.
 *
 * @since 1.0.0
 */
abstract class AbstractResponse implements ResponseInterface
{
    /**
     * Adds a header.
     *
     * @since 1.0.0
     *
     * @param string $name  The header name.
     * @param string $value The header value.
     *
     * @throws \InvalidArgumentException If any of the parameters are of invalid type.
     */
    public function addHeader($name, $value)
    {
        $this->myHeaders->add($name, $value);
    }

    /**
     * Returns the content.
     *
     * @since 1.0.0
     *
     * @return string The content.
     */
    public function getContent()
    {
        return $this->myContent;
    }

    /**
     * Returns a header value by header name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The header name.
     *
     * @throws \InvalidArgumentException If the $name parameter is not a string.
     *
     * @return string|null The header value by header name if it exists, null otherwise.
     */
    public function getHeader($name)
    {
        return $this->myHeaders->get($name);
    }

    /**
     * Returns the headers.
     *
     * @since 1.0.0
     *
     * @return HeaderCollectionInterface The headers.
     */
    public function getHeaders()
    {
        return $this->myHeaders;
    }

    /**
     * Returns the request.
     *
     * @since 1.0.0
     *
     * @return RequestInterface The request.
     */
    public function getRequest()
    {
        return $this->myRequest;
    }

    /**
     * Returns the status code.
     *
     * @since 1.0.0
     *
     * @return StatusCodeInterface The status code.
     */
    public function getStatusCode()
    {
        return $this->myStatusCode;
    }

    /**
     * Sets the content.
     *
     * @since 1.0.0
     *
     * @param string $content The content.
     *
     * @throws \InvalidArgumentException If the $content parameter is not a string.
     */
    public function setContent($content)
    {
        if (!is_string($content)) {
            throw new \InvalidArgumentException('$content parameter is not a string.');
        }

        $this->myContent = $content;
    }

    /**
     * Sets the expiry time.
     *
     * @since 1.0.0
     *
     * @param \DateTimeImmutable|null $expiry The expiry time or null for immediate expiry.
     */
    public function setExpiry(\DateTimeImmutable $expiry = null)
    {
        $date = new \DateTimeImmutable();
        $expiry = $expiry ?: $date;

        $this->setHeader('Date', $date->setTimeZone(new \DateTimeZone('UTC'))->format('D, d M Y H:i:s \G\M\T'));
        $this->setHeader('Expires', $expiry->setTimeZone(new \DateTimeZone('UTC'))->format('D, d M Y H:i:s \G\M\T'));

        if ($expiry <= $date) {
            $this->setHeader('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0');

            return;
        }

        $maxAge = $expiry->getTimestamp() - $date->getTimestamp();
        $this->setHeader('Cache-Control', 'public, max-age=' . $maxAge);
    }

    /**
     * Sets a header.
     *
     * @since 1.0.0
     *
     * @param string $name  The header name.
     * @param string $value The header value.
     *
     * @throws \InvalidArgumentException If any of the parameters are of invalid type.
     */
    public function setHeader($name, $value)
    {
        $this->myHeaders->set($name, $value);
    }

    /**
     * Sets the headers.
     *
     * @since 1.0.0
     *
     * @param HeaderCollectionInterface $headers The headers.
     */
    public function setHeaders(HeaderCollectionInterface $headers)
    {
        $this->myHeaders = $headers;
    }

    /**
     * Sets the status code.
     *
     * @since 1.0.0
     *
     * @param StatusCodeInterface $statusCode The status code.
     */
    public function setStatusCode(StatusCodeInterface $statusCode)
    {
        $this->myStatusCode = $statusCode;
    }

    /**
     * Outputs the content.
     *
     * @since 1.0.0
     */
    public abstract function output();

    /**
     * Constructs a response.
     *
     * @since 1.0.0
     *
     * @param RequestInterface $request The request.
     */
    protected function __construct(RequestInterface $request)
    {
        $this->myContent = '';
        $this->myHeaders = new HeaderCollection();
        $this->myRequest = $request;
        $this->myStatusCode = new StatusCode(StatusCode::OK);
    }

    /**
     * @var string My content.
     */
    private $myContent;

    /**
     * @var HeaderCollection My headers.
     */
    private $myHeaders;

    /**
     * @var RequestInterface My request.
     */
    private $myRequest;

    /**
     * @var StatusCodeInterface My status code.
     */
    private $myStatusCode;
}
