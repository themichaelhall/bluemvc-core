<?php

namespace BlueMvc\Core\Tests\Collections;

use BlueMvc\Core\Collections\CustomItemCollection;
use PHPUnit\Framework\TestCase;

/**
 * Test CustomItemCollection class.
 */
class CustomItemCollectionTest extends TestCase
{
    /**
     * Test count for empty collection.
     */
    public function testCountForEmptyCollection()
    {
        $customItemCollection = new CustomItemCollection();

        self::assertSame(0, count($customItemCollection));
    }

    /**
     * Test get method.
     */
    public function testGet()
    {
        $customItemCollection = new CustomItemCollection();

        self::assertNull($customItemCollection->get('Foo'));
    }

    /**
     * Test get method with invalid name parameter type.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $name parameter is not a string.
     */
    public function testGetMethodWithInvalidNameParameterType()
    {
        $customItemCollection = new CustomItemCollection();

        $customItemCollection->get(true);
    }

    /**
     * Test set method.
     */
    public function testSet()
    {
        $customItemCollection = new CustomItemCollection();
        $customItemCollection->set('Foo', 'xxx');
        $customItemCollection->set('bar', false);
        $customItemCollection->set('foo', ['One' => 1, 'Two' => 2]);

        self::assertSame(3, count($customItemCollection));
        self::assertSame('xxx', $customItemCollection->get('Foo'));
        self::assertSame(false, $customItemCollection->get('bar'));
        self::assertSame(['One' => 1, 'Two' => 2], $customItemCollection->get('foo'));
    }

    /**
     * Test set method with invalid name parameter type.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $name parameter is not a string.
     */
    public function testSetMethodWithInvalidNameParameterType()
    {
        $customItemCollection = new CustomItemCollection();

        $customItemCollection->set(10, 'Foo');
    }

    /**
     * Test iterator functionality for empty collection.
     */
    public function testIteratorForEmptyCollection()
    {
        $customItemCollection = new CustomItemCollection();

        $customItemArray = iterator_to_array($customItemCollection, true);

        self::assertSame([], $customItemArray);
    }

    /**
     * Test iterator functionality for non-empty collection.
     */
    public function testIteratorForNonEmptyCollection()
    {
        $customItemCollection = new CustomItemCollection();
        $customItemCollection->set('Foo', false);
        $customItemCollection->set('Bar', 'Baz');

        $customItemArray = iterator_to_array($customItemCollection, true);

        self::assertSame(['Foo' => false, 'Bar' => 'Baz'], $customItemArray);
    }
}
