<?php

namespace BlueMvc\Core\Tests\Collections;

use BlueMvc\Core\Collections\SessionItemCollection;
use BlueMvc\Core\Interfaces\Collections\SessionItemCollectionInterface;
use BlueMvc\Core\Tests\Helpers\Fakes\FakeSession;

/**
 * Test SessionItemCollection class.
 */
class SessionItemCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that session is not activated by default.
     */
    public function testSessionIsNotActivatedByDefault()
    {
        $sessionItemCollection = new SessionItemCollection();

        self::assertInstanceOf(SessionItemCollectionInterface::class, $sessionItemCollection);
        self::assertSame(PHP_SESSION_NONE, FakeSession::getStatus());
    }

    /**
     * Test count for empty collection.
     */
    public function testCountForEmptyCollection()
    {
        $sessionItemCollection = new SessionItemCollection();

        self::assertSame(0, count($sessionItemCollection));
        self::assertSame(PHP_SESSION_ACTIVE, FakeSession::getStatus());
    }

    /**
     * Test get method.
     */
    public function testGet()
    {
        $sessionItemCollection = new SessionItemCollection();

        self::assertNull($sessionItemCollection->get('Foo'));
        self::assertSame(PHP_SESSION_ACTIVE, FakeSession::getStatus());
    }

    /**
     * Test get method with invalid name parameter type.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $name parameter is not a string.
     */
    public function testGetMethodWithInvalidNameParameterType()
    {
        $sessionItemCollection = new SessionItemCollection();

        $sessionItemCollection->get(true);
    }

    /**
     * Test set method.
     */
    public function testSet()
    {
        $sessionItemCollection = new SessionItemCollection();
        $sessionItemCollection->set('Foo', 'xxx');
        $sessionItemCollection->set('bar', false);
        $sessionItemCollection->set('foo', ['One' => 1, 'Two' => 2]);

        self::assertSame(3, count($sessionItemCollection));
        self::assertSame('xxx', $sessionItemCollection->get('Foo'));
        self::assertSame(false, $sessionItemCollection->get('bar'));
        self::assertSame(['One' => 1, 'Two' => 2], $sessionItemCollection->get('foo'));
        self::assertSame(PHP_SESSION_ACTIVE, FakeSession::getStatus());
    }

    /**
     * Test set method with invalid name parameter type.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $name parameter is not a string.
     */
    public function testSetMethodWithInvalidNameParameterType()
    {
        $sessionItemCollection = new SessionItemCollection();

        $sessionItemCollection->set(10, 'Foo');
    }

    /**
     * Test remove method.
     */
    public function testRemove()
    {
        $sessionItemCollection = new SessionItemCollection();
        $sessionItemCollection->set('Foo', 'xxx');
        $sessionItemCollection->set('bar', false);

        $sessionItemCollection->remove('Foo');
        $sessionItemCollection->remove('baz');

        self::assertSame(['bar' => false], iterator_to_array($sessionItemCollection));
        self::assertSame(PHP_SESSION_ACTIVE, FakeSession::getStatus());
    }

    /**
     * Test remove method with invalid name parameter type.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $name parameter is not a string.
     */
    public function testRemoveMethodWithInvalidNameParameterType()
    {
        $sessionItemCollection = new SessionItemCollection();

        $sessionItemCollection->remove(1.0);
    }

    /**
     * Test iterator functionality for empty collection.
     */
    public function testIteratorForEmptyCollection()
    {
        $sessionItemCollection = new SessionItemCollection();

        $sessionItemArray = iterator_to_array($sessionItemCollection, true);

        self::assertSame([], $sessionItemArray);
        self::assertSame(PHP_SESSION_ACTIVE, FakeSession::getStatus());
    }

    /**
     * Test iterator functionality for non-empty collection.
     */
    public function testIteratorForNonEmptyCollection()
    {
        $sessionItemCollection = new SessionItemCollection();
        $sessionItemCollection->set('Foo', false);
        $sessionItemCollection->set('Bar', 'Baz');

        $sessionItemArray = iterator_to_array($sessionItemCollection, true);

        self::assertSame(['Foo' => false, 'Bar' => 'Baz'], $sessionItemArray);
        self::assertSame(PHP_SESSION_ACTIVE, FakeSession::getStatus());
    }

    /**
     * Set up.
     */
    public function setUp()
    {
        FakeSession::enable();
    }

    /**
     * Tear down.
     */
    public function tearDown()
    {
        FakeSession::disable();
    }
}
