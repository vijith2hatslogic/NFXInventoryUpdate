<?php

declare(strict_types=1);

namespace NFX\InventoryUpdate\Storefront\Page\InventoryUpdate;

use Shopware\Storefront\Page\Page;
use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Storefront\Framework\Page\StorefrontSearchResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;

/**
 * Class for set and get the InventoryUpdate page.
 */
class InventoryUpdatePage extends Page
{

    /**
     * @var ProductEntity|null
     */
    protected $product;
    
    /**
     * @var array|null
     */
    protected $salesChannels;

    public function getProduct(): ?ProductEntity
    {
        return $this->product;
    }

    public function setProduct(?ProductEntity $product): void
    {
        $this->product = $product;
    }

    public function getSalesChannels(): ?array
    {
        return $this->salesChannels;
    }

    public function setSalesChannels(?array $salesChannels): void
    {
        $this->salesChannels = $salesChannels;
    }
}
