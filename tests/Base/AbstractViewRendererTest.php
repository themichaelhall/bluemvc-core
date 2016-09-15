<?php

require_once __DIR__ . '/../Helpers/TestViewRenderers/BasicTestViewRenderer.php';

/**
 * Test AbstractViewRenderer class.
 */
class AbstractViewRendererTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test getViewFileExtension method.
     */
    public function testGetViewFileExtension()
    {
        $viewRenderer = new BasicTestViewRenderer();

        $this->assertSame('view', $viewRenderer->getViewFileExtension());
    }
}
