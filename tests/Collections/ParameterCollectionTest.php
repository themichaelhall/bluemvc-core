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
}
