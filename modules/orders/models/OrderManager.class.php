<?php

class OrderManager
{

    const SESSION_BASKET = 'oBasket';
    /**
     * Current basket
     *
     * @var Order
     */
    protected static $basket;

    /**
     * Retrieve the current basket
     *
     * @return \Order
     */
    public static function getBasket()
    {
        if (!static::$basket) {
            static::$basket = Session::get(static::SESSION_BASKET) ?: new Order();
        }

        return static::$basket;
    }

    /**
     * Empty the basket
     */
    public static function clearBasket()
    {
        static::$basket = null;
        Session::clear(static::SESSION_BASKET);
    }

    /**
     * Commit changes
     */
    public static function commit()
    {
        Session::set(static::SESSION_BASKET, static::getBasket());
    }

    /**
     * get a Order by id
     *
     * @param int $iOrderId
     *
     * @return Order
     */
    public static function getOrderById($iOrderId)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `orders`
                    WHERE
                        `orderId` = ' . db_int($iOrderId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "Order");
    }

    /**
     * return orders filtered by a few options
     *
     * @param array $aFilter    filter properties
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database coloumn name => order) add order by columns and orders
     *
     * @return array NewsItem
     */
    public static function getOrdersByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`o`.`created`' => 'DESC'])
    {
        $sWhere = '';

        if (!empty($aFilter['orderId']) && is_numeric($aFilter['orderId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`o`.`orderId` = ' . db_int($aFilter['orderId']);
        }

        if (!empty($aFilter['status']) && is_numeric($aFilter['status'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`o`.`status` = ' . db_int($aFilter['status']);
        }

        if (!empty($aFilter['statuses']) && is_array($aFilter['statuses'])) {
            $sStatuses = '';
            foreach ($aFilter['statuses'] AS $iStatus) {
                $sStatuses .= ($sStatuses != '' ? ',' : '') . db_int($iStatus);
            }
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`o`.`status` IN (' . $sStatuses . ')';
        }

        # handle order by
        $sOrderBy = '';
        if (count($aOrderBy) > 0) {
            foreach ($aOrderBy AS $sColumn => $sOrder) {
                $sOrderBy .= ($sOrderBy !== '' ? ',' : '') . $sColumn . ' ' . $sOrder;
            }
        }
        $sOrderBy = ($sOrderBy !== '' ? 'ORDER BY ' : '') . $sOrderBy;

        # handle start,limit
        $sLimit = '';
        if (is_numeric($iLimit)) {
            $sLimit .= db_int($iLimit);
        }
        if ($sLimit !== '') {
            $sLimit = (is_numeric($iStart) ? db_int($iStart) . ',' : '0,') . $sLimit;
        }
        $sLimit = ($sLimit !== '' ? 'LIMIT ' : '') . $sLimit;

        $sQuery = ' SELECT ' . ($iFoundRows !== false ? 'SQL_CALC_FOUND_ROWS' : '') . '
                        `o`.*
                    FROM
                        `orders` `o`
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb     = DBConnections::get();
        $aOrders = $oDb->query($sQuery, QRY_OBJECT, "Order");
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aOrders;
    }

    /**
     * save a Order
     *
     * @param Order $oOrder
     */
    public static function saveOrder(Order $oOrder)
    {

        # save the Order
        $sQuery = ' INSERT INTO `orders`(
                        `orderId`,
                        `customerId`,
                        `email`,
                        `invoice_companyName`,
                        `invoice_gender`,
                        `invoice_firstName`,
                        `invoice_insertion`,
                        `invoice_lastName`,
                        `invoice_address`,
                        `invoice_houseNumber`,
                        `invoice_houseNumberAddition`,
                        `invoice_postalCode`,
                        `invoice_city`,
                        `invoice_phone`,
                        `delivery_companyName`,
                        `delivery_gender`,
                        `delivery_firstName`,
                        `delivery_insertion`,
                        `delivery_lastName`,
                        `delivery_address`,
                        `delivery_houseNumber`,
                        `delivery_houseNumberAddition`,
                        `delivery_postalCode`,
                        `delivery_city`,
                        `deliveryMethodId`,
                        `deliveryMethodName`,
                        `deliveryPrice`,
                        `paymentMethodId`,
                        `paymentMethodName`,
                        `paymentPrice`,
                        `totalPriceWithoutTax`,
                        `totalPriceWithTax`,
                        `totalDiscount`,
                        `couponId`,
                        `taxIncluded`,
                        `created`
                    )
                    VALUES (
                        ' . db_int($oOrder->orderId) . ',
                        ' . db_int($oOrder->customerId) . ',
                        ' . db_str($oOrder->email) . ',
                        ' . db_str($oOrder->invoice_companyName) . ',
                        ' . db_str($oOrder->invoice_gender) . ',
                        ' . db_str($oOrder->invoice_firstName) . ',
                        ' . db_str($oOrder->invoice_insertion) . ',
                        ' . db_str($oOrder->invoice_lastName) . ',
                        ' . db_str($oOrder->invoice_address) . ',
                        ' . db_int($oOrder->invoice_houseNumber) . ',
                        ' . db_str($oOrder->invoice_houseNumberAddition) . ',
                        ' . db_str($oOrder->invoice_postalCode) . ',
                        ' . db_str($oOrder->invoice_city) . ',
                        ' . db_str($oOrder->invoice_phone) . ',
                        ' . db_str($oOrder->delivery_companyName) . ',
                        ' . db_str($oOrder->delivery_gender) . ',
                        ' . db_str($oOrder->delivery_firstName) . ',
                        ' . db_str($oOrder->delivery_insertion) . ',
                        ' . db_str($oOrder->delivery_lastName) . ',
                        ' . db_str($oOrder->delivery_address) . ',
                        ' . db_int($oOrder->delivery_houseNumber) . ',
                        ' . db_str($oOrder->delivery_houseNumberAddition) . ',
                        ' . db_str($oOrder->delivery_postalCode) . ',
                        ' . db_str($oOrder->delivery_city) . ',
                        ' . db_int($oOrder->deliveryMethodId) . ',
                        ' . db_str(
                $oOrder->getDeliveryMethod()
                    ->getTranslations()->name
            ) . ',
                        ' . db_str(round($oOrder->getDeliveryPrice(), 4)) . ',
                        ' . db_int($oOrder->paymentMethodId) . ',
                        ' . db_str(
                $oOrder->getPaymentMethod()
                    ->getTranslations()->name
            ) . ',
                        ' . db_str(round($oOrder->getPaymentPrice(), 4)) . ',
                        ' . db_deci($oOrder->getTotal(false)) . ',
                        ' . db_deci($oOrder->getTotal(true)) . ',
                        ' . db_deci(abs($oOrder->getDiscount(Settings::get('taxIncluded')))) . ',
                        ' . db_int($oOrder->couponId) . ',
                        ' . db_int(Settings::get('taxIncluded')) . ',
                        NOW()
                    )
                    ON DUPLICATE KEY UPDATE
                        `customerId`=VALUES(`customerId`),
                        `email`=VALUES(`email`),
                        `invoice_companyName`=VALUES(`invoice_companyName`),
                        `invoice_gender`=VALUES(`invoice_gender`),
                        `invoice_firstName`=VALUES(`invoice_firstName`),
                        `invoice_insertion`=VALUES(`invoice_insertion`),
                        `invoice_lastName`=VALUES(`invoice_lastName`),
                        `invoice_address`=VALUES(`invoice_address`),
                        `invoice_houseNumber`=VALUES(`invoice_houseNumber`),
                        `invoice_houseNumberAddition`=VALUES(`invoice_houseNumberAddition`),
                        `invoice_postalCode`=VALUES(`invoice_postalCode`),
                        `invoice_city`=VALUES(`invoice_city`),
                        `invoice_phone`=VALUES(`invoice_phone`),
                        `delivery_companyName`=VALUES(`delivery_companyName`),
                        `delivery_gender`=VALUES(`delivery_gender`),
                        `delivery_firstName`=VALUES(`delivery_firstName`),
                        `delivery_insertion`=VALUES(`delivery_insertion`),
                        `delivery_lastName`=VALUES(`delivery_lastName`),
                        `delivery_address`=VALUES(`delivery_address`),
                        `delivery_houseNumber`=VALUES(`delivery_houseNumber`),
                        `delivery_houseNumberAddition`=VALUES(`delivery_houseNumberAddition`),
                        `delivery_postalCode`=VALUES(`delivery_postalCode`),
                        `delivery_city`=VALUES(`delivery_city`),
                        `deliveryMethodId`=VALUES(`deliveryMethodId`),
                        `deliveryMethodName`=VALUES(`deliveryMethodName`),
                        `deliveryPrice`=VALUES(`deliveryPrice`),
                        `paymentMethodId`=VALUES(`paymentMethodId`),
                        `paymentMethodName`=VALUES(`paymentMethodName`),
                        `paymentPrice`=VALUES(`paymentPrice`),
                        `totalPriceWithoutTax`=VALUES(`totalPriceWithoutTax`),
                        `totalPriceWithTax`=VALUES(`totalPriceWithTax`),
                        `totalDiscount`=VALUES(`totalDiscount`),
                        `couponId`=VALUES(`couponId`),
                        `taxIncluded`=VALUES(`taxIncluded`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oOrder->orderId === null) {
            $oOrder->orderId = $oDb->insert_id;
        }

        # save the OrderProduct objects
        self::saveOrderProducts($oOrder);

        # redeems the discount coupon if exists
        if (!empty($oOrder->couponId)) {
            $oCoupon = CouponManager::getCouponById($oOrder->couponId);
            CouponManager::redeemCouponByCode($oCoupon->code);
        }

        # you have to save/update the OrderStatus/OrderPayment and stock seperately via the controller (because it's not always needed)
    }

    /**
     * save the OrderProducts of an Order
     *
     * @param Order $oOrder
     */
    public static function saveOrderProducts(Order $oOrder)
    {
        $sDeleteNotIn  = '';
        $sInsertValues = '';
        foreach ($oOrder->getProducts() as $oProduct) {
            if (is_numeric($oProduct->orderProductId)) {
                $sDeleteNotIn .= ($sDeleteNotIn == '' ? '' : ',') . db_int($oProduct->orderProductId);
            }
            $dOriginalPrice = $oProduct->getOriginalPrice(Settings::get("taxIncluded"));

            $sInsertValues .= ($sInsertValues == '' ? '' : ',') . '(' . db_int($oProduct->orderProductId) . ', ' . db_int($oOrder->orderId) . ', ' . db_int($oProduct->catalogProductId) . ', ' . db_str($oProduct->brandName) . ', ' . db_str(
                    $oProduct->productName
                ) . ', ' . db_int($oProduct->amount) . ', ' . db_str($dOriginalPrice) . ', ' . db_str(round($oProduct->getSalePrice(Settings::get("taxIncluded")), 4)) . ', ' . db_str(
                    round($oProduct->getPurchasePrice(Settings::get("taxIncluded")), 4)
                ) . ', ' . db_int($oProduct->getTaxPercentage()) . ', ' . db_int($oProduct->catalogProductSizeId) . ', ' . db_str($oProduct->productSizeName) . ', ' . db_int($oProduct->catalogProductColorId) . ', ' . db_str(
                    $oProduct->productColorName
                ) . ', ' . db_int($oProduct->substractedFromStock) . ')';
        }

        $oDb = DBConnections::get();

        # delete the objects which aren't relevant
        $sQuery = ' DELETE FROM
                        `order_products`
                    WHERE
                        `orderId` = ' . db_int($oOrder->orderId) . '
                        ' . ($sDeleteNotIn == '' ? '' : 'AND `orderProductId` NOT IN (' . $sDeleteNotIn . ')') . '
                    ;';

        $oDb->query($sQuery, QRY_NORESULT);

        # check if there are any objects
        if (count($oOrder->getProducts()) == 0) {
            return;
        }

        # delete the objects, then insert them
        $sQuery = ' INSERT IGNORE INTO `order_products` (
                        `orderProductId`,
                        `orderId`,
                        `catalogProductId`,
                        `brandName`,
                        `productName`,
                        `amount`,
                        `originalPrice`,
                        `salePrice`,
                        `purchasePrice`,
                        `taxPercentage`,
                        `catalogProductSizeId`,
                        `productSizeName`,
                        `catalogProductColorId`,
                        `productColorName`,
                        `substractedFromStock`
                    )
                    VALUES ' . $sInsertValues . '
                    ;';

        $oDb->query($sQuery, QRY_NORESULT);
    }

    /**
     * update the related CatalogProduct objects' stock
     *
     * @param Order   $oOrder
     * @param boolean $bSubstract (substract amount from stock, false will add substracted amount)
     */
    public static function updateCatalogProductStock(Order $oOrder, $bSubstract = true)
    {

        if ($bSubstract) {
            $sQuery = ' UPDATE
                            `catalog_product_size_color_relations` AS `cpscr`
                        JOIN
                            `order_products` AS `op` USING(`catalogProductId`,`catalogProductSizeId`,`catalogProductColorId`)
                        SET
                            `cpscr`.`stock` =
                                CASE
                                    WHEN `cpscr`.`stock` IS NULL
                                    THEN `cpscr`.`stock`
                                    ELSE (`cpscr`.`stock` - (`op`.`amount` - `op`.`substractedFromStock`))
                                END,
                            `op`.`substractedFromStock` =
                                CASE
                                    WHEN `cpscr`.`stock` IS NULL
                                    THEN `op`.`amount`
                                    ELSE `op`.`substractedFromStock` + (`op`.`amount` - `op`.`substractedFromStock`)
                                END
                        WHERE
                            `op`.`orderId` = ' . db_int($oOrder->orderId) . '
                        ;';
        } else {
            $sQuery = ' UPDATE
                            `catalog_product_size_color_relations` AS `cpscr`
                        JOIN
                            `order_products` AS `op` USING(`catalogProductId`,`catalogProductSizeId`,`catalogProductColorId`)
                        SET
                            `cpscr`.`stock` =
                                CASE
                                    WHEN `cpscr`.`stock` IS NULL
                                    THEN `cpscr`.`stock`
                                    ELSE (`cpscr`.`stock` + (`op`.`substractedFromStock`))
                                END,
                            `op`.`substractedFromStock` =
                                CASE
                                    WHEN `cpscr`.`stock` IS NULL
                                    THEN NULL
                                    ELSE 0
                                END
                        WHERE
                            `op`.`orderId` = ' . db_int($oOrder->orderId) . '
                        ;';
        }

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    /**
     * delete Order
     *
     * @param CouponRule $oCouponRule
     *
     * @return bool
     */
    public static function deleteOrder(Order $oOrder)
    {
        $sQuery = " DELETE FROM
                            `orders`
                        WHERE
                            `orderId` = " . db_int($oOrder->orderId) . "
                        LIMIT 1
                        ;";

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        return true;
    }

}

?>