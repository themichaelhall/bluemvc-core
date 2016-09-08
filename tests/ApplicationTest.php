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
        $DS = DIRECTORY_SEPARATOR;

        $application = new Application(
            [
                'DOCUMENT_ROOT' => $DS . 'var' . $DS . 'www',
            ]
        );

        $this->assertSame($DS . 'var' . $DS . 'www' . $DS, $application->getDocumentRoot()->__toString());
    }
}
