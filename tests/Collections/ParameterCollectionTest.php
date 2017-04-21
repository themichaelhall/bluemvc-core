<?php

use BlueMvc\Core\Collections\ParameterCollection;

/**
 * Test ParameterCollection class.
 */
class ParameterCollectionTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test count for empty collection.
     */
    public function testCountForEmptyCollection()
    {
        $parameterCollection = new ParameterCollection();

        $this->assertSame(0, count($parameterCollection));
    }

    /**
     * Test get method.
     */
    public function testGet()
    {
        $parameterCollection = new ParameterCollection();

        $this->assertNull($parameterCollection->get('Foo'));
    }

    /**
     * Test get method with invalid name parameter type.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $name parameter is not a string.
     */
    public function testGetMethodWithInvalidNameParameterType()
    {
        $parameterCollection = new ParameterCollection();

        $parameterCollection->get(10);
    }
}
