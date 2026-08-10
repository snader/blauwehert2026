<?php

class CatalogProductTranslationManager
{

    /**
     * return CatalogProduct translations
     *
     * @param array $aFilter filter properties
     */
    public static function getProductTranslationsByFilter($aFilter = [])
    {

        $sWhere = '';

        // get by productId
        if (isset($aFilter['productId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cpt`.`catalogProductId` = ' . db_int($aFilter['productId']);
        }

        // get by languageId
        if (isset($aFilter['languageId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cpt`.`languageId` = ' . db_int($aFilter['languageId']);
        }

        $sQuery = ' SELECT 
                        `cpt`.*
                    FROM
                        `catalog_product_translations` AS `cpt`
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "CatalogProductTranslation");
    }

    /**
     * save a CatalogProductTranslation
     *
     * @param CatalogProductTranslation $oProductTranslation
     */
    public static function saveProductTranslation(CatalogProductTranslation $oProductTranslation)
    {
        $sQuery = ' INSERT INTO `catalog_product_translations`(
                        `catalogProductTranslationId`,
                        `catalogProductId`,
                        `languageId`,
                        `name`,
                        `description`,
                        `windowTitle`,
                        `metaKeywords`,
                        `metaDescription`,
                        `urlPart`,
                        `googleCategory`
                    )
                    VALUES (
                        ' . db_int($oProductTranslation->catalogProductTranslationId) . ',
                        ' . db_int($oProductTranslation->catalogProductId) . ',
                        ' . db_int($oProductTranslation->languageId) . ',
                        ' . db_str($oProductTranslation->name) . ',
                        ' . db_str($oProductTranslation->description) . ',
                        ' . db_str($oProductTranslation->windowTitle) . ',
                        ' . db_str($oProductTranslation->metaKeywords) . ',
                        ' . db_str($oProductTranslation->metaDescription) . ',
                        ' . db_str($oProductTranslation->getUrlPart()) . ',
                        ' . db_str($oProductTranslation->googleCategory) . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `catalogProductId`=VALUES(`catalogProductId`),
                        `languageId`=VALUES(`languageId`),
                        `name`=VALUES(`name`),
                        `description`=VALUES(`description`),
                        `windowTitle`=VALUES(`windowTitle`),
                        `metaKeywords`=VALUES(`metaKeywords`),
                        `metaDescription`=VALUES(`metaDescription`),
                        `urlPart`=VALUES(`urlPart`),
                        `googleCategory`=VALUES(`googleCategory`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oProductTranslation->catalogProductTranslationId === null) {
            $oProductTranslation->catalogProductTranslationId = $oDb->insert_id;
        }
    }

}
