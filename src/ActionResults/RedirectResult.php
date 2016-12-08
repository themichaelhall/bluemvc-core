<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\ActionResults;

use BlueMvc\Core\Base\ActionResults\AbstractActionResult;
use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Interfaces\ResponseInterface;
use DataTypes\Url;

/**
 * Class representing a 302 Found action result.
 *
 * @since 1.0.0
 */
class RedirectResult extends AbstractActionResult
{
    /**
     * Constructs the action result.
     *
     * @since 1.0.0
     *
     * @param string $url The url as an absolute or relative url.
     *
     * @throws \InvalidArgumentException If the url parameter is not a string.
     */
    public function __construct($url = '')
    {
        if (!is_string($url)) {
            throw new \InvalidArgumentException('$url parameter is not a string.');
        }

        parent::__construct(new StatusCode(StatusCode::FOUND));

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
