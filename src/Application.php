<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

namespace BlueMvc\Core;

use BlueMvc\Core\Base\AbstractApplication;
use BlueMvc\Core\Collections\SessionItemCollection;
use DataTypes\FilePath;

/**
 * Class representing a BlueMvc main application.
 *
 * @since 1.0.0
 */
class Application extends AbstractApplication
{
    /**
     * Constructs the application.
     *
     * @since 1.0.0
     *
     * @param array|null $serverVars The server array or null to use the global $_SERVER array.
     */
    public function __construct(array $serverVars = null)
    {
        $serverVars = $serverVars !== null ? $serverVars : $_SERVER;
        parent::__construct(FilePath::parse($serverVars['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR), new SessionItemCollection());

        $this->setDebug(isset($serverVars['BLUEMVC_DEBUG']));
    }
}
