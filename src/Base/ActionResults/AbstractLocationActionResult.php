<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\Base\ActionResults;

use BlueMvc\Core\Interfaces\ApplicationInterface;
use BlueMvc\Core\Interfaces\Http\StatusCodeInterface;
use BlueMvc\Core\Interfaces\RequestInterface;
use BlueMvc\Core\Interfaces\ResponseInterface;
use DataTypes\Url;

/**
 * Abstract class representing an action result with a location.
 *
 * @since 1.0.0
 */
class AbstractLocationActionResult extends AbstractActionResult
{
    /**
     * Constructs the action result.
     *
     * @since 1.0.0
     *
     * @param StatusCodeInterface $statusCode The status code.
     * @param string              $location   The location as an absolute or relative url.
     *
     * @throws \InvalidArgumentException If the location parameter is not a string.
     */
    public function __construct(StatusCodeInterface $statusCode, $location = '')
    {
        if (!is_string($location)) {
            throw new \InvalidArgumentException('$location parameter is not a string.');
        }

        parent::__construct('', $statusCode);

        $this->myLocation = $location;
    }

    /**
     * Updates the response.
     *
     * @since 1.0.0
     *
     * @param ApplicationInterface $application The application.
     * @param RequestInterface     $request     The request.
     * @param ResponseInterface    $response    The response.
     */
    public function updateResponse(ApplicationInterface $application, RequestInterface $request, ResponseInterface $response)
    {
        parent::updateResponse($application, $request, $response);

        $locationUrl = Url::parseRelative($this->myLocation, $request->getUrl());
        $response->setHeader('Location', $locationUrl->__toString());
    }

    /**
     * @var string My url.
     */
    private $myLocation;
}
