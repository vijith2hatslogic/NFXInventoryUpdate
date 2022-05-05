<?php

declare(strict_types=1);

namespace NFX\InventoryUpdate\Setup;

use Doctrine\DBAL\Connection;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Installer
{
    /**
     * @var ContainerInterface
     */
    protected $container;
    protected InstallContext $installContext;

    /**
     * Installer constructor.
     *
     * @param ContainerInterface        $container
     * @param InstallContext $installContext
     */
    public function __construct(
        ContainerInterface $container,
        InstallContext $installContext
    ) {
        $this->container = $container;
    }

    /**
     * @return bool
     */
    public function install()
    {
        //$this->createCustomFields();
        return true;
    }
}
