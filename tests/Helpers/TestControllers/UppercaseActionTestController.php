<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Helpers\TestControllers;

use BlueMvc\Core\Controller;

/**
 * Uppercase action test controller.
 */
class UppercaseActionTestController extends Controller
{
    /**
     * Index action.
     *
     * @return string The result.
     */
    public function INDEXACTION(): string
    {
        return 'INDEX action';
    }

    /**
     * Foo action.
     *
     * @return string The result.
     */
    public function FOOACTION(): string
    {
        return 'FOO action';
    }

    /**
     * Default action.
     *
     * @param string $action The action.
     *
     * @return string The result.
     */
    public function DEFAULTACTION(string $action): string
    {
        return 'DEFAULT action "' . $action . '"';
    }
}
