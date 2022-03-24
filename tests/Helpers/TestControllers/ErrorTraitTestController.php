<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Helpers\TestControllers;

use BlueMvc\Core\Controller;
use BlueMvc\Core\Interfaces\ErrorControllerInterface;
use BlueMvc\Core\Traits\ErrorControllerTrait;
use BlueMvc\Core\View;
use DomainException;
use ParseError;
use RuntimeException;

/**
 * Test controller that handles errors via ErrorControllerTrait.
 */
class ErrorTraitTestController extends Controller implements ErrorControllerInterface
{
    use ErrorControllerTrait;

    /**
     * 403 Forbidden action.
     */
    public function _403Action()
    {
        // This emulates the case when there is a bug in the error controller.
        throw new RuntimeException('Exception thrown from 403 action.');
    }

    /**
     * 405 Method not allowed action.
     */
    public function _405Action()
    {
        // This emulates the case when there is a bug in the error controller that throws an error.
        throw new ParseError('Error thrown from 405 action.');
    }

    /**
     * Default action.
     *
     * @param int $statusCode The status code as a string.
     *
     * @return View|null The result.
     */
    public function defaultAction(int $statusCode): ?View
    {
        $errorText = 'Error: ' . $statusCode;
        $throwable = $this->getThrowable();
        if ($throwable !== null) {
            // Use standard error page for DomainException.
            if ($throwable instanceof DomainException) {
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

        $this->getResponse()->addHeader('X-ErrorTrait-PreActionEvent', '1');

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

        $this->getResponse()->addHeader('X-ErrorTrait-PostActionEvent', '1');

        return null;
    }
}
