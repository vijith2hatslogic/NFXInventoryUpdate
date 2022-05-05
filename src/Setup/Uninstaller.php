<?php

declare(strict_types=1);

namespace NFX\InventoryUpdate\Setup;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;

class Uninstaller
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    protected UninstallContext $uninstallContext;

    /**
     * Uninstaller constructor.
     *
     * @param ContainerInterface  $container
     * @param UninstallContext $uninstallContext
     */
    public function __construct(
        ContainerInterface $container,
        UninstallContext $uninstallContext
    ) {
        $this->container = $container;
    }

    /**
     * @param UninstallContext $uninstallContext
     */
    public function uninstall(UninstallContext $uninstallContext): void
    {
        if ($uninstallContext->keepUserData()) {
            return;
        }

        //$this->removeCustomFields();

        return;
    }
}
