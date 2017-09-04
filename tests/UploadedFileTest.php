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
        $uploadedFile = new UploadedFile(FilePath::parse('/foo/bar.txt'), 'FooBar.txt', 1234);

        self::assertSame($DS . 'foo' . $DS . 'bar.txt', $uploadedFile->getPath()->__toString());
    }

    /**
     * Test getOriginalName method.
     */
    public function testGetOriginalName()
    {
        $uploadedFile = new UploadedFile(FilePath::parse('/foo/bar.txt'), 'FooBar.txt', 1234);

        self::assertSame('FooBar.txt', $uploadedFile->getOriginalName());
    }

    /**
     * Test getSize method.
     */
    public function testGetSize()
    {
        $uploadedFile = new UploadedFile(FilePath::parse('/foo/bar.txt'), 'FooBar.txt', 1234);

        self::assertSame(1234, $uploadedFile->getSize());
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
        self::assertSame(0, $uploadedFile->getSize());
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

    /**
     * Test create with invalid size parameter type.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $size parameter is not an integer.
     */
    public function testCreateWithInvalidSizeParameterType()
    {
        new UploadedFile(FilePath::parse('/foo/bar.txt'), 'FooBar.txt', true);
    }
}
