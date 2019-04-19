<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Issues;

use BlueMvc\Core\Request;
use BlueMvc\Core\Tests\Helpers\Fakes\FakeFunctionExists;
use BlueMvc\Core\Tests\Helpers\Fakes\FakeGetAllHeaders;
use PHPUnit\Framework\TestCase;

/**
 * Test Issue #2.
 */
class Issue0002Test extends TestCase
{
    /**
     * Test that authority header is fetched from getallheaders().
     */
    public function testAuthorityHeaderIsFetchedFromGetAllHeaders()
    {
        $request = new Request();

        self::assertSame('Basic Zm9vOmJhcg==', $request->getHeader('Authorization'));
    }

    /**
     * Set up.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->originalServerArray = $_SERVER;

        $_SERVER = [
            'HTTP_HOST'      => 'example.com',
            'REQUEST_URI'    => '/foo/bar',
            'REQUEST_METHOD' => 'GET',
        ];

        FakeFunctionExists::enable();
        FakeGetAllHeaders::enable();
        FakeGetAllHeaders::addHeader('Host', 'example.com');
        FakeGetAllHeaders::addHeader('Authorization', 'Basic Zm9vOmJhcg==');
    }

    /**
     * Tear down.
     */
    public function tearDown(): void
    {
        parent::tearDown();

        FakeFunctionExists::disable();
        FakeGetAllHeaders::disable();

        $_SERVER = $this->originalServerArray;
    }

    /**
     * @var array The original server array.
     */
    private $originalServerArray;
}
