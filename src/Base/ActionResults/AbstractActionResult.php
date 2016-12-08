<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core\Base\ActionResults;

use BlueMvc\Core\Interfaces\ActionResults\ActionResultInterface;
use BlueMvc\Core\Interfaces\Http\StatusCodeInterface;
use BlueMvc\Core\Interfaces\ResponseInterface;

/**
 * Abstract class representing an action result.
 *
 * @since 1.0.0
 */
abstract class AbstractActionResult implements ActionResultInterface
{
    /**
     * Constructs the action result.
     *
     * @since 1.0.0
     *
     * @param StatusCodeInterface $statusCode The status code.
     * @param string              $content    The content.
     *
     * @throws \InvalidArgumentException If the content parameter is not a string.
     */
    protected function __construct(StatusCodeInterface $statusCode, $content = '')
    {
        if (!is_string($content)) {
            throw new \InvalidArgumentException('$content parameter is not a string.');
        }

        $this->myStatusCode = $statusCode;
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
        $response->setStatusCode($this->myStatusCode);
        $response->setContent($this->myContent);
    }

    /**
     * @var StatusCodeInterface My status code.
     */
    private $myStatusCode;

    /**
     * @var string My content.
     */
    private $myContent;
}
