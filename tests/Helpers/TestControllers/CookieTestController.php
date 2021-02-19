<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Helpers\TestControllers;

use BlueMvc\Core\Controller;

/**
 * Cookie test controller class.
 */
class CookieTestController extends Controller
{
    /**
     * Index action.
     *
     * @return string The result.
     */
    public function indexAction(): string
    {
        $result = [];

        foreach ($this->getRequest()->getCookies() as $name => $cookie) {
            $result[] = $name . '=' . $cookie;
        }

        return implode(',', $result);
    }
}
