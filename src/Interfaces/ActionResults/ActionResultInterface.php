<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */
namespace BlueMvc\Core\Interfaces\ActionResults;

use BlueMvc\Core\Interfaces\ResponseInterface;

/**
 * Interface for ActionResult class.
 *
 * @since 1.0.0
 */
interface ActionResultInterface
{
    /**
     * Updates the response.
     *
     * @since 1.0.0
     *
     * @param ResponseInterface $response The response.
     */
    public function updateResponse(ResponseInterface $response);
}
