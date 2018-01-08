<?php

namespace BlueMvc\Core\Tests\Collections;

use BlueMvc\Core\Collections\HeaderCollection;
use PHPUnit\Framework\TestCase;

/**
 * Test HeaderCollection class.
 */
class HeaderCollectionTest extends TestCase
{
    /**
     * Test count for empty collection.
     */
    public function testCountForEmptyCollection()
    {
        $headerCollection = new HeaderCollection();

        self::assertSame(0, count($headerCollection));
    }

    /**
     * Test get method.
     */
    public function testGet()
    {
        $headerCollection = new HeaderCollection();

        self::assertNull($headerCollection->get('Content-Type'));
    }

    /**
     * Test get method with invalid name parameter type.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $name parameter is not a string.
     */
    public function testGetMethodWithInvalidNameParameterType()
    {
        $headerCollection = new HeaderCollection();

        $headerCollection->get(10);
    }

    /**
     * Test set method.
     */
    public function testSet()
    {
        $headerCollection = new HeaderCollection();
        $headerCollection->set('content-type', 'text/html');
        $headerCollection->set('Host', 'localhost');
        $headerCollection->set('Content-Type', 'image/png');

        self::assertSame(2, count($headerCollection));
        self::assertSame('image/png', $headerCollection->get('Content-Type'));
        self::assertSame('localhost', $headerCollection->get('Host'));
    }

    /**
     * Test set method with invalid name parameter type.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $name parameter is not a string.
     */
    public function testSetMethodWithInvalidNameParameterType()
    {
        $headerCollection = new HeaderCollection();

        $headerCollection->set(10, 'test/html');
    }

    /**
     * Test set method with invalid valid parameter type.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $value parameter is not a string.
     */
    public function testSetMethodWithInvalidValueParameterType()
    {
        $headerCollection = new HeaderCollection();

        $headerCollection->set('Location', false);
    }

    /**
     * Test add method.
     */
    public function testAdd()
    {
        $headerCollection = new HeaderCollection();

        $headerCollection->set('Accept-Encoding', 'gzip');
        $headerCollection->add('accept-encoding', 'deflate');
        $headerCollection->add('content-type', 'text/html');

        self::assertSame(2, count($headerCollection));
        self::assertSame('text/html', $headerCollection->get('Content-type'));
        self::assertSame('gzip, deflate', $headerCollection->get('Accept-encoding'));
    }

    /**
     * Test add method with invalid name parameter type.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $name parameter is not a string.
     */
    public function testAddMethodWithInvalidNameParameterType()
    {
        $headerCollection = new HeaderCollection();

        $headerCollection->add(10, 'test/html');
    }

    /**
     * Test add method with invalid valid parameter type.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $value parameter is not a string.
     */
    public function testAddMethodWithInvalidValueParameterType()
    {
        $headerCollection = new HeaderCollection();

        $headerCollection->add('Location', false);
    }

    /**
     * Test iterator functionality for empty collection.
     */
    public function testIteratorForEmptyCollection()
    {
        $headerCollection = new HeaderCollection();

        $headerArray = iterator_to_array($headerCollection, true);

        self::assertSame([], $headerArray);
    }

    /**
     * Test iterator functionality for non-empty collection.
     */
    public function testIteratorForNonEmptyCollection()
    {
        $headerCollection = new HeaderCollection();
        $headerCollection->set('Location', 'http://localhost/');
        $headerCollection->set('Accept-Encoding', 'gzip');

        $headerArray = iterator_to_array($headerCollection, true);

        self::assertSame(['Location' => 'http://localhost/', 'Accept-Encoding' => 'gzip'], $headerArray);
    }
}
