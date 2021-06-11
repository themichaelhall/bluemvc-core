<?php

/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

declare(strict_types=1);

namespace BlueMvc\Core\Traits;

use BlueMvc\Core\Interfaces\Collections\CustomItemCollectionInterface;

/**
 * Trait for custom items functionality.
 *
 * @since 2.2.0
 */
trait CustomItemsTrait
{
    /**
     * Returns a custom item by name if it exists, null otherwise.
     *
     * @since 1.0.0
     *
     * @param string $name The custom item name.
     *
     * @return mixed|null The custom item if it exists, null otherwise.
     */
    public function getCustomItem(string $name)
    {
        return $this->customItems->get($name);
    }

    /**
     * Returns the custom items.
     *
     * @since 1.0.0
     *
     * @return CustomItemCollectionInterface The custom items.
     */
    public function getCustomItems(): CustomItemCollectionInterface
    {
        return $this->customItems;
    }

    /**
     * Sets a custom item.
     *
     * @since 1.0.0
     *
     * @param string $name  The custom item name.
     * @param mixed  $value The custom item value.
     */
    public function setCustomItem(string $name, $value): void
    {
        $this->customItems->set($name, $value);
    }

    /**
     * Sets the custom items.
     *
     * @since 1.0.0
     *
     * @param CustomItemCollectionInterface $customItems The custom items.
     */
    public function setCustomItems(CustomItemCollectionInterface $customItems): void
    {
        $this->customItems = $customItems;
    }

    /**
     * @var CustomItemCollectionInterface My custom items.
     */
    private $customItems;
}
