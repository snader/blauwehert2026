<?php

class CatalogProductSizeTranslationManager
{

    /**
     * return CatalogProductSize translations
     *
     * @param array $aFilter filter properties
     */
    public static function getSizeTranslationsByFilter($aFilter = [])
    {

        $sWhere = '';

        // get by sizeId
        if (isset($aFilter['sizeId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cpst`.`catalogProductSizeId` = ' . db_int($aFilter['sizeId']);
        }

        // get by languageId
        if (isset($aFilter['languageId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cpst`.`languageId` = ' . db_int($aFilter['languageId']);
        }

        $sQuery = ' SELECT 
                        `cpst`.*
                    FROM
                        `catalog_product_size_translations` AS `cpst`
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "CatalogProductSizeTranslation");
    }

    /**
     * save a CatalogProductSizeTranslation
     *
     * @param CatalogProductSizeTranslation $oProductSizeTranslation
     */
    public static function saveProductSizeTranslation(CatalogProductSizeTranslation $oProductSizeTranslation)
    {
        $sQuery = ' INSERT INTO `catalog_product_size_translations`(
                        `catalogProductSizeTranslationId`,
                        `catalogProductSizeId`,
                        `languageId`,
                        `name`
                    )
                    VALUES (
                        ' . db_int($oProductSizeTranslation->catalogProductSizeTranslationId) . ',
                        ' . db_int($oProductSizeTranslation->catalogProductSizeId) . ',
                        ' . db_int($oProductSizeTranslation->languageId) . ',
                        ' . db_str($oProductSizeTranslation->name) . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `catalogProductSizeId`=VALUES(`catalogProductSizeId`),
                        `languageId`=VALUES(`languageId`),
                        `name`=VALUES(`name`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oProductSizeTranslation->catalogProductSizeTranslationId === null) {
            $oProductSizeTranslation->catalogProductSizeTranslationId = $oDb->insert_id;
        }
    }

}