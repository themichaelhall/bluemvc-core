<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\Base\ActionResults;

use BlueMvc\Core\Interfaces\Http\StatusCodeInterface;
use BlueMvc\Core\Interfaces\ResponseInterface;
use DataTypes\Url;

/**
 * Abstract class representing a redirect action result.
 *
 * @since 1.0.0
 */
class AbstractRedirectActionResult extends AbstractActionResult
{
    /**
     * Constructs the action result.
     *
     * @since 1.0.0
     *
     * @param StatusCodeInterface $statusCode The status code.
     * @param string              $url        The url as an absolute or relative url.
     *
     * @throws \InvalidArgumentException If the url parameter is not a string.
     */
    public function __construct(StatusCodeInterface $statusCode, $url = '')
    {
        if (!is_string($url)) {
            throw new \InvalidArgumentException('$url parameter is not a string.');
        }

        parent::__construct($statusCode);

        $this->myUrl = $url;
    }

    /**
     * Updates the response.
     *
     * @since 1.0.0
     *
     * @param ResponseInterface $response The response.
     */
    public function updateResponse(ResponseInterface $response)
    {
        parent::updateResponse($response);

        $redirectUrl = Url::parseRelative($this->myUrl, $response->getRequest()->getUrl());
        $response->setHeader('Location', $redirectUrl->__toString());
    }

    /**
     * @var string My url.
     */
    private $myUrl;
}
