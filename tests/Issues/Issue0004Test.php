<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Issues;

use BlueMvc\Core\Request;
use PHPUnit\Framework\TestCase;

/**
 * Test Issue #4.
 */
class Issue0004Test extends TestCase
{
    /**
     * Test $_SERVER['REQUEST_URI'] with invalid characters.
     */
    public function testRequestUriWithInvalidCharacters()
    {
        $request = new Request();
        $url = $request->getUrl();

        self::assertSame(['path'], $url->getPath()->getDirectoryParts());
        self::assertSame('foo\\(bar) -._~:#[]@!$&\'()*+,;=Ö', $url->getPath()->getFilename());
        self::assertSame('%3CBaz%5C[Baz]%3E/%20-._~:/?%23[]@!$&\'()*+,;=%C3%96', $url->getQueryString());
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
            'REQUEST_URI'    => '/path/foo\\(bar)%20-._~:#[]@!$&\'()*+,;=Ö?<Baz\\[Baz]>/%20-._~:/?#[]@!$&\'()*+,;=Ö',
            'REQUEST_METHOD' => 'GET',
        ];
    }

    /**
     * Tear down.
     */
    public function tearDown(): void
    {
        parent::tearDown();

        $_SERVER = $this->originalServerArray;
    }

    /**
     * @var array The original server array.
     */
    private $originalServerArray;
}
