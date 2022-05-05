<?php

declare(strict_types=1);

namespace NFX\InventoryUpdate\Service;


use Shopware\Core\Defaults;
use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Content\Product\DataAbstractionLayer\StockUpdater;
use Shopware\Core\Framework\DataAbstractionLayer\Doctrine\RetryableQuery;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Content\Product\Aggregate\ProductReview\ProductReviewEntity;

class Helper
{
    private $container;
    private $context;
    private $productRepository;
    private $productSalesChannelRepository;
    private Connection $connection;
    private StockUpdater $stockUpdater;


    public function __construct(ContainerInterface $container, Connection $connection, StockUpdater $stockUpdater)
    {
        $this->container = $container;
        $this->connection = $connection;
        $this->context = Context::createDefaultContext();
        $this->productRepository = $this->container->get('product.repository');
        $this->productSalesChannelRepository = $this->container->get('sales_channel.product.repository');
        $this->stockUpdater = $stockUpdater;
    }

    public function getProductByEan(string $ean, SalesChannelContext $context)
    {
        $criteria = (new Criteria());
        $criteria->addFilter(new EqualsFilter('ean', $ean));
        $product = $this->productSalesChannelRepository->search($criteria, $context)->first();

        if (!$product) {
            //throw new ProductNumberNotFoundException($productNumber);
        }

        return $product;
    }

    public function getProductById(string $productId,  SalesChannelContext $context)
    {
        $criteria = (new Criteria());
        $criteria->addFilter(new EqualsFilter('id', $productId));
        $product = $this->productSalesChannelRepository->search($criteria, $context)->first();

        return $product;
    }

    public function updateStock(string $productId, int $stock): bool
    {
        if (empty($productId) || empty($stock)) {
            return false;
        }

        $updateData = [
            'id' => $productId,
            'stock' => $stock,
        ];

        $this->productRepository->update([$updateData], $this->context);
        $this->stockUpdater->update([$productId], $this->context);

        return true;
    }
}
