<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Collections;

use BlueMvc\Core\Collections\SessionItemCollection;
use BlueMvc\Core\Interfaces\Collections\SessionItemCollectionInterface;
use BlueMvc\Core\Tests\Helpers\Fakes\FakeSession;
use PHPUnit\Framework\TestCase;

/**
 * Test SessionItemCollection class.
 */
class SessionItemCollectionTest extends TestCase
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
     * Test set method.
     */
    public function testSet()
    {
        $sessionItemCollection = new SessionItemCollection();
        $sessionItemCollection->set('Foo', 'xxx');
        $sessionItemCollection->set('bar', false);
        $sessionItemCollection->set('foo', ['One' => 1, 'Two' => 2]);
        $sessionItemCollection->set('1', '2');

        self::assertSame(4, count($sessionItemCollection));
        self::assertSame('xxx', $sessionItemCollection->get('Foo'));
        self::assertSame(false, $sessionItemCollection->get('bar'));
        self::assertSame(['One' => 1, 'Two' => 2], $sessionItemCollection->get('foo'));
        self::assertSame('2', $sessionItemCollection->get('1'));
        self::assertSame(PHP_SESSION_ACTIVE, FakeSession::getStatus());
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
        $sessionItemCollection->set('1', '2');

        $sessionItemArray = iterator_to_array($sessionItemCollection, true);

        self::assertSame(['Foo' => false, 'Bar' => 'Baz', 1 => '2'], $sessionItemArray);
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
