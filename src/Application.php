<?php

/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

declare(strict_types=1);

namespace BlueMvc\Core;

use BlueMvc\Core\Base\AbstractApplication;
use DataTypes\System\FilePath;

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
     */
    public function __construct()
    {
        parent::__construct(
            FilePath::parseAsDirectory($_SERVER['DOCUMENT_ROOT'])
        );

        $this->setDebug(isset($_SERVER['BLUEMVC_DEBUG']));
    }
}
