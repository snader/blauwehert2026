<?php

class CatalogProductPropertyTypeTranslationManager
{

    /**
     * return CatalogProductPropertyType translations
     *
     * @param array $aFilter filter properties
     */
    public static function getPropertyTypeTranslationsByFilter($aFilter = [])
    {

        $sWhere = '';

        // get by propertyTypeId
        if (isset($aFilter['propertyTypeId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cpptt`.`catalogProductPropertyTypeId` = ' . db_int($aFilter['propertyTypeId']);
        }

        // get by languageId
        if (isset($aFilter['languageId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cpptt`.`languageId` = ' . db_int($aFilter['languageId']);
        }

        $sQuery = ' SELECT 
                        `cpptt`.*
                    FROM
                        `catalog_product_property_type_translations` AS `cpptt`
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "CatalogProductPropertyTypeTranslation");
    }

    /**
     * save a CatalogProductPropertyTypeTranslation
     *
     * @param CatalogProductPropertyTypeTranslation $oProductPropertyTypeTranslation
     */
    public static function saveProductPropertyTypeTranslation(CatalogProductPropertyTypeTranslation $oProductPropertyTypeTranslation)
    {
        $sQuery = ' INSERT INTO `catalog_product_property_type_translations`(
                        `catalogProductPropertyTypeTranslationId`,
                        `catalogProductPropertyTypeId`,
                        `languageId`,
                        `title`
                    )
                    VALUES (
                        ' . db_int($oProductPropertyTypeTranslation->catalogProductPropertyTypeTranslationId) . ',
                        ' . db_int($oProductPropertyTypeTranslation->catalogProductPropertyTypeId) . ',
                        ' . db_int($oProductPropertyTypeTranslation->languageId) . ',
                        ' . db_str($oProductPropertyTypeTranslation->title) . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `catalogProductPropertyTypeId`=VALUES(`catalogProductPropertyTypeId`),
                        `languageId`=VALUES(`languageId`),
                        `title`=VALUES(`title`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oProductPropertyTypeTranslation->catalogProductPropertyTypeTranslationId === null) {
            $oProductPropertyTypeTranslation->catalogProductPropertyTypeTranslationId = $oDb->insert_id;
        }
    }

}
