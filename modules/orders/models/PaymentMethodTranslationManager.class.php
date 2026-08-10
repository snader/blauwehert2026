<?php

class PaymentMethodTranslationManager
{

    /**
     * return PaymentMethod translations
     *
     * @param array $aFilter filter properties
     */
    public static function getPaymentMethodTranslationsByFilter($aFilter = [])
    {

        $sWhere = '';

        // get by paymentMethodId
        if (isset($aFilter['paymentMethodId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`pmt`.`paymentMethodId` = ' . db_int($aFilter['paymentMethodId']);
        }

        // get by languageId
        if (isset($aFilter['languageId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`pmt`.`languageId` = ' . db_int($aFilter['languageId']);
        }

        $sQuery = ' SELECT 
                        `pmt`.*
                    FROM
                        `payment_method_translations` AS `pmt`
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "PaymentMethodTranslation");
    }

    /**
     * save a PaymentMethodTranslation
     *
     * @param PaymentMethodTranslation $oPaymentMethodTranslation
     */
    public static function savePaymentMethodTranslation(PaymentMethodTranslation $oPaymentMethodTranslation)
    {
        $sQuery = ' INSERT INTO `payment_method_translations`(
                        `paymentMethodTranslationId`,
                        `paymentMethodId`,
                        `languageId`,
                        `name`,
                        `redirectPage`
                    )
                    VALUES (
                        ' . db_int($oPaymentMethodTranslation->paymentMethodTranslationId) . ',
                        ' . db_int($oPaymentMethodTranslation->paymentMethodId) . ',
                        ' . db_int($oPaymentMethodTranslation->languageId) . ',
                        ' . db_str($oPaymentMethodTranslation->name) . ',
                        ' . db_str($oPaymentMethodTranslation->redirectPage) . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `paymentMethodId`=VALUES(`paymentMethodId`),
                        `languageId`=VALUES(`languageId`),
                        `name`=VALUES(`name`),
                        `redirectPage`=VALUES(`redirectPage`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oPaymentMethodTranslation->paymentMethodTranslationId === null) {
            $oPaymentMethodTranslation->paymentMethodTranslationId = $oDb->insert_id;
        }
    }

}
