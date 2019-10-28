<?php

/** @noinspection PhpDeprecationInspection */

declare(strict_types=1);

namespace BlueMvc\Core\Tests\Helpers\TestControllers;

use BlueMvc\Core\ActionResults\ActionResult;
use BlueMvc\Core\ActionResults\ActionResultException;
use BlueMvc\Core\Base\AbstractController;
use BlueMvc\Core\Http\StatusCode;
use BlueMvc\Core\Interfaces\ActionResults\ActionResultExceptionInterface;
use BlueMvc\Core\Interfaces\ApplicationInterface;
use BlueMvc\Core\Interfaces\RequestInterface;
use BlueMvc\Core\Interfaces\ResponseInterface;
use stdClass;

/**
 * Controller that uses the deprecated AbstractController class.
 */
class DeprecatedTestController extends AbstractController
{
    /**
     * index action.
     */
    public function INDEXAction(): void
    {
        $this->getResponse()->setContent('Index Action');
    }

    /**
     * Action with one mandatory parameter.
     *
     * @param string $parameter The mandatory parameter.
     *
     * @throws ActionResultExceptionInterface
     */
    public function fooAction(string $parameter): void
    {
        $this->getResponse()->setContent('Foo Action (' . $parameter . ')');

        throw new ActionResultException(new ActionResult('', new StatusCode(StatusCode::OK)));
    }

    /**
     * Action with numeric name.
     */
    public function _123Action(): void
    {
        $this->getResponse()->setContent('123 Action');
    }

    /**
     * Action with parameters.
     *
     * @param mixed         $parameter1
     * @param int           $parameter2
     * @param float         $parameter3
     * @param bool          $parameter4
     * @param string        $parameter5
     * @param stdClass|null $parameter6
     */
    public function parametersAction($parameter1, int $parameter2, float $parameter3, bool $parameter4 = false, string $parameter5 = 'FooBar', stdClass $parameter6 = null)
    {
        $this->getResponse()->setContent('Parameters Action (' . $parameter1 . ',' . $parameter2 . ',' . $parameter3 . ',' . ($parameter4 ? 'true' : 'false') . ',' . $parameter5 . ',' . ($parameter6 !== null ? 'stdClass' : 'null') . ')');
    }

    /**
     * On pre-action event.
     *
     * @throws ActionResultExceptionInterface
     *
     * @return mixed
     */
    protected function onPreActionEvent()
    {
        parent::onPreActionEvent();

        if ($this->getRequest()->getQueryParameter('return-from-pre-action-event') !== null) {
            $this->getResponse()->setContent('Return from Pre Action Event');

            return true;
        }

        if ($this->getRequest()->getQueryParameter('throw-from-pre-action-event') !== null) {
            $this->getResponse()->setContent('Throw from Pre Action Event');

            throw new ActionResultException(new ActionResult('', new StatusCode(StatusCode::OK)));
        }

        return null;
    }

    /**
     * On post-action event.
     *
     * @throws ActionResultExceptionInterface
     *
     * @return mixed
     */
    protected function onPostActionEvent()
    {
        parent::onPostActionEvent();

        if ($this->getRequest()->getQueryParameter('return-from-post-action-event') !== null) {
            $this->getResponse()->setContent('Return from Post Action Event');

            return true;
        }

        if ($this->getRequest()->getQueryParameter('throw-from-post-action-event') !== null) {
            $this->getResponse()->setContent('Throw from Post Action Event');

            throw new ActionResultException(new ActionResult('', new StatusCode(StatusCode::OK)));
        }

        return null;
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */

    /**
     * Private action.
     */
    private function barAction(): void
    {
        $this->getResponse()->setContent('Bar Action');
    }

    /**
     * DeprecatedTestController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Processes a request.
     *
     * @param ApplicationInterface $application The application.
     * @param RequestInterface     $request     The request.
     * @param ResponseInterface    $response    The response.
     * @param string               $action      The action.
     * @param array                $parameters  The parameters.
     */
    public function processRequest(ApplicationInterface $application, RequestInterface $request, ResponseInterface $response, string $action, array $parameters = []): void
    {
        parent::processRequest($application, $request, $response, $action, $parameters);

        $isIndex = $action === '';
        $actionName = $isIndex ? 'index' : $action;

        if (!$this->tryInvokeActionMethod($actionName, $parameters, !$isIndex, $result, $hasFoundActionMethod)) {
            $response->setStatusCode(new StatusCode(StatusCode::NOT_FOUND));
        }
    }
}
