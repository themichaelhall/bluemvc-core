<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests;

use BlueMvc\Core\Exceptions\InvalidFilePathException;
use BlueMvc\Core\UploadedFile;
use DataTypes\System\FilePath;
use PHPUnit\Framework\TestCase;

/**
 * Test UploadedFile class.
 */
class UploadedFileTest extends TestCase
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
     * Test create with directory as path.
     */
    public function testCreateWithDirectoryAsPath()
    {
        $DS = DIRECTORY_SEPARATOR;

        self::expectException(InvalidFilePathException::class);
        self::expectExceptionMessage('Path "' . $DS . 'foo' . $DS . 'bar' . $DS . '" is not a file.');

        new UploadedFile(FilePath::parse('/foo/bar/'));
    }

    /**
     * Test create with a relative path.
     */
    public function testCreateWithRelativePath()
    {
        $DS = DIRECTORY_SEPARATOR;

        self::expectException(InvalidFilePathException::class);
        self::expectExceptionMessage('Path "foo' . $DS . 'bar.txt" is not an absolute path.');

        new UploadedFile(FilePath::parse('./foo/bar.txt'));
    }
}
