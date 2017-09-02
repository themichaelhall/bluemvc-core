<?php

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
    public function publicAction()
    {
        return 'Public action';
    }

    /**
     * A protected action.
     *
     * @return string The result.
     */
    protected function protectedAction()
    {
        return 'Protected action';
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */

    /**
     * A private action.
     *
     * @return string The result.
     */
    private function privateAction()
    {
        return 'Private action';
    }

    /**
     * A public static action.
     *
     * @return string The result.
     */
    public static function publicStaticAction()
    {
        return 'Public static action';
    }

    /**
     * A protected static action.
     *
     * @return string The result.
     */
    protected static function protectedStaticAction()
    {
        return 'Protected static action';
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */

    /**
     * A private static action.
     *
     * @return string The result.
     */
    private static function privateStaticAction()
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
    public function defaultAction($action)
    {
        return 'Default action: Action=[' . $action . ']';
    }
}
