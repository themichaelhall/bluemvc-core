<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */
namespace BlueMvc\Core;

use BlueMvc\Core\Base\AbstractController;
use BlueMvc\Core\Interfaces\ApplicationInterface;
use BlueMvc\Core\Interfaces\RequestInterface;
use BlueMvc\Core\Interfaces\ResponseInterface;
use BlueMvc\Core\Interfaces\RouteMatchInterface;
use BlueMvc\Core\Interfaces\ViewInterface;
use DataTypes\FilePath;

/**
 * Class representing a standard controller.
 *
 * @since 1.0.0
 */
abstract class Controller extends AbstractController
{
    /**
     * Processes a request.
     *
     * @since 1.0.0
     *
     * @param ApplicationInterface $application The application.
     * @param RequestInterface     $request     The request.
     * @param ResponseInterface    $response    The response.
     * @param RouteMatchInterface  $routeMatch  The route match.
     *
     * @return bool True if request was actually processed, false otherwise.
     */
    public function processRequest(ApplicationInterface $application, RequestInterface $request, ResponseInterface $response, RouteMatchInterface $routeMatch)
    {
        parent::processRequest($application, $request, $response, $routeMatch);

        $action = $routeMatch->getAction();
        if ($action === '') {
            $action = 'index';
        }

        if ($this->tryInvokeActionMethod($action, [], $result)) {
            if ($result instanceof ViewInterface) {
                // A view was returned. Figure out the path to the view file(s) to try.
                $controllerName = (new \ReflectionClass($this))->getShortName();
                if (strlen($controllerName) > 10 && substr(strtolower($controllerName), -10) === 'controller') {
                    $controllerName = substr($controllerName, 0, -10);
                }

                $actionName = $action; // fixme: validate characters

                // Try the view renderers until a match is found.
                // fixme: Exception if no view renderers are found.
                foreach ($application->getViewRenderers() as $viewRenderer) {
                    $viewFile = FilePath::parse($controllerName . DIRECTORY_SEPARATOR . $actionName . '.' . $viewRenderer->getViewFileExtension());
                    $fullViewFile = $application->getViewPath()->withFilePath($viewFile);

                    if (file_exists($fullViewFile->__toString())) {
                        $response->setContent($viewRenderer->renderView($application->getViewPath(), $viewFile, $result->getModel()));

                        break;
                    }
                }
            } else {
                $response->setContent($result);
            }

            return true;
        }

        return false;
    }
}
