<?php

/**
 * This file is a part of the bluemvc-core package.
 *
 * Read more at https://bluemvc.com/
 */

declare(strict_types=1);

namespace BlueMvc\Core\Traits;

use BlueMvc\Core\Collections\CustomItemCollection;
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
     * @return mixed The custom item if it exists, null otherwise.
     */
    public function getCustomItem(string $name): mixed
    {
        return $this->getOrCreateCustomItems()->get($name);
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
        return $this->getOrCreateCustomItems();
    }

    /**
     * Sets a custom item.
     *
     * @since 1.0.0
     *
     * @param string $name  The custom item name.
     * @param mixed  $value The custom item value.
     */
    public function setCustomItem(string $name, mixed $value): void
    {
        $this->getOrCreateCustomItems()->set($name, $value);
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
     * Returns the internal custom items collection and creates it beforehand if needed.
     *
     * @return CustomItemCollectionInterface The custom items collection.
     */
    private function getOrCreateCustomItems(): CustomItemCollectionInterface
    {
        if ($this->customItems === null) {
            $this->customItems = new CustomItemCollection();
        }

        return $this->customItems;
    }

    /**
     * @var CustomItemCollectionInterface|null The custom items or null if no custom items is set.
     */
    private ?CustomItemCollectionInterface $customItems = null;
}
