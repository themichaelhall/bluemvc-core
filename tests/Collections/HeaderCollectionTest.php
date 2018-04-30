<?php

declare(strict_types=1);

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
     * Test set method.
     */
    public function testSet()
    {
        $headerCollection = new HeaderCollection();
        $headerCollection->set('content-type', 'text/html');
        $headerCollection->set('Host', 'localhost');
        $headerCollection->set('Content-Type', 'image/png');
        $headerCollection->set('1', '2');

        self::assertSame(3, count($headerCollection));
        self::assertSame('image/png', $headerCollection->get('Content-Type'));
        self::assertSame('localhost', $headerCollection->get('Host'));
        self::assertSame('2', $headerCollection->get('1'));
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
        $headerCollection->set('1', '2');

        $headerArray = iterator_to_array($headerCollection, true);

        self::assertSame(['Location' => 'http://localhost/', 'Accept-Encoding' => 'gzip', 1 => '2'], $headerArray);
    }
}
