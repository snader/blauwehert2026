<?php

class CatalogBrandTranslationManager
{

    /**
     * return CatalogBrand translations
     *
     * @param array $aFilter filter properties
     */
    public static function getBrandTranslationsByFilter($aFilter = [])
    {

        $sWhere = '';

        // get by brandId
        if (isset($aFilter['brandId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cbt`.`catalogBrandId` = ' . db_int($aFilter['brandId']);
        }

        // get by languageId
        if (isset($aFilter['languageId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cbt`.`languageId` = ' . db_int($aFilter['languageId']);
        }

        $sQuery = ' SELECT 
                        `cbt`.*
                    FROM
                        `catalog_brand_translations` AS `cbt`
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "CatalogBrandTranslation");
    }

    /**
     * save a CatalogBrandTranslation
     *
     * @param CatalogBrandTranslation $oBrandTranslation
     */
    public static function saveProductBrandTranslation(CatalogBrandTranslation $oBrandTranslation)
    {
        $sQuery = ' INSERT INTO `catalog_brand_translations`(
                        `catalogBrandTranslationId`,
                        `catalogBrandId`,
                        `languageId`,
                        `name`
                    )
                    VALUES (
                        ' . db_int($oBrandTranslation->catalogBrandTranslationId) . ',
                        ' . db_int($oBrandTranslation->catalogBrandId) . ',
                        ' . db_int($oBrandTranslation->languageId) . ',
                        ' . db_str($oBrandTranslation->name) . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `catalogBrandId`=VALUES(`catalogBrandId`),
                        `languageId`=VALUES(`languageId`),
                        `name`=VALUES(`name`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oBrandTranslation->catalogBrandTranslationId === null) {
            $oBrandTranslation->catalogBrandTranslationId = $oDb->insert_id;
        }
    }

}
