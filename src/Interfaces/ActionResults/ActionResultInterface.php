<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */
declare(strict_types=1);

namespace BlueMvc\Core\Interfaces\ActionResults;

use BlueMvc\Core\Interfaces\ApplicationInterface;
use BlueMvc\Core\Interfaces\RequestInterface;
use BlueMvc\Core\Interfaces\ResponseInterface;
use BlueMvc\Core\Interfaces\ViewOrActionResultInterface;

/**
 * Interface for ActionResult class.
 *
 * @since 1.0.0
 */
interface ActionResultInterface extends ViewOrActionResultInterface
{
    /**
     * Updates the response.
     *
     * @since 1.0.0
     *
     * @param ApplicationInterface $application The application.
     * @param RequestInterface     $request     The request.
     * @param ResponseInterface    $response    The response.
     */
    public function updateResponse(ApplicationInterface $application, RequestInterface $request, ResponseInterface $response): void;
}
