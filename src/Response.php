<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core;

use BlueMvc\Core\Base\AbstractResponse;

/**
 * Class representing a web response.
 *
 * @since 1.0.0
 */
class Response extends AbstractResponse
{
    /**
     * Constructs a response.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Outputs the content.
     *
     * @since 1.0.0
     */
    public function output()
    {
        header('HTTP/1.1 ' . $this->getStatusCode());

        // Output headers.
        foreach ($this->getHeaders() as $headerName => $headerValue) {
            header($headerName . ': ' . $headerValue);
        }

        // Output content.
        echo $this->getContent();
    }
}
