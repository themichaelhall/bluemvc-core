<?php

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Helpers\TestControllers;

use BlueMvc\Core\ActionResults\NoContentResult;
use BlueMvc\Core\ActionResults\NotModifiedResult;
use BlueMvc\Core\Controller;
use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Interfaces\ActionResults\ActionResultInterface;
use BlueMvc\Core\Interfaces\ViewInterface;
use BlueMvc\Core\Interfaces\ViewOrActionResultInterface;
use BlueMvc\Core\Tests\Helpers\TestClasses\SimpleTestClass;
use BlueMvc\Core\Tests\Helpers\TestClasses\StringableTestClass;
use BlueMvc\Core\View;

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
    public function indexAction(): string
    {
        return 'Hello World!';
    }

    /**
     * Server error action.
     *
     * @return string The result.
     */
    public function serverErrorAction(): string
    {
        $this->getResponse()->setStatusCode(new StatusCode(StatusCode::INTERNAL_SERVER_ERROR));

        return 'Server Error';
    }

    /**
     * Action starting with a numeric character.
     *
     * @return string The result.
     */
    public function _123numericAction(): string
    {
        return 'Numeric action result';
    }

    /**
     * Action returning an integer.
     *
     * @return int The result.
     */
    public function intAction(): int
    {
        return 42;
    }

    /**
     * Action returning false.
     *
     * @return bool The result.
     */
    public function falseAction(): bool
    {
        return false;
    }

    /**
     * Action returning true.
     *
     * @return bool The result.
     */
    public function trueAction(): bool
    {
        return true;
    }

    /**
     * Action returning null.
     *
     * @return null The result.
     */
    public function nullAction()
    {
        $this->getResponse()->setContent('Content set manually.');

        return null;
    }

    /**
     * Action returning an object.
     *
     * @return SimpleTestClass The result.
     */
    public function objectAction(): SimpleTestClass
    {
        return new SimpleTestClass('Foo');
    }

    /**
     * Action returning a stringable object.
     *
     * @return StringableTestClass The result.
     */
    public function stringableAction(): StringableTestClass
    {
        return new StringableTestClass('Bar');
    }

    /**
     * Action returning an action result.
     *
     * @return ActionResultInterface The result.
     */
    public function actionResultAction(): ActionResultInterface
    {
        return new NotModifiedResult();
    }

    /**
     * Action returning a view.
     *
     * @return ViewInterface The result.
     */
    public function viewAction(): ViewInterface
    {
        return new View();
    }

    /**
     * Action returning a view or action result.
     *
     * @return ViewOrActionResultInterface The result.
     */
    public function viewOrActionResultAction(): ViewOrActionResultInterface
    {
        if ($this->getRequest()->getQueryParameter('showView') === '1') {
            return new View();
        }

        return new NoContentResult();
    }
}
