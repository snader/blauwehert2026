<?php

class DeliveryMethodTranslationManager
{

    /**
     * return DeliveryMethod translations
     *
     * @param array $aFilter filter properties
     */
    public static function getDeliveryMethodTranslationsByFilter($aFilter = [])
    {

        $sWhere = '';

        // get by deliveryMethodId
        if (isset($aFilter['deliveryMethodId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`dmt`.`deliveryMethodId` = ' . db_int($aFilter['deliveryMethodId']);
        }

        // get by languageId
        if (isset($aFilter['languageId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`dmt`.`languageId` = ' . db_int($aFilter['languageId']);
        }

        $sQuery = ' SELECT 
                        `dmt`.*
                    FROM
                        `delivery_method_translations` AS `dmt`
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "DeliveryMethodTranslation");
    }

    /**
     * save a DeliveryMethodTranslation
     *
     * @param DeliveryMethodTranslation $oDeliveryMethodTranslation
     */
    public static function saveDeliveryMethodTranslation(DeliveryMethodTranslation $oDeliveryMethodTranslation)
    {
        $sQuery = ' INSERT INTO `delivery_method_translations`(
                        `deliveryMethodTranslationId`,
                        `deliveryMethodId`,
                        `languageId`,
                        `name`
                    )
                    VALUES (
                        ' . db_int($oDeliveryMethodTranslation->deliveryMethodTranslationId) . ',
                        ' . db_int($oDeliveryMethodTranslation->deliveryMethodId) . ',
                        ' . db_int($oDeliveryMethodTranslation->languageId) . ',
                        ' . db_str($oDeliveryMethodTranslation->name) . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `deliveryMethodId`=VALUES(`deliveryMethodId`),
                        `languageId`=VALUES(`languageId`),
                        `name`=VALUES(`name`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oDeliveryMethodTranslation->deliveryMethodTranslationId === null) {
            $oDeliveryMethodTranslation->deliveryMethodTranslationId = $oDb->insert_id;
        }
    }

}
