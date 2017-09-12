<?php

namespace BlueMvc\Core\Tests\Helpers\TestResponses;

use BlueMvc\Core\Base\AbstractResponse;
use BlueMvc\Core\Interfaces\RequestInterface;

/**
 * Basic test response class.
 */
class BasicTestResponse extends AbstractResponse
{
    /**
     * Constructs the test response.
     *
     * @param RequestInterface $request
     */
    public function __construct(RequestInterface $request)
    {
        parent::__construct($request);
    }

    /**
     * Does nothing.
     */
    public function output()
    {
    }
}
