<?php

declare(strict_types=1);
/**
 * (c) by NFX Media
 */

namespace NFX\InventoryUpdate;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use NFX\InventoryUpdate\Setup\Installer;
use NFX\InventoryUpdate\Setup\Uninstaller;

class NFXInventoryUpdate extends Plugin
{
    /**
     * @param InstallContext $installContext
     */

    public function install(InstallContext $installContext): void
    {
        $installer = new Installer(
            $this->container,
            $installContext
        );

        $installer->install($installContext);
    }

    /**
     * @param UninstallContext $unInstallContext
     */

    public function uninstall(UninstallContext $unInstallContext): void
    {
        $unInstaller = new Uninstaller(
            $this->container,
            $unInstallContext
        );

        $unInstaller->uninstall($unInstallContext);
    }
}
