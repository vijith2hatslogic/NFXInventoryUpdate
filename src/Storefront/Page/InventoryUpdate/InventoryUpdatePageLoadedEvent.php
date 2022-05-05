<?php declare(strict_types=1);

namespace NFX\InventoryUpdate\Storefront\Page\InventoryUpdate;

use Shopware\Core\System\SalesChannel\SalesChannelContext;
use NFX\InventoryUpdate\Storefront\Page\InventoryUpdate\InventoryUpdatePage;
use Shopware\Storefront\Page\PageLoadedEvent;
use Symfony\Component\HttpFoundation\Request;

class InventoryUpdatePageLoadedEvent extends PageLoadedEvent
{
    /**
     * @var InventoryUpdatePage
     */
    protected $page;

    public function __construct(InventoryUpdatePage $page, SalesChannelContext $salesChannelContext, Request $request)
    {
        $this->page = $page;
        parent::__construct($salesChannelContext, $request);
    }

    public function getPage(): InventoryUpdatePage
    {
        return $this->page;
    }
}
