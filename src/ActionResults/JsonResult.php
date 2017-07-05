<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\ActionResults;

use BlueMvc\Core\Base\ActionResults\AbstractActionResult;
use BlueMvc\Core\Exceptions\InvalidActionResultContentException;
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
     * @param mixed                    $content    The content.
     * @param StatusCodeInterface|null $statusCode The status code or null for status code 200 OK.
     *
     * @throws InvalidActionResultContentException If the content could not be json-encoded.
     */
    public function __construct($content, StatusCodeInterface $statusCode = null)
    {
        $json = json_encode($content);
        if ($json === false) {
            throw new InvalidActionResultContentException('Could not create JsonResult from content: ' . json_last_error_msg());
        }

        parent::__construct($json, $statusCode ?: new StatusCode(StatusCode::OK));
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
