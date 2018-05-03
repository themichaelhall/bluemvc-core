<?php

declare(strict_types=1);

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
     * Test set method.
     */
    public function testSet()
    {
        $viewItemCollection = new ViewItemCollection();
        $viewItemCollection->set('Foo', 'xxx');
        $viewItemCollection->set('bar', false);
        $viewItemCollection->set('foo', ['One' => 1, 'Two' => 2]);
        $viewItemCollection->set('1', '2');

        self::assertSame(4, count($viewItemCollection));
        self::assertSame('xxx', $viewItemCollection->get('Foo'));
        self::assertSame(false, $viewItemCollection->get('bar'));
        self::assertSame(['One' => 1, 'Two' => 2], $viewItemCollection->get('foo'));
        self::assertSame('2', $viewItemCollection->get('1'));
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
        $viewItemCollection->set('1', '2');

        $viewItemArray = iterator_to_array($viewItemCollection, true);

        self::assertSame(['Foo' => false, 'Bar' => 'Baz', 1 => '2'], $viewItemArray);
    }
}
