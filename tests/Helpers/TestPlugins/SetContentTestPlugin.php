<?php

namespace BlueMvc\Core\Tests\Helpers\TestPlugins;

use BlueMvc\Core\Base\AbstractPlugin;
use BlueMvc\Core\Interfaces\ApplicationInterface;
use BlueMvc\Core\Interfaces\RequestInterface;
use BlueMvc\Core\Interfaces\ResponseInterface;

/**
 * Test plugin that sets content.
 */
class SetContentTestPlugin extends AbstractPlugin
{
    /**
     * SetContentTestPlugin constructor.
     *
     * @param bool $stopAfterPreRequest  If true, request should stop processing after pre-request, false otherwise.
     * @param bool $stopAfterPostRequest If true, request should stop processing after post-request, false otherwise.
     */
    public function __construct($stopAfterPreRequest, $stopAfterPostRequest)
    {
        $this->myStopAfterPreRequest = $stopAfterPreRequest;
        $this->myStopAfterPostRequest = $stopAfterPostRequest;
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
    public function onPreRequest(ApplicationInterface $application, RequestInterface $request, ResponseInterface $response)
    {
        parent::onPreRequest($application, $request, $response);

        $response->setContent($response->getContent() . 'onPreRequest');

        return $this->myStopAfterPreRequest;
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
    public function onPostRequest(ApplicationInterface $application, RequestInterface $request, ResponseInterface $response)
    {
        parent::onPostRequest($application, $request, $response);

        $response->setContent($response->getContent() . 'onPostRequest');

        return $this->myStopAfterPostRequest;
    }

    /**
     * @var bool If true, request should stop processing after pre-request, false otherwise.
     */
    private $myStopAfterPreRequest;

    /**
     * @var bool If true, request should stop processing after post-request, false otherwise.
     */
    private $myStopAfterPostRequest;
}
