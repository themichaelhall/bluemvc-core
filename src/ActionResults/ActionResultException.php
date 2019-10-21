<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */
declare(strict_types=1);

namespace BlueMvc\Core\ActionResults;

use BlueMvc\Core\Interfaces\ActionResults\ActionResultExceptionInterface;
use BlueMvc\Core\Interfaces\ActionResults\ActionResultInterface;
use Exception;

/**
 * Class representing a generic action result exception.
 *
 * @since 2.1.0
 */
class ActionResultException extends Exception implements ActionResultExceptionInterface
{
    /**
     * Constructs the action result exception.
     *
     * @since 2.1.0
     *
     * @param ActionResultInterface $actionResult
     */
    public function __construct(ActionResultInterface $actionResult)
    {
        parent::__construct();

        $this->actionResult = $actionResult;
    }

    /**
     * Returns the action result.
     *
     * @since 2.1.0
     *
     * @return ActionResultInterface The action result.
     */
    public function getActionResult(): ActionResultInterface
    {
        return $this->actionResult;
    }

    /**
     * @var ActionResultInterface My action result.
     */
    private $actionResult;
}
