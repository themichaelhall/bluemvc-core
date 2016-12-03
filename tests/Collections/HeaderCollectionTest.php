<?php

use BlueMvc\Core\Collections\HeaderCollection;

/**
 * Test HeaderCollection class.
 */
class HeaderCollectionTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test count for empty collection.
     */
    public function testCountForEmptyCollection()
    {
        $headerCollection = new HeaderCollection();

        $this->assertSame(0, count($headerCollection));
    }
}
