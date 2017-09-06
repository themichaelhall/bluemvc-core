<?php

namespace BlueMvc\Core\Tests\Collections;

use BlueMvc\Core\Collections\UploadedFileCollection;

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
}
