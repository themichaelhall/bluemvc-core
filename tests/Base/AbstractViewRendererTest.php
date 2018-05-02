<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Base;

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
     *
     * @expectedException \BlueMvc\Core\Exceptions\InvalidViewFileExtensionException
     * @expectedExceptionMessage View file extension "foo$bar" contains invalid character "$".
     */
    public function testCreateWithInvalidFileExtension()
    {
        new BasicTestViewRenderer('foo$bar');
    }
}
