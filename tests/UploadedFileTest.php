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
        $uploadedFile = new UploadedFile(FilePath::parse('/foo/bar.txt'), 'FooBar.txt');

        self::assertSame($DS . 'foo' . $DS . 'bar.txt', $uploadedFile->getPath()->__toString());
    }

    /**
     * Test getOriginalName method.
     */
    public function testGetOriginalName()
    {
        $uploadedFile = new UploadedFile(FilePath::parse('/foo/bar.txt'), 'FooBar.txt');

        self::assertSame('FooBar.txt', $uploadedFile->getOriginalName());
    }

    /**
     * Test default constructor parameters.
     */
    public function testDefaultConstructorParameters()
    {
        $DS = DIRECTORY_SEPARATOR;
        $uploadedFile = new UploadedFile(FilePath::parse('/foo/bar.txt'));

        self::assertSame($DS . 'foo' . $DS . 'bar.txt', $uploadedFile->getPath()->__toString());
        self::assertSame('', $uploadedFile->getOriginalName());
    }

    /**
     * Test create with invalid original name parameter type.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $originalName parameter is not a string.
     */
    public function testCreateWithInvalidOriginalNameParameterType()
    {
        new UploadedFile(FilePath::parse('/foo/bar.txt'), 0);
    }
}
