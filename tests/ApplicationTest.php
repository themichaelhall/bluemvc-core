<?php

use BlueMvc\Core\Application;

/**
 * Test Application class.
 */
class ApplicationTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test getDocumentRoot method.
     */
    public function testGetDocumentRoot()
    {
        $application = new Application(
            [
                'DOCUMENT_ROOT' => DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'www' . DIRECTORY_SEPARATOR,
            ]
        );

        $this->assertSame(DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'www' . DIRECTORY_SEPARATOR, $application->getDocumentRoot()->__toString());
    }
}
