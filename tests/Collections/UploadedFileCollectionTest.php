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
}
