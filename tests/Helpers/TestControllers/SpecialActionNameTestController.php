<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Helpers\TestControllers;

use BlueMvc\Core\Controller;

/**
 * Special action name test controller.
 */
class SpecialActionNameTestController extends Controller
{
    /**
     * _index action.
     *
     * @return string The result.
     */
    public function _indexAction(): string
    {
        return '_index action';
    }

    /**
     * _default action.
     *
     * @return string The result.
     */
    public function _DefaultAction(): string
    {
        return '_Default action';
    }
}
