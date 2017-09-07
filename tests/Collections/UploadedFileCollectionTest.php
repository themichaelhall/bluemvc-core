<?php

namespace BlueMvc\Core\Tests\Collections;

use BlueMvc\Core\Collections\UploadedFileCollection;
use BlueMvc\Core\UploadedFile;
use DataTypes\FilePath;

/**
 * Test UploadedFileCollection class.
 */
class UploadedFileCollectionTest extends \PHPUnit_Framework_TestCase
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
     * Test get method with invalid name parameter type.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $name parameter is not a string.
     */
    public function testGetMethodWithInvalidNameParameterType()
    {
        $uploadedFileCollection = new UploadedFileCollection();

        $uploadedFileCollection->get(10);
    }

    /**
     * Test set method.
     */
    public function testSet()
    {
        $fileFoo = new UploadedFile(FilePath::parse('/tmp/foo.txt'), 'Foo', 1234);
        $fileBar = new UploadedFile(FilePath::parse('/tmp/bar.dat'));

        $uploadedFileCollection = new UploadedFileCollection();
        $uploadedFileCollection->set('Foo', $fileFoo);
        $uploadedFileCollection->set('bar', $fileBar);

        self::assertSame(2, count($uploadedFileCollection));
        self::assertSame($fileFoo, $uploadedFileCollection->get('Foo'));
        self::assertSame($fileBar, $uploadedFileCollection->get('bar'));
        self::assertNull($uploadedFileCollection->get('foo'));
    }

    /**
     * Test set method with invalid name parameter type.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $name parameter is not a string.
     */
    public function testSetMethodWithInvalidNameParameterType()
    {
        $uploadedFileCollection = new UploadedFileCollection();

        $uploadedFileCollection->set(false, new UploadedFile(FilePath::parse('/tmp/foo')));
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

        $uploadedFileCollection = new UploadedFileCollection();
        $uploadedFileCollection->set('Foo', $fileFoo);
        $uploadedFileCollection->set('bar', $fileBar);

        $uploadedFileArray = iterator_to_array($uploadedFileCollection, true);

        self::assertSame(['Foo' => $fileFoo, 'bar' => $fileBar], $uploadedFileArray);
    }
}
