<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Helpers\TestControllers;

use BlueMvc\Core\Controller;
use DomainException;
use Error;
use LogicException;

/**
 * Test controller that throws an exception.
 */
class ExceptionTestController extends Controller
{
    /**
     * Index action.
     *
     * @throws LogicException
     */
    public function indexAction()
    {
        $this->getResponse()->setHeader('X-Should-Be-Removed', '1');
        $this->getResponse()->setContent('Should also be removed.');

        throw new LogicException('Exception was thrown.');
    }

    /**
     * DomainException action.
     *
     * @throws DomainException
     */
    public function domainExceptionAction()
    {
        throw new DomainException('DomainException was thrown.');
    }

    /**
     * Error action.
     *
     * @throws Error
     */
    public function errorAction()
    {
        throw new Error('Error was thrown.');
    }
}
