<?php

namespace BlueMvc\Core;

use BlueMvc\Core\Base\AbstractApplication;

/**
 * BlueMvc main application.
 */
class Application extends AbstractApplication
{
    /**
     * Constructs the application.
     *
     * @param array $serverVars The $_SERVER array.
     */
    public function __construct(array $serverVars)
    {
        parent::__construct($serverVars['DOCUMENT_ROOT']);
    }
}
