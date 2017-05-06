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
}
