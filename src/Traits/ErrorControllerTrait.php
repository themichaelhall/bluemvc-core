<?php

/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

declare(strict_types=1);

namespace BlueMvc\Core\Traits;

use Throwable;

/**
 * Trait for error controller functionality.
 *
 * @since 1.1.0
 */
trait ErrorControllerTrait
{
    /**
     * Returns the throwable or null if no throwable.
     *
     * @since 2.0.0
     *
     * @return Throwable|null The throwable or null if no throwable.
     */
    public function getThrowable(): ?Throwable
    {
        return $this->throwable;
    }

    /**
     * Sets the throwable.
     *
     * @since 2.0.0
     *
     * @param Throwable $throwable The throwable.
     */
    public function setThrowable(Throwable $throwable): void
    {
        $this->throwable = $throwable;
    }

    /**
     * Returns true if post-action event is enabled, false otherwise.
     *
     * @since 1.1.0
     *
     * @return bool True if post-action event is enabled, false otherwise.
     */
    protected function isPostActionEventEnabled(): bool
    {
        return $this->throwable === null;
    }

    /**
     * Returns true if pre-action event is enabled, false otherwise.
     *
     * @since 1.1.0
     *
     * @return bool True if pre-action event is enabled, false otherwise.
     */
    protected function isPreActionEventEnabled(): bool
    {
        return $this->throwable === null;
    }

    /**
     * @var Throwable|null My throwable or null if no throwable.
     */
    private $throwable = null;
}
