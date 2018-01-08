<?php

namespace BlueMvc\Core\Tests\Collections;

use BlueMvc\Core\Collections\ViewItemCollection;
use PHPUnit\Framework\TestCase;

/**
 * Test ViewItemCollection class.
 */
class ViewItemCollectionTest extends TestCase
{
    /**
     * Test count for empty collection.
     */
    public function testCountForEmptyCollection()
    {
        $viewItemCollection = new ViewItemCollection();

        self::assertSame(0, count($viewItemCollection));
    }

    /**
     * Test get method.
     */
    public function testGet()
    {
        $viewItemCollection = new ViewItemCollection();

        self::assertNull($viewItemCollection->get('Foo'));
    }

    /**
     * Test get method with invalid name parameter type.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $name parameter is not a string.
     */
    public function testGetMethodWithInvalidNameParameterType()
    {
        $viewItemCollection = new ViewItemCollection();

        $viewItemCollection->get(true);
    }

    /**
     * Test set method.
     */
    public function testSet()
    {
        $viewItemCollection = new ViewItemCollection();
        $viewItemCollection->set('Foo', 'xxx');
        $viewItemCollection->set('bar', false);
        $viewItemCollection->set('foo', ['One' => 1, 'Two' => 2]);

        self::assertSame(3, count($viewItemCollection));
        self::assertSame('xxx', $viewItemCollection->get('Foo'));
        self::assertSame(false, $viewItemCollection->get('bar'));
        self::assertSame(['One' => 1, 'Two' => 2], $viewItemCollection->get('foo'));
    }

    /**
     * Test set method with invalid name parameter type.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $name parameter is not a string.
     */
    public function testSetMethodWithInvalidNameParameterType()
    {
        $viewItemCollection = new ViewItemCollection();

        $viewItemCollection->set(10, 'Foo');
    }

    /**
     * Test iterator functionality for empty collection.
     */
    public function testIteratorForEmptyCollection()
    {
        $viewItemCollection = new ViewItemCollection();

        $viewItemArray = iterator_to_array($viewItemCollection, true);

        self::assertSame([], $viewItemArray);
    }

    /**
     * Test iterator functionality for non-empty collection.
     */
    public function testIteratorForNonEmptyCollection()
    {
        $viewItemCollection = new ViewItemCollection();
        $viewItemCollection->set('Foo', false);
        $viewItemCollection->set('Bar', 'Baz');

        $viewItemArray = iterator_to_array($viewItemCollection, true);

        self::assertSame(['Foo' => false, 'Bar' => 'Baz'], $viewItemArray);
    }
}
