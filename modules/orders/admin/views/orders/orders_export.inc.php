<?php

$sExport = '<table>';
$sExport .= '<tr>'
    . '<td>' . sysTranslations::get('order_order_name') . '</td>'
    . '<td>' . sysTranslations::get('global_name') . ' (' . sysTranslations::get('order_invoice_address') . ')</td>'
    . '<td>' . sysTranslations::get('order_street') . ' (' . sysTranslations::get('order_invoice_address') . ')</td>'
    . '<td>' . sysTranslations::get('order_address') . ' (' . sysTranslations::get('order_invoice_address') . ')</td>'
    . '<td>' . sysTranslations::get('global_postal_code') . ' (' . sysTranslations::get('order_invoice_address') . ')</td>'
    . '<td>' . sysTranslations::get('order_city') . ' (' . sysTranslations::get('order_invoice_address') . ')</td>'
    . '<td>' . sysTranslations::get('global_name') . ' (' . sysTranslations::get('order_delivery_address') . ')</td>'
    . '<td>' . sysTranslations::get('order_street') . ' (' . sysTranslations::get('order_delivery_address') . ') </td>'
    . '<td>' . sysTranslations::get('order_address') . ' (' . sysTranslations::get('order_delivery_address') . ')</td>'
    . '<td>' . sysTranslations::get('global_postal_code') . ' (' . sysTranslations::get('order_delivery_address') . ')</td>'
    . '<td>' . sysTranslations::get('order_city') . ' (' . sysTranslations::get('order_delivery_address') . ')</td>'
    . '<td>' . sysTranslations::get('global_email') . '</td>'
    . '<td>' . sysTranslations::get('order_payment_method') . '</td>'
    . '<td>' . sysTranslations::get('order_payment_status') . '</td>'
    . '<td>' . sysTranslations::get('order_delivery_method') . '</td>'
    . '<td>' . sysTranslations::get('order_delivery_cost') . '' . ($bWithTax ? '(' . sysTranslations::get('global_inc_taxes') . ')' : ' (' . sysTranslations::get('global_excl_taxes') . ')') . '</td>'
    . '<td>' . sysTranslations::get('order_invoice_amount') . ($bWithTax ? '(' . sysTranslations::get('global_inc_taxes') . ')' : ' (' . sysTranslations::get('global_excl_taxes') . ')') . '</td>'
    . '<td>' . sysTranslations::get('global_discount') . '</td>'

    // Product information
    . '<td>' . sysTranslations::get('global_brand') . '</td>'
    . '<td>' . sysTranslations::get('order_product_name') . '</td>'
    . '<td>' . sysTranslations::get('catalog_product_number') . '</td>'
    . '<td>' . sysTranslations::get('global_size') . '</td>'
    . '<td>' . sysTranslations::get('global_amount') . '</td>'
    . '<td>' . sysTranslations::get('order_unit_price') . ' ' . ($bWithTax ? '(' . sysTranslations::get('global_inc_taxes') . ')' : ' (' . sysTranslations::get('global_excl_taxes') . ')') . '</td>'
    . '<td>' . sysTranslations::get('global_total') . '' . ($bWithTax ? '(' . sysTranslations::get('global_inc_taxes') . ')' : ' (' . sysTranslations::get('global_excl_taxes') . ')') . '</td>'
    . '<td>' . sysTranslations::get('catalog_purchase_price') . ' ' . ($bWithTax ? '(' . sysTranslations::get('global_inc_taxes') . ')' : ' (' . sysTranslations::get('global_excl_taxes') . ')') . '</td>'
    . '<td>' . sysTranslations::get('catalog_margin') . ' ' . ($bWithTax ? '(' . sysTranslations::get('global_inc_taxes') . ')' : ' (' . sysTranslations::get('global_excl_taxes') . ')') . '</td>'
    . '</tr>';

foreach ($aOrders as $oOrder) {
    $new_order = true;
    foreach (OrderProductManager::getProductsByOrderId($oOrder->orderId) as $oOrderProduct) {
        $sExport .= '<tr>';
        // Order header details, only if it's the first product of the order
        if ($new_order) {
            $sExport .= '<td></td>'
                . '<td>' . $oOrder->orderId . '</td>'
                . '<td>' . $oOrder->invoice_firstName . ' ' . $oOrder->invoice_lastName . '</td>'
                . '<td>' . $oOrder->invoice_address . '</td>'
                . '<td>' . $oOrder->invoice_houseNumber . '' . $oOrder->invoice_houseNumberAddition . '</td>'
                . '<td>' . $oOrder->invoice_postalCode . '</td>'
                . '<td>' . $oOrder->invoice_city . '</td>'
                . '<td>' . $oOrder->delivery_firstName . ' ' . $oOrder->delivery_lastName . '</td>'
                . '<td>' . $oOrder->delivery_address . '</td>'
                . '<td>' . $oOrder->delivery_houseNumber . '' . $oOrder->delivery_houseNumberAddition . '</td>'
                . '<td>' . $oOrder->delivery_postalCode . '</td>'
                . '<td>' . $oOrder->delivery_city . '</td>'
                . '<td>' . $oOrder->email . '</td>'
                . '<td>' . $oOrder->deliveryMethodName . '</td>'
                . '<td>' . OrderStatusManager::getLabelByStatus($oOrder->status) . '</td>'
                . '<td>' . $oOrder->getDeliveryPrice() . '</td>'
                . '<td>' . $oOrderProduct->brandName . '</td>'
                . '<td>' . $oOrder->totalDiscount . '</td>';
        } else {
            $sExport .= '<td></td>'
                . '<td></td>'
                . '<td></td>'
                . '<td></td>'
                . '<td></td>'
                . '<td></td>'
                . '<td></td>'
                . '<td></td>'
                . '<td></td>'
                . '<td></td>'
                . '<td></td>'
                . '<td></td>'
                . '<td></td>'
                . '<td></td>'
                . '<td></td>'
                . '<td></td>'
                . '<td></td>'
                . '<td></td>';
        }
        // Order product details
        $sExport   .= '<td>' . $oOrderProduct->brandName . '</td>'
            . '<td>' . $oOrderProduct->productName . '</td>'
            . '<td>' . $oOrderProduct->catalogProductId . '</td>'
            . '<td>' . $oOrderProduct->productSizeName . '</td>'
            . '<td>' . $oOrderProduct->amount . '</td>'
            . '<td>' . ($oOrderProduct->getSalePrice($bWithTax)) . '</td>'
            . '<td>' . (($oOrderProduct->getSalePrice($bWithTax)) * $oOrderProduct->amount) . '</td>'
            . '<td>' . ($oOrderProduct->getPurchasePrice($bWithTax)) . '</td>'
            . '<td>' . ($oOrderProduct->getSalePrice($bWithTax) - ($oOrderProduct->getPurchasePrice($bWithTax))) . '</td>';
        $new_order = false;
        $sExport   .= '</tr>';
    }
}
$sExport .= '</table>';

$sFileName = 'orderexport_' . date('Ymd_His');
$sFileName .= $bWithTax ? '_' . sysTranslations::get('global_inc_taxes') : '_' . sysTranslations::get('global_excl_taxes');

header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename=' . $sFileName . '.xls');
header('Pragma: no-cache');
header('Expires: 0');

?>

