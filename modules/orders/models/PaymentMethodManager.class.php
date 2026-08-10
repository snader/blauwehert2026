<?php

class PaymentMethodManager
{

    /**
     * get a PaymentMethod by id
     *
     * @param int $iPaymentMethodId
     *
     * @return PaymentMethod
     */
    public static function getPaymentMethodById($iPaymentMethodId)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `payment_methods`
                    WHERE
                        `paymentMethodId` = ' . db_int($iPaymentMethodId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "PaymentMethod");
    }

    /**
     * get a PaymentMethod by name
     *
     * @param int $sName
     *
     * @return PaymentMethod
     */
    public static function getPaymentMethodByName($sName)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `payment_methods`
                    WHERE
                        `system_name` = ' . db_str($sName) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "PaymentMethod");
    }

    /**
     * return paymentMethods filtered by a few options
     *
     * @param array $aFilter  filter properties
     * @param int   $iLimit   limit number of records returned
     * @param array $aOrderBy array(database coloumn name => order) add order by columns and orders
     *
     * @return array PaymentMethod
     */
    public static function getPaymentMethodsByFilter(array $aFilter = [])
    {

        $sFrom  = '';
        $sWhere = '';

        // get paymentMethods for deliveryMethod
        if (!empty($aFilter['deliveryMethodId'])) {
            $sFrom  .= 'JOIN `delivery_method_payment_method_relations` AS `dmpmr` ON `dmpmr`.`paymentMethodId` = `pm`.`paymentMethodId`';
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`dmpmr`.`deliveryMethodId` = ' . db_int($aFilter['deliveryMethodId']);
        }

        $sQuery = ' SELECT
                        `pm`.*
                    FROM
                        `payment_methods` AS `pm`
                    JOIN
                        `payment_method_translations` AS `pmt` ON `pmt`.`paymentMethodId` = `pm`.`paymentMethodId`
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    GROUP BY
                        `pm`.`paymentMethodId`
                    ORDER BY
                        `pm`.`order` ASC, 
                        `pmt`.`name` ASC
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "PaymentMethod");
    }

    /**
     * save a PaymentMethod
     *
     * @param productSize $oProductSize
     */
    public static function savePaymentMethod(PaymentMethod $oPaymentMethod)
    {
        $sQuery = ' INSERT INTO `payment_methods`(
                        `paymentMethodId`,
                        `price`,
                        `isOnlinePaymentMethod`,
                        `order`,
                        `system_name`
                    )
                    VALUES (
                        ' . db_int($oPaymentMethod->paymentMethodId) . ',
                        ' . db_str($oPaymentMethod->price) . ',
                        ' . db_int($oPaymentMethod->isOnlinePaymentMethod) . ',
                        ' . db_int($oPaymentMethod->order) . ',
                        ' . db_str($oPaymentMethod->system_name) . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `price`=VALUES(`price`),
                        `isOnlinePaymentMethod`=VALUES(`isOnlinePaymentMethod`),
                        `order`=VALUES(`order`),
                        `system_name`=VALUES(`system_name`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oPaymentMethod->paymentMethodId === null) {
            $oPaymentMethod->paymentMethodId = $oDb->insert_id;
        }
    }

    /**
     * delete a PaymentMethod
     *
     * @param PaymentMethod $oPaymentMethod
     *
     * @return bool true
     */
    public static function deletePaymentMethod(PaymentMethod $oPaymentMethod)
    {
        if ($oPaymentMethod->isDeletable()) {
            # delete object
            $sQuery = ' DELETE FROM
                        `payment_methods`
                    WHERE
                        `paymentMethodId` = ' . db_int($oPaymentMethod->paymentMethodId) . '
                    LIMIT 1
                    ;';

            $oDb = DBConnections::get();
            $oDb->query($sQuery, QRY_NORESULT);

            return true;
        } else {
            return false;
        }
    }

}

?>