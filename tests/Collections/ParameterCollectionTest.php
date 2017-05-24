<?php

namespace BlueMvc\Core\Tests\Collections;

use BlueMvc\Core\Collections\ParameterCollection;

/**
 * Test ParameterCollection class.
 */
class ParameterCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test count for empty collection.
     */
    public function testCountForEmptyCollection()
    {
        $parameterCollection = new ParameterCollection();

        self::assertSame(0, count($parameterCollection));
    }

    /**
     * Test get method.
     */
    public function testGet()
    {
        $parameterCollection = new ParameterCollection();

        self::assertNull($parameterCollection->get('Foo'));
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

        self::assertSame(3, count($parameterCollection));
        self::assertSame('xxx', $parameterCollection->get('Foo'));
        self::assertSame('YYY', $parameterCollection->get('bar'));
        self::assertSame('zzz', $parameterCollection->get('foo'));
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

    /**
     * Test iterator functionality for empty collection.
     */
    public function testIteratorForEmptyCollection()
    {
        $parameterCollection = new ParameterCollection();

        $parameterArray = iterator_to_array($parameterCollection, true);

        self::assertSame([], $parameterArray);
    }

    /**
     * Test iterator functionality for non-empty collection.
     */
    public function testIteratorForNonEmptyCollection()
    {
        $parameterCollection = new ParameterCollection();
        $parameterCollection->set('Foo', '1');
        $parameterCollection->set('Bar', '2');

        $parameterArray = iterator_to_array($parameterCollection, true);

        self::assertSame(['Foo' => '1', 'Bar' => '2'], $parameterArray);
    }
}
