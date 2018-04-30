<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Helpers\TestPlugins;

use BlueMvc\Core\Base\AbstractPlugin;
use BlueMvc\Core\Interfaces\ApplicationInterface;
use BlueMvc\Core\Interfaces\RequestInterface;
use BlueMvc\Core\Interfaces\ResponseInterface;

/**
 * Test plugin that sets headers.
 */
class SetHeaderTestPlugin extends AbstractPlugin
{
    /**
     * SetHeaderTestPlugin constructor.
     *
     * @param bool $stopAfterPreRequest  If true, request should stop processing after pre-request, false otherwise.
     * @param bool $stopAfterPostRequest If true, request should stop processing after post-request, false otherwise.
     */
    public function __construct(bool $stopAfterPreRequest, bool $stopAfterPostRequest)
    {
        $this->stopAfterPreRequest = $stopAfterPreRequest;
        $this->stopAfterPostRequest = $stopAfterPostRequest;
    }

    /**
     * Called before a request is processed.
     *
     * @param ApplicationInterface $application The application.
     * @param RequestInterface     $request     The request.
     * @param ResponseInterface    $response    The response.
     *
     * @return bool True if request should stop processing, false otherwise.
     */
    public function onPreRequest(ApplicationInterface $application, RequestInterface $request, ResponseInterface $response): bool
    {
        parent::onPreRequest($application, $request, $response);

        $response->setHeader('X-PluginOnPreRequest', '1');

        return $this->stopAfterPreRequest;
    }

    /**
     * Called after a request is processed.
     *
     * @param ApplicationInterface $application The application.
     * @param RequestInterface     $request     The request.
     * @param ResponseInterface    $response    The response.
     *
     * @return bool True if request should stop processing, false otherwise.
     */
    public function onPostRequest(ApplicationInterface $application, RequestInterface $request, ResponseInterface $response): bool
    {
        parent::onPostRequest($application, $request, $response);

        $response->setHeader('X-PluginOnPostRequest', '1');

        return $this->stopAfterPostRequest;
    }

    /**
     * @var bool If true, request should stop processing after pre-request, false otherwise.
     */
    private $stopAfterPreRequest;

    /**
     * @var bool If true, request should stop processing after post-request, false otherwise.
     */
    private $stopAfterPostRequest;
}
