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

    /**
     * Test get method.
     */
    public function testGet()
    {
        $headerCollection = new HeaderCollection();

        $this->assertNull($headerCollection->get('Content-Type'));
    }

    /**
     * Test get method with invalid parameter type.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $name parameter is not a string.
     */
    public function testGetMethodWithInvalidNameParameterType()
    {
        $headerCollection = new HeaderCollection();

        $headerCollection->get(10);
    }
}
