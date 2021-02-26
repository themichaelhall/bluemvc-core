<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Helpers\TestControllers;

use BlueMvc\Core\Controller;

/**
 * Test controller with various action methods visibility.
 */
class ActionMethodVisibilityTestController extends Controller
{
    /**
     * A public action.
     *
     * @return string The result.
     */
    public function publicAction(): string
    {
        return 'Public action';
    }

    /**
     * A protected action.
     *
     * @return string The result.
     */
    protected function protectedAction(): string
    {
        return 'Protected action';
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */

    /**
     * A private action.
     *
     * @return string The result.
     */
    private function privateAction(): string
    {
        return 'Private action';
    }

    /**
     * A public static action.
     *
     * @return string The result.
     */
    public static function publicStaticAction(): string
    {
        return 'Public static action';
    }

    /**
     * A protected static action.
     *
     * @return string The result.
     */
    protected static function protectedStaticAction(): string
    {
        return 'Protected static action';
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */

    /**
     * A private static action.
     *
     * @return string The result.
     */
    private static function privateStaticAction(): string
    {
        return 'Private static action';
    }

    /**
     * The default action.
     *
     * @param string $action The action.
     *
     * @return string The result.
     */
    public function defaultAction(string $action): string
    {
        return 'Default action: Action=[' . $action . ']';
    }
}
