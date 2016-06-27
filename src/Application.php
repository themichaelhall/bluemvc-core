<?php

namespace BlueMvc\Core;

use BlueMvc\Core\Interfaces\ApplicationInterface;

/**
 * BlueMvc main application.
 */
class Application implements ApplicationInterface
{
    /**
     * Constructs the application.
     *
     * @param array $serverVars The $_SERVER array.
     */
    public function __construct(array $serverVars)
    {
        $this->myDocumentRoot = $serverVars['DOCUMENT_ROOT'];
    }

    /**
     * @return string The document root.
     */
    public function getDocumentRoot()
    {
        return $this->myDocumentRoot;
    }

    /**
     * @var string My document root.
     */
    private $myDocumentRoot;
}
