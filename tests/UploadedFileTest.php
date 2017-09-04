<?php

namespace BlueMvc\Core\Tests;

use BlueMvc\Core\UploadedFile;
use DataTypes\FilePath;

/**
 * Test UploadedFile class.
 */
class UploadedFileTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test getPath method.
     */
    public function testGetPath()
    {
        $DS = DIRECTORY_SEPARATOR;
        $uploadedFile = new UploadedFile(FilePath::parse('/foo/bar.txt'));

        self::assertSame($DS . 'foo' . $DS . 'bar.txt', $uploadedFile->getPath()->__toString());
    }
}
