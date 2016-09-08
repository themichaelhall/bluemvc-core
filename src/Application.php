<?php

namespace BlueMvc\Core;

use BlueMvc\Core\Base\AbstractApplication;
use DataTypes\FilePath;
use DataTypes\Interfaces\FilePathInterface;

/**
 * BlueMvc main application.
 */
class Application extends AbstractApplication
{
    /**
     * Constructs the application.
     *
     * @param array|null $serverVars The server array or null to use the global $_SERVER array.
     */
    public function __construct(array $serverVars = null)
    {
        parent::__construct();

        $this->myServerVars = $serverVars !== null ? $serverVars : $_SERVER;
    }

    /**
     * @return FilePathInterface The document root.
     */
    public function getDocumentRoot()
    {
        if (parent::getDocumentRoot() === null) {
            parent::setDocumentRoot(FilePath::parse($this->myServerVars['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR));
        }

        return parent::getDocumentRoot();
    }

    /**
     * @var array My server array.
     */
    private $myServerVars;
}
