<?php

/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

declare(strict_types=1);

namespace BlueMvc\Core\ActionResults;

use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Interfaces\ApplicationInterface;
use BlueMvc\Core\Interfaces\RequestInterface;
use BlueMvc\Core\Interfaces\ResponseInterface;

/**
 * Class representing a 401 Unauthorized action result.
 *
 * @since 2.1.0
 */
class UnauthorizedResult extends ActionResult
{
    /**
     * Constructs the action result.
     *
     * @since 2.1.0
     *
     * @param string $wwwAuthenticate The WWW-Authenticate header.
     */
    public function __construct(string $wwwAuthenticate = 'Basic')
    {
        parent::__construct('', new StatusCode(StatusCode::UNAUTHORIZED));

        $this->wwwAuthenticate = $wwwAuthenticate;
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
        parent::updateResponse($application, $request, $response);

        $response->setHeader('WWW-Authenticate', $this->wwwAuthenticate);
    }

    /**
     * @var string My WWW-Authenticate header.
     */
    private $wwwAuthenticate;
}
