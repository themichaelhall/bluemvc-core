<?php

use BlueMvc\Core\Base\AbstractApplication;
use DataTypes\Interfaces\FilePathInterface;

/**
 * A basic test application.
 */
class BasicTestApplication extends AbstractApplication
{
    /**
     * Constructs the application.
     *
     * @param FilePathInterface $documentRoot The document root.
     */
    public function __construct(FilePathInterface $documentRoot)
    {
        parent::__construct($documentRoot);
    }
}
