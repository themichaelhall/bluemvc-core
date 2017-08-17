<?php

namespace BlueMvc\Core\Tests\Base;

use BlueMvc\Core\Tests\Helpers\TestViewRenderers\BasicTestViewRenderer;

/**
 * Test AbstractViewRenderer class.
 */
class AbstractViewRendererTest extends \PHPUnit_Framework_TestCase
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

    /**
     * Test create view renderer with invalid argument type.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $viewFileExtension parameter is not a string.
     */
    public function testCreateWithInvalidArgumentType()
    {
        new BasicTestViewRenderer([]);
    }
}
