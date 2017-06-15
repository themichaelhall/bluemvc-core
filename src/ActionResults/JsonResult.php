<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\ActionResults;

use BlueMvc\Core\Base\ActionResults\AbstractActionResult;
use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Interfaces\ApplicationInterface;
use BlueMvc\Core\Interfaces\Http\StatusCodeInterface;
use BlueMvc\Core\Interfaces\RequestInterface;
use BlueMvc\Core\Interfaces\ResponseInterface;

/**
 * Class representing a JSON action result.
 *
 * @since 1.0.0
 */
class JsonResult extends AbstractActionResult
{
    /**
     * Constructs the action result.
     *
     * @since 1.0.0
     *
     * @param array                    $content    The content.
     * @param StatusCodeInterface|null $statusCode The status code or null for status code 200 OK.
     */
    public function __construct(array $content, StatusCodeInterface $statusCode = null)
    {
        parent::__construct(json_encode($content), $statusCode ?: new StatusCode(StatusCode::OK));
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

        $response->setHeader('Content-Type', 'application/json');
    }
}
