<?php

namespace BlueMvc\Core;

use BlueMvc\Core\Base\AbstractApplication;
use DataTypes\FilePath;

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
        parent::__construct(FilePath::parse($serverVars['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR));

        $this->myServerVars = $serverVars !== null ? $serverVars : $_SERVER;
    }

    /**
     * @var array My server array.
     */
    private $myServerVars;
}
