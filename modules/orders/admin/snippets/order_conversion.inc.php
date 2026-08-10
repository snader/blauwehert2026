<?php
/**
 * @var \Order $oOrder
 * @var \OrderPayment $oOrderPayment
 * @var \CatalogProductCategory $oOrderProductCategory
 * add javascript and/or HTML to measure conversion or sale
 */

$oOrder = $oOrderPayment->getOrder();
if (ENVIRONMENT == 'production') {
    ?>
    <script>
        window.dataLayer = window.dataLayer || [];
        dataLayer.push({
            'transactionId': '<?= $oOrder->orderId ?>',
            'transactionAffiliation': '<?= CLIENT_NAME ?>',
            'transactionTotal': <?= number_format($oOrder->getTotal(true), 2, '.', '') ?>,
            'transactionTax': <?= number_format($oOrder->getBTW(), 2, '.', '') ?>, // Tax.
            'transactionShipping': <?= number_format($oOrder->getDeliveryCosts(true) + $oOrder->getPaymentCosts(true), 2, '.', '') ?>, // Shipping.
            'transactionProducts': [
                <?php
                $iProductCounter = 0;
                /** @var \OrderProduct $oOrderProduct */
                foreach ($oOrder->getProducts() AS $oOrderProduct) {
                $oProductSizeColorRelation = CatalogProductSizeColorRelationManager::getCatalogProductSizeColorRelation($oOrderProduct->catalogProductId, $oOrderProduct->catalogProductSizeId, $oOrderProduct->catalogProductColorId);
                $sMPN = '';
                if ($oProductSizeColorRelation) {
                    $sMPN = $oProductSizeColorRelation->getMPN();
                }

                $oProduct = $oOrderProduct->getProduct();
                $aOrderProductCategories = $oProduct->getCategories();

                $sCategoryName = '';
                if (!empty($aOrderProductCategories)) {
                    foreach ($aOrderProductCategories as $oOrderProductCategory) {
                        $oProductCategory = $oOrderProductCategory->getTranslations();
                        if (!empty($oProductCategory)) {
                            $sCategoryName = $oProductCategory->name;
                            break;
                        }
                    }
                }
                ?>
                {
                    'sku': '<?= $sMPN ? $sMPN : $oProductSizeColorRelation->catalogProductId . ' - ' . $oProductSizeColorRelation->catalogProductSizeId . ' - ' . $oProductSizeColorRelation->catalogProductColorId  ?>',
                    'name': '<?= str_replace('"', '',$oOrderProduct->brandName . ' - ' . $oOrderProduct->productName) ?>', // Product name. Required.
                    'category': '<?= $sCategoryName ?>',
                    'price': <?= number_format($oOrderProduct->getSalePrice(true), 2, '.', '') ?>, // Unit price.
                    'quantity': <?= $oOrderProduct->amount ?> // Quantity.
                }<?= (++$iProductCounter < count($oOrder->getProducts()) ? ',' : '') ?>
                <?php
                }
                ?>
            ]
        });
    </script>
    <?php
}