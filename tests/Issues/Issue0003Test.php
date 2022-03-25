<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Issues;

use BlueMvc\Core\Request;
use PHPUnit\Framework\TestCase;

/**
 * Test Issue #3.
 */
class Issue0003Test extends TestCase
{
    /**
     * Test different $_SERVER['HTTPS'] values.
     *
     * @dataProvider serverHttpsValueDataProvider
     *
     * @param null|string $serverHttps The $_SERVER['HTTPS'] value or null if not set.
     * @param string      $expectedUrl The expected Url.
     */
    public function testServerHttpsValue(?string $serverHttps, string $expectedUrl)
    {
        if ($serverHttps !== null) {
            $_SERVER['HTTPS'] = $serverHttps;
        }

        $request = new Request();

        self::assertSame($expectedUrl, $request->getUrl()->__toString());
    }

    /**
     * Data provider for testServerHttpsValue.
     *
     * @return array
     */
    public function serverHttpsValueDataProvider(): array
    {
        return [
            [null, 'http://example.com/foo/bar'],
            ['', 'http://example.com/foo/bar'],
            ['foo', 'https://example.com/foo/bar'],
            ['on', 'https://example.com/foo/bar'],
            ['off', 'http://example.com/foo/bar'], // IIS sets the value to 'off' for non-https.
        ];
    }

    /**
     * Set up.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->originalServerArray = $_SERVER;

        $_SERVER = [
            'HTTP_HOST'      => 'example.com',
            'REQUEST_URI'    => '/foo/bar',
            'REQUEST_METHOD' => 'GET',
        ];
    }

    /**
     * Tear down.
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $_SERVER = $this->originalServerArray;
    }

    /**
     * @var array The original server array.
     */
    private $originalServerArray;
}
