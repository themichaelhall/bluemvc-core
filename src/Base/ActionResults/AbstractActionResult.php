<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */
declare(strict_types=1);

namespace BlueMvc\Core\Base\ActionResults;

use BlueMvc\Core\Interfaces\ActionResults\ActionResultInterface;
use BlueMvc\Core\Interfaces\ApplicationInterface;
use BlueMvc\Core\Interfaces\Http\StatusCodeInterface;
use BlueMvc\Core\Interfaces\RequestInterface;
use BlueMvc\Core\Interfaces\ResponseInterface;

/**
 * Abstract class representing an action result.
 *
 * @since      1.0.0
 * @deprecated Use ActionResult class.
 * @see        \BlueMvc\Core\ActionResults\ActionResult
 */
abstract class AbstractActionResult implements ActionResultInterface
{
    /**
     * Constructs the action result.
     *
     * @since 1.0.0
     *
     * @param string              $content    The content.
     * @param StatusCodeInterface $statusCode The status code.
     */
    protected function __construct(string $content, StatusCodeInterface $statusCode)
    {
        $this->statusCode = $statusCode;
        $this->content = $content;
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
    public function updateResponse(ApplicationInterface $application, RequestInterface $request, ResponseInterface $response): void
    {
        $response->setStatusCode($this->statusCode);
        $response->setContent($this->content);
    }

    /**
     * @var StatusCodeInterface My status code.
     */
    private $statusCode;

    /**
     * @var string My content.
     */
    private $content;
}
