<?php

/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

declare(strict_types=1);

namespace BlueMvc\Core\ActionResults;

use BlueMvc\Core\Interfaces\ActionResults\ActionResultInterface;
use BlueMvc\Core\Interfaces\ApplicationInterface;
use BlueMvc\Core\Interfaces\Http\StatusCodeInterface;
use BlueMvc\Core\Interfaces\RequestInterface;
use BlueMvc\Core\Interfaces\ResponseInterface;

/**
 * Class representing a generic action result.
 *
 * @since 2.1.0
 */
class ActionResult implements ActionResultInterface
{
    /**
     * Constructs the action result.
     *
     * @since 2.1.0
     *
     * @param string              $content    The content.
     * @param StatusCodeInterface $statusCode The status code.
     */
    public function __construct(string $content, StatusCodeInterface $statusCode)
    {
        $this->statusCode = $statusCode;
        $this->content = $content;
    }

    /**
     * Updates the response.
     *
     * @since 2.1.0
     *
     * @param ApplicationInterface $application The application.
     * @param RequestInterface     $request     The request.
     * @param ResponseInterface    $response    The response.
     */
    public function updateResponse(ApplicationInterface $application, RequestInterface $request, ResponseInterface $response): void
    {
        $response->setStatusCode($this->statusCode);
        $response->setContent($this->content);
    }

    /**
     * @var StatusCodeInterface The status code.
     */
    private StatusCodeInterface $statusCode;

    /**
     * @var string The content.
     */
    private string $content;
}
