<?php

class CatalogProductPropertyTypePossibleValueTranslationManager
{

    /**
     * return CatalogProductPropertyTypePossibleValue translations
     *
     * @param array $aFilter filter properties
     */
    public static function getPropertyTypePossibleValueTranslationsByFilter($aFilter = [])
    {

        $sWhere = '';

        // get by possibleValueId
        if (isset($aFilter['possibleValueId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cpptpvt`.`catalogProductPropertyTypePossibleValueId` = ' . db_int($aFilter['possibleValueId']);
        }

        // get by languageId
        if (isset($aFilter['languageId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cpptpvt`.`languageId` = ' . db_int($aFilter['languageId']);
        }

        $sQuery = ' SELECT 
                        `cpptpvt`.*
                    FROM
                        `catalog_product_property_type_possible_value_translations` AS `cpptpvt`
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "CatalogProductPropertyTypePossibleValueTranslation");
    }

    /**
     * save a CatalogProductPropertyTypePossibleValueTranslation
     *
     * @param CatalogProductPropertyTypePossibleValueTranslation $oProductPropertyTypePossibleValueTranslation
     */
    public static function saveProductPropertyTypePossibleValueTranslation(CatalogProductPropertyTypePossibleValueTranslation $oProductPropertyTypePossibleValueTranslation)
    {
        $sQuery = ' INSERT INTO `catalog_product_property_type_possible_value_translations`(
                        `catalogProductPropertyTypePossibleValueTranslationId`,
                        `catalogProductPropertyTypePossibleValueId`,
                        `languageId`,
                        `value`
                    )
                    VALUES (
                        ' . db_int($oProductPropertyTypePossibleValueTranslation->catalogProductPropertyTypePossibleValueTranslationId) . ',
                        ' . db_int($oProductPropertyTypePossibleValueTranslation->catalogProductPropertyTypePossibleValueId) . ',
                        ' . db_int($oProductPropertyTypePossibleValueTranslation->languageId) . ',
                        ' . db_str($oProductPropertyTypePossibleValueTranslation->value) . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `catalogProductPropertyTypePossibleValueId`=VALUES(`catalogProductPropertyTypePossibleValueId`),
                        `languageId`=VALUES(`languageId`),
                        `value`=VALUES(`value`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oProductPropertyTypePossibleValueTranslation->catalogProductPropertyTypePossibleValueTranslationId === null) {
            $oProductPropertyTypePossibleValueTranslation->catalogProductPropertyTypePossibleValueTranslationId = $oDb->insert_id;
        }
    }

}
