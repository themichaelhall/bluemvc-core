<?php

namespace BlueMvc\Core\Tests\Collections;

use BlueMvc\Core\Collections\ViewItemCollection;

/**
 * Test ViewItemCollection class.
 */
class ViewItemCollectionTest extends \PHPUnit_Framework_TestCase
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
}
