<?php

declare(strict_types=1);

namespace NFX\InventoryUpdate\Storefront\Controller;

use NFX\InventoryUpdate\Service\Helper;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Shopware\Core\Content\Product\ProductEntity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Shopware\Storefront\Controller\StorefrontController;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use NFX\InventoryUpdate\Storefront\Page\InventoryUpdate\InventoryUpdatePageLoader;

/**
 * @RouteScope(scopes={"storefront"})
 */
class InventoryUpdateController extends StorefrontController
{
    private Helper $helper;
    private Context $context;


    public function __construct(InventoryUpdatePageLoader $inventoryUpdatePageLoader, Helper $helper)
    {
        $this->inventoryUpdatePageLoader = $inventoryUpdatePageLoader;
        $this->helper = $helper;
        $this->context = Context::createDefaultContext();
    }

    /**
     * @Route("/product/inventory-update", name="frontend.product.inventory.update", methods={"GET"}, defaults={"csrf_protected"=false, "XmlHttpRequest"=true})
     */
    public function index(Request $request, SalesChannelContext $context): Response
    {

        $templatePage = $this->inventoryUpdatePageLoader->load($request, $context);

        return $this->renderStorefront(
            '@Storefront/storefront/page/inventory-update/index.html.twig',
            ['page' => $templatePage]
        );
    }

    /**
     * @Route("/product/ean-search", 
     * name="frontend.product.ean.search", 
     * methods={"POST"}, defaults={"csrf_protected"=false, "XmlHttpRequest"=true})
     */
    public function getProduct(Request $request, RequestDataBag $requestDataBag, SalesChannelContext $context): Response
    {
        $ean = $requestDataBag->get('eanNumber');

        $res = [
            'status' => false,
            'content' => '',
            'message' => '',
        ];

        if (empty($ean)) {
            $res['message'] = $this->trans('inventoryUpdate.errors.eanRequired');
        }

        if ($ean) {
            $product = $this->helper->getProductByEan($ean, $context);

            if (!empty($product)) {
                $res['status'] = true;
                $inventoryForm = $this->renderStorefront('storefront/page/inventory-update/update-form.html.twig', [
                    'product' => $product
                ])->getContent();

                $res['content'] = $inventoryForm;
            } else {
                $res['message'] = $this->trans('inventoryUpdate.errors.productNotFound');
            }
        }

        return new JsonResponse($res);
    }

    /**
     * @Route("/product/update-stock", 
     * name="frontend.product.update.stock", 
     * methods={"POST"}, defaults={"csrf_protected"=false, "XmlHttpRequest"=true})
     */
    public function updateStock(Request $request, RequestDataBag $requestDataBag, SalesChannelContext $context): Response
    {
        $productId = $requestDataBag->get('productId');
        $stock = (int) $requestDataBag->get('stock');
        $currentStock = (int) $requestDataBag->get('currentStock');

        $res = [
            'status' => false,
            'content' => '',
            'message' => '',
        ];

        if (empty($productId)) {
            $res['message'] = $this->trans('inventoryUpdate.errors.productNotFound');
        }

        if ($productId) {
            $stockUpdate = $currentStock + $stock;
            $status = $this->helper->updateStock($productId, $stockUpdate);
            $product = $this->helper->getProductById($productId, $context);

            $inventoryForm = $this->renderStorefront('storefront/page/inventory-update/update-form.html.twig', [
                'product' => $product
            ])->getContent();

            if (!empty($status)) {
                $availableStock = $product->getAvailableStock();

                $res['status'] = true;
                $res['message'] = $this->trans('inventoryUpdate.label.productStockUpdated', ['%stock%' => $stock, '%availableStock%' => $availableStock]);
            } else {
                $res['message'] = $this->trans('inventoryUpdate.errors.stockUpdateFailed');
            }

            $res['content'] = $inventoryForm;
        }

        return new JsonResponse($res);
    }

    /**
     * @Route("/product/inventory-update/back-to-search", name="frontend.product.inventory.update.back.to.search", methods={"post"}, defaults={"csrf_protected"=false, "XmlHttpRequest"=true})
     */
    public function backToSearchForm(Request $request, RequestDataBag $requestDataBag, SalesChannelContext $context): Response
    {
        $res = [
            'status' => true,
            'content' => '',
            'message' => '',
        ];

        $action = $requestDataBag->get('action');

        $res['content'] = $this->renderStorefront('@Storefront/storefront/page/inventory-update/ean-search-form.html.twig')->getContent();

        return new JsonResponse($res);
    }
}
