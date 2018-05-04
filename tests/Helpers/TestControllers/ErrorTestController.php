<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Helpers\TestControllers;

use BlueMvc\Core\ErrorController;
use BlueMvc\Core\View;

/**
 * Test controller that handles errors.
 */
class ErrorTestController extends ErrorController
{
    /**
     * 403 Forbidden action.
     */
    public function _403Action()
    {
        // This emulates the case when there is a bug in the error controller.
        throw new \RuntimeException('Exception thrown from 403 action.');
    }

    /**
     * Default action.
     *
     * @param string $statusCode The status code as a string.
     *
     * @return View The result.
     */
    public function defaultAction($statusCode)
    {
        $errorText = 'Error: ' . $statusCode;
        $throwable = $this->getThrowable();
        if ($throwable !== null) {
            // Use standard error page for DomainException.
            if ($throwable instanceof \DomainException) {
                return null;
            }

            $errorText .= ', Throwable: ' . get_class($throwable) . ', ThrowableMessage: ' . $throwable->getMessage();
        }

        return new View($errorText);
    }

    /**
     * Pre action event.
     *
     * @return null
     */
    protected function onPreActionEvent()
    {
        parent::onPreActionEvent();

        $this->getResponse()->addHeader('X-Error-PreActionEvent', '1');

        return null;
    }

    /**
     * Post action event.
     *
     * @return null
     */
    protected function onPostActionEvent()
    {
        parent::onPostActionEvent();

        $this->getResponse()->addHeader('X-Error-PostActionEvent', '1');

        return null;
    }
}
