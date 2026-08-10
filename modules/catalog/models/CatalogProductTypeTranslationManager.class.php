<?php

class CatalogProductTypeTranslationManager
{

    /**
     * return CatalogProductType translations
     *
     * @param array $aFilter filter properties
     */
    public static function getTypeTranslationsByFilter($aFilter = [])
    {

        $sWhere = '';

        // get by typeId
        if (isset($aFilter['typeId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cptt`.`catalogProductTypeId` = ' . db_int($aFilter['typeId']);
        }

        // get by languageId
        if (isset($aFilter['languageId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cptt`.`languageId` = ' . db_int($aFilter['languageId']);
        }

        $sQuery = ' SELECT 
                        `cptt`.*
                    FROM
                        `catalog_product_type_translations` AS `cptt`
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "CatalogProductTypeTranslation");
    }

    /**
     * save a CatalogProductTypeTranslation
     *
     * @param CatalogProductTypeTranslation $oProductTypeTranslation
     */
    public static function saveProductTypeTranslation(CatalogProductTypeTranslation $oProductTypeTranslation)
    {
        $sQuery = ' INSERT INTO `catalog_product_type_translations`(
                        `catalogProductTypeTranslationId`,
                        `catalogProductTypeId`,
                        `languageId`,
                        `title`
                    )
                    VALUES (
                        ' . db_int($oProductTypeTranslation->catalogProductTypeTranslationId) . ',
                        ' . db_int($oProductTypeTranslation->catalogProductTypeId) . ',
                        ' . db_int($oProductTypeTranslation->languageId) . ',
                        ' . db_str($oProductTypeTranslation->title) . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `catalogProductTypeId`=VALUES(`catalogProductTypeId`),
                        `languageId`=VALUES(`languageId`),
                        `title`=VALUES(`title`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oProductTypeTranslation->catalogProductTypeTranslationId === null) {
            $oProductTypeTranslation->catalogProductTypeTranslationId = $oDb->insert_id;
        }
    }

}
