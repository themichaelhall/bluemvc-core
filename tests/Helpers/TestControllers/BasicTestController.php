<?php

use BlueMvc\Core\Controller;

/**
 * Basic test controller class.
 */
class BasicTestController extends Controller
{
    /**
     * Index action.
     *
     * @return string The result.
     */
    public function indexAction()
    {
        return 'Hello World!';
    }
}
