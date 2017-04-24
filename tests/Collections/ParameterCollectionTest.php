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

    /**
     * Test set method.
     */
    public function testSet()
    {
        $parameterCollection = new ParameterCollection();
        $parameterCollection->set('Foo', 'xxx');
        $parameterCollection->set('bar', 'YYY');
        $parameterCollection->set('foo', 'zzz');

        $this->assertSame(3, count($parameterCollection));
        $this->assertSame('xxx', $parameterCollection->get('Foo'));
        $this->assertSame('YYY', $parameterCollection->get('bar'));
        $this->assertSame('zzz', $parameterCollection->get('foo'));
    }

    /**
     * Test set method with invalid name parameter type.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $name parameter is not a string.
     */
    public function testSetMethodWithInvalidNameParameterType()
    {
        $parameterCollection = new ParameterCollection();

        $parameterCollection->set(10, 'Foo');
    }

    /**
     * Test set method with invalid valid parameter type.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $value parameter is not a string.
     */
    public function testSetMethodWithInvalidValueParameterType()
    {
        $parameterCollection = new ParameterCollection();

        $parameterCollection->set('Bar', false);
    }
}
