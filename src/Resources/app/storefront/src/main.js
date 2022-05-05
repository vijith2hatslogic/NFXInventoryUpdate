// Import all necessary Storefront plugins and scss files
import nfxInventoryUpdatePlugin from './js/inventory-update.js';

// Register them via the existing PluginManager
const PluginManager = window.PluginManager;
PluginManager.register('nfxInventoryUpdatePlugin', nfxInventoryUpdatePlugin, '[data-inventory-update="true"]');

