<?php
/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */
declare(strict_types=1);

namespace BlueMvc\Core\Interfaces\ActionResults;

/**
 * Interface for ActionResultException class.
 *
 * @since 2.1.0
 */
interface ActionResultExceptionInterface
{
    /**
     * Returns the action result.
     *
     * @since 2.1.0
     *
     * @return ActionResultInterface The action result.
     */
    public function getActionResult(): ActionResultInterface;
}
