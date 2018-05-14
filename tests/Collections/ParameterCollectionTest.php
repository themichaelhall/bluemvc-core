<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Collections;

use BlueMvc\Core\Collections\ParameterCollection;
use PHPUnit\Framework\TestCase;

/**
 * Test ParameterCollection class.
 */
class ParameterCollectionTest extends TestCase
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
     * Test set method.
     */
    public function testSet()
    {
        $parameterCollection = new ParameterCollection();
        $parameterCollection->set('Foo', 'xxx');
        $parameterCollection->set('bar', 'YYY');
        $parameterCollection->set('foo', 'zzz');
        $parameterCollection->set('1', '2');

        self::assertSame(4, count($parameterCollection));
        self::assertSame('xxx', $parameterCollection->get('Foo'));
        self::assertSame('YYY', $parameterCollection->get('bar'));
        self::assertSame('zzz', $parameterCollection->get('foo'));
        self::assertSame('2', $parameterCollection->get('1'));
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
        $parameterCollection->set('1', '2');

        $parameterArray = iterator_to_array($parameterCollection, true);

        self::assertSame(['Foo' => '1', 'Bar' => '2', 1 => '2'], $parameterArray);
    }
}
