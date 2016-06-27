<?php

namespace BlueMvc\Core\Base;

use BlueMvc\Core\Interfaces\ApplicationInterface;

/**
 * Abstract class representing a BlueMvc main application.
 */
abstract class AbstractApplication implements ApplicationInterface
{
    /**
     * Constructs the application.
     *
     * @param string $documentRoot The document root.
     */
    public function __construct($documentRoot)
    {
        $this->myDocumentRoot = $documentRoot;
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
