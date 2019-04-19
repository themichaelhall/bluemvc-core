<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Base;

use BlueMvc\Core\Exceptions\InvalidViewFileExtensionException;
use BlueMvc\Core\Tests\Helpers\TestViewRenderers\BasicTestViewRenderer;
use PHPUnit\Framework\TestCase;

/**
 * Test AbstractViewRenderer class.
 */
class AbstractViewRendererTest extends TestCase
{
    /**
     * Test getViewFileExtension method.
     */
    public function testGetViewFileExtension()
    {
        $viewRenderer = new BasicTestViewRenderer();

        self::assertSame('view', $viewRenderer->getViewFileExtension());
    }

    /**
     * Test create view renderer with invalid file extension.
     */
    public function testCreateWithInvalidFileExtension()
    {
        self::expectException(InvalidViewFileExtensionException::class);
        self::expectExceptionMessage('View file extension "foo$bar" contains invalid character "$".');

        new BasicTestViewRenderer('foo$bar');
    }
}
