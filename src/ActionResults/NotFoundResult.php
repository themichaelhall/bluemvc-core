<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */
namespace BlueMvc\Core\ActionResults;

use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Interfaces\ActionResults\ActionResultInterface;
use BlueMvc\Core\Interfaces\ResponseInterface;

/**
 * Class representing a 404 Not Found action result.
 *
 * @since 1.0.0
 */
class NotFoundResult implements ActionResultInterface // fixme: Abstract base class
{
    /**
     * Constructs the action result.
     *
     * @since 1.0.0
     *
     * @param string $content The content.
     *
     * @throws \InvalidArgumentException If the content parameter is not a string.
     */
    public function __construct($content = '')
    {
        if (!is_string($content)) {
            throw new \InvalidArgumentException('$content parameter is not a string.');
        }

        $this->myContent = $content;
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
        $response->setStatusCode(new StatusCode(StatusCode::NOT_FOUND));
        $response->setContent($this->myContent);
    }

    /**
     * @var string My content.
     */
    private $myContent;
}
