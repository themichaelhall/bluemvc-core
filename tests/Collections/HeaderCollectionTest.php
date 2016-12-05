<?php

use BlueMvc\Core\Collections\HeaderCollection;

/**
 * Test HeaderCollection class.
 */
class HeaderCollectionTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test count for empty collection.
     */
    public function testCountForEmptyCollection()
    {
        $headerCollection = new HeaderCollection();

        $this->assertSame(0, count($headerCollection));
    }

    /**
     * Test get method.
     */
    public function testGet()
    {
        $headerCollection = new HeaderCollection();

        $this->assertNull($headerCollection->get('Content-Type'));
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

        $this->assertSame(2, count($headerCollection));
        $this->assertSame('image/png', $headerCollection->get('Content-Type'));
        $this->assertSame('localhost', $headerCollection->get('Host'));
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

        $this->assertSame(2, count($headerCollection));
        $this->assertSame('text/html', $headerCollection->get('Content-type'));
        $this->assertSame('gzip, deflate', $headerCollection->get('Accept-encoding'));
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
}
