<?php

class CatalogProductColorTranslationManager
{

    /**
     * return CatalogProductColor translations
     *
     * @param array $aFilter filter properties
     */
    public static function getColorTranslationsByFilter($aFilter = [])
    {

        $sWhere = '';

        // get by colorId
        if (isset($aFilter['colorId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cpct`.`catalogProductColorId` = ' . db_int($aFilter['colorId']);
        }

        // get by languageId
        if (isset($aFilter['languageId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cpct`.`languageId` = ' . db_int($aFilter['languageId']);
        }

        $sQuery = ' SELECT 
                        `cpct`.*
                    FROM
                        `catalog_product_color_translations` AS `cpct`
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "CatalogProductColorTranslation");
    }

    /**
     * save a CatalogProductColorTranslation
     *
     * @param CatalogProductColorTranslation $oProductColorTranslation
     */
    public static function saveProductColorTranslation(CatalogProductColorTranslation $oProductColorTranslation)
    {
        $sQuery = ' INSERT INTO `catalog_product_color_translations`(
                        `catalogProductColorTranslationId`,
                        `catalogProductColorId`,
                        `languageId`,
                        `name`
                    )
                    VALUES (
                        ' . db_int($oProductColorTranslation->catalogProductColorTranslationId) . ',
                        ' . db_int($oProductColorTranslation->catalogProductColorId) . ',
                        ' . db_int($oProductColorTranslation->languageId) . ',
                        ' . db_str($oProductColorTranslation->name) . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `catalogProductColorId`=VALUES(`catalogProductColorId`),
                        `languageId`=VALUES(`languageId`),
                        `name`=VALUES(`name`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oProductColorTranslation->catalogProductColorTranslationId === null) {
            $oProductColorTranslation->catalogProductColorTranslationId = $oDb->insert_id;
        }
    }

}
