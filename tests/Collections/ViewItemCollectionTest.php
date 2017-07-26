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
}
