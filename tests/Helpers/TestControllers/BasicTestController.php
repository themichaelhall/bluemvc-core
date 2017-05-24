<?php

namespace BlueMvc\Core\Tests\Helpers\TestControllers;

use BlueMvc\Core\Controller;
use BlueMvc\Core\Http\StatusCode;

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

    /**
     * Server error action.
     *
     * @return string The result.
     */
    public function serverErrorAction()
    {
        $this->getResponse()->setStatusCode(new StatusCode(StatusCode::INTERNAL_SERVER_ERROR));

        return 'Server Error';
    }

    /**
     * Action starting with a numeric character.
     *
     * @return string The result.
     */
    public function _123numericAction()
    {
        return 'Numeric action result';
    }
}
