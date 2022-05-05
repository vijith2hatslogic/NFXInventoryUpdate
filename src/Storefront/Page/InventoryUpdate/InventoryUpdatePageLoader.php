<?php

declare(strict_types=1);

namespace NFX\InventoryUpdate\Storefront\Page\InventoryUpdate;

use Shopware\Core\Content\Property\Aggregate\PropertyGroupOption\PropertyGroupOptionCollection;
use Shopware\Core\Content\Product\SalesChannel\CrossSelling\AbstractProductCrossSellingRoute;
use Shopware\Core\Framework\DataAbstractionLayer\Exception\InconsistentCriteriaIdsException;
use NFX\InventoryUpdate\Storefront\Page\InventoryUpdate\InventoryUpdatePageLoadedEvent;
use Shopware\Core\Content\Product\SalesChannel\Listing\AbstractProductListingRoute;
use Shopware\Core\Content\Cms\DataResolver\ResolverContext\EntityResolverContext;
use Shopware\Core\Content\Product\SalesChannel\Detail\AbstractProductDetailRoute;
use Shopware\Core\Framework\Routing\Exception\MissingRequestParameterException;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use NFX\InventoryUpdate\Storefront\Page\InventoryUpdate\InventoryUpdatePage;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Page\GenericPageLoaderInterface;
use Symfony\Component\HttpFoundation\Request;

class InventoryUpdatePageLoader
{
    private GenericPageLoaderInterface $genericLoader;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        GenericPageLoaderInterface $genericLoader,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->genericLoader = $genericLoader;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function load(Request $request, SalesChannelContext $context): InventoryUpdatePage
    {

        $page = $this->genericLoader->load($request, $context);
        $page = InventoryUpdatePage::createFrom($page);

        $this->eventDispatcher->dispatch(
            new InventoryUpdatePageLoadedEvent($page, $context, $request)
        );

        return $page;
    }
}
