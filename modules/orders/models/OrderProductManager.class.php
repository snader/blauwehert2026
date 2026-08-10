<?php

class OrderProductManager
{

    /**
     * get all OrderProduct objects related to an Order
     *
     * @param int $iOrderId
     *
     * @return array OrderProduct
     */
    public static function getProductsByOrderId($iOrderId)
    {
        # define the query
        $sQuery = ' SELECT
                        *
                    FROM
                        `order_products`
                    WHERE
                        `orderId` = ' . db_int($iOrderId) . '
                    ORDER BY
                        `brandName` ASC,
                        `productName` ASC
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "OrderProduct");
    }

}

?>