<?php

class DeliveryMethodManager
{

    /**
     * get a DeliveryMethod by id
     *
     * @param int $iDeliveryMethodId
     *
     * @return DeliveryMethod
     */
    public static function getDeliveryMethodById($iDeliveryMethodId)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `delivery_methods`
                    WHERE
                        `deliveryMethodId` = ' . db_int($iDeliveryMethodId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "DeliveryMethod");
    }

    /**
     * get a DeliveryMethod by name
     *
     * @param str $sName
     *
     * @return DeliveryMethod
     */
    public static function getDeliveryMethodByName($sName)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `delivery_methods`
                    WHERE
                        `system_name` = ' . db_str($sName) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "DeliveryMethod");
    }

    /**
     * get all DeliveryMethod objects
     *
     * @return array DeliveryMethod
     */
    public static function getAllDeliveryMethods()
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `delivery_methods`
                    ORDER BY
                        `order` ASC,
                        `deliveryMethodId` ASC
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "DeliveryMethod");
    }

    /**
     * check if deliveryMethod and PaymentMethod combi is valid
     *
     * @param int $iDeliveryMethodId
     * @param int $iPaymentMethodId
     *
     * @return boolean
     */
    public static function isValidDeliveryMethodPaymentMethodRelation($iDeliveryMethodId, $iPaymentMethodId)
    {
        $sQuery = ' SELECT
                        COUNT(*) AS `number`
                    FROM
                        `delivery_method_payment_method_relations`
                    WHERE
                        `deliveryMethodId` = ' . db_int($iDeliveryMethodId) . '
                    AND
                        `paymentMethodId` = ' . db_int($iPaymentMethodId) . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT)->number == 1;
    }

    /**
     * save a DeliveryMethod
     *
     * @param productSize $oProductSize
     */
    public static function saveDeliveryMethod(DeliveryMethod $oDeliveryMethod)
    {
        $sQuery = ' INSERT INTO `delivery_methods`(
                        `deliveryMethodId`,
                        `price`,
                        `order`,
                        `freeFromPrice`,
                        `deliveryTime`,
                        `system_name`
                    )
                    VALUES (
                        ' . db_int($oDeliveryMethod->deliveryMethodId) . ',
                        ' . db_str($oDeliveryMethod->price) . ',
                        ' . db_int($oDeliveryMethod->order) . ',
                        ' . db_str($oDeliveryMethod->freeFromPrice) . ',
                        ' . db_str($oDeliveryMethod->deliveryTime) . ',
                        ' . db_str($oDeliveryMethod->system_name) . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `price`=VALUES(`price`),
                        `order`=VALUES(`order`),
                        `freeFromPrice`=VALUES(`freeFromPrice`),
                        `deliveryTime`=VALUES(`deliveryTime`),
                        `system_name`=VALUES(`system_name`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oDeliveryMethod->deliveryMethodId === null) {
            $oDeliveryMethod->deliveryMethodId = $oDb->insert_id;
        }

        self::savePaymentMethodRelations($oDeliveryMethod);
    }

    /**
     * save paymentMethod relations
     *
     * @param DeliveryMethod $oDeliveryMethod
     */
    private static function savePaymentMethodRelations(DeliveryMethod $oDeliveryMethod)
    {
        $oDb = DBConnections::get();

        # define the NOT IT and VALUES part of the DELETE and INSERT query
        $sDeleteNotIn  = '';
        $sInsertValues = '';
        foreach ($oDeliveryMethod->getPaymentMethods('all') as $oPaymentMethod) {
            $sDeleteNotIn  .= ($sDeleteNotIn == '' ? '' : ',') . db_int($oPaymentMethod->paymentMethodId);
            $sInsertValues .= ($sInsertValues == '' ? '' : ', ') . '(' . db_int($oDeliveryMethod->deliveryMethodId) . ', ' . db_int($oPaymentMethod->paymentMethodId) . ')';
        }

        # delete the old objects
        $sQuery = ' DELETE FROM
                        `delivery_method_payment_method_relations`
                    WHERE
                        `deliveryMethodId` = ' . db_int($oDeliveryMethod->deliveryMethodId) . '
                        ' . ($sDeleteNotIn != '' ? 'AND `paymentMethodId` NOT IN (' . $sDeleteNotIn . ')' : '') . '
                    ;';
        $oDb->query($sQuery, QRY_NORESULT);

        if (!empty($sInsertValues)) {
            # save the objects
            $sQuery = ' INSERT IGNORE INTO `delivery_method_payment_method_relations`(
                        `deliveryMethodId`,
                        `paymentMethodId`
                    )
                    VALUES ' . $sInsertValues . '
                    ;';

            $oDb->query($sQuery, QRY_NORESULT);
        }
    }

    /**
     * delete a DeliveryMethod
     *
     * @param DeliveryMethod $oDeliveryMethod
     *
     * @return bool true
     */
    public static function deleteDeliveryMethod(DeliveryMethod $oDeliveryMethod)
    {
        if ($oDeliveryMethod->isDeletable()) {
            # delete object
            $sQuery = ' DELETE FROM
                        `delivery_methods`
                    WHERE
                        `deliveryMethodId` = ' . db_int($oDeliveryMethod->deliveryMethodId) . '
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