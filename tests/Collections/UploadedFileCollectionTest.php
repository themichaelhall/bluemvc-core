<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Collections;

use BlueMvc\Core\Collections\UploadedFileCollection;
use BlueMvc\Core\UploadedFile;
use DataTypes\FilePath;
use PHPUnit\Framework\TestCase;

/**
 * Test UploadedFileCollection class.
 */
class UploadedFileCollectionTest extends TestCase
{
    /**
     * Test count for empty collection.
     */
    public function testCountForEmptyCollection()
    {
        $uploadedFileCollection = new UploadedFileCollection();

        self::assertSame(0, count($uploadedFileCollection));
    }

    /**
     * Test get method.
     */
    public function testGet()
    {
        $uploadedFileCollection = new UploadedFileCollection();

        self::assertNull($uploadedFileCollection->get('Foo'));
    }

    /**
     * Test set method.
     */
    public function testSet()
    {
        $fileFoo = new UploadedFile(FilePath::parse('/tmp/foo.txt'), 'Foo', 1234);
        $fileBar = new UploadedFile(FilePath::parse('/tmp/bar.dat'));
        $fileBaz = new UploadedFile(FilePath::parse('/tmp/baz.ico'));

        $uploadedFileCollection = new UploadedFileCollection();
        $uploadedFileCollection->set('Foo', $fileFoo);
        $uploadedFileCollection->set('bar', $fileBar);
        $uploadedFileCollection->set('1', $fileBaz);

        self::assertSame(3, count($uploadedFileCollection));
        self::assertSame($fileFoo, $uploadedFileCollection->get('Foo'));
        self::assertSame($fileBar, $uploadedFileCollection->get('bar'));
        self::assertNull($uploadedFileCollection->get('foo'));
        self::assertSame($fileBaz, $uploadedFileCollection->get('1'));
    }

    /**
     * Test iterator functionality for empty collection.
     */
    public function testIteratorForEmptyCollection()
    {
        $uploadedFileCollection = new UploadedFileCollection();

        $uploadedFileArray = iterator_to_array($uploadedFileCollection, true);

        self::assertSame([], $uploadedFileArray);
    }

    /**
     * Test iterator functionality for non-empty collection.
     */
    public function testIteratorForNonEmptyCollection()
    {
        $fileFoo = new UploadedFile(FilePath::parse('/tmp/foo.txt'), 'Foo', 1234);
        $fileBar = new UploadedFile(FilePath::parse('/tmp/bar.dat'));
        $fileBaz = new UploadedFile(FilePath::parse('/tmp/baz.ico'));

        $uploadedFileCollection = new UploadedFileCollection();
        $uploadedFileCollection->set('Foo', $fileFoo);
        $uploadedFileCollection->set('bar', $fileBar);
        $uploadedFileCollection->set('1', $fileBaz);

        $uploadedFileArray = iterator_to_array($uploadedFileCollection, true);

        self::assertSame(['Foo' => $fileFoo, 'bar' => $fileBar, 1 => $fileBaz], $uploadedFileArray);
    }
}
