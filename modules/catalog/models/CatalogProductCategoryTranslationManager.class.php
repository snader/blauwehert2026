<?php

class CatalogProductCategoryTranslationManager
{

    /**
     * return CatalogProductCategory translations
     *
     * @param array $aFilter filter properties
     */
    public static function getCategoryTranslationsByFilter($aFilter = [])
    {

        $sWhere = '';

        // get by categoryId
        if (isset($aFilter['categoryId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cpct`.`catalogProductCategoryId` = ' . db_int($aFilter['categoryId']);
        }

        // get by languageId
        if (isset($aFilter['languageId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cpct`.`languageId` = ' . db_int($aFilter['languageId']);
        }

        $sQuery = ' SELECT 
                        `cpct`.*
                    FROM
                        `catalog_product_category_translations` AS `cpct`
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "CatalogProductCategoryTranslation");
    }

    /**
     * save a CatalogProductCategoryTranslation
     *
     * @param CatalogProductCategoryTranslation $oProductCategoryTranslation
     */
    public static function saveProductCategoryTranslation(CatalogProductCategoryTranslation $oProductCategoryTranslation)
    {

        $sQuery = ' INSERT INTO `catalog_product_category_translations`(
                        `catalogProductCategoryTranslationId`,
                        `catalogProductCategoryId`,
                        `languageId`,
                        `name`,
                        `content`,
                        `windowTitle`,
                        `metaKeywords`,
                        `metaDescription`,
                        `urlPart`
                    )
                    VALUES (
                        ' . db_int($oProductCategoryTranslation->catalogProductCategoryTranslationId) . ',
                        ' . db_int($oProductCategoryTranslation->catalogProductCategoryId) . ',
                        ' . db_int($oProductCategoryTranslation->languageId) . ',
                        ' . db_str($oProductCategoryTranslation->name) . ',
                        ' . db_str($oProductCategoryTranslation->content) . ',
                        ' . db_str($oProductCategoryTranslation->windowTitle) . ',
                        ' . db_str($oProductCategoryTranslation->metaKeywords) . ',
                        ' . db_str($oProductCategoryTranslation->metaDescription) . ',
                        ' . db_str($oProductCategoryTranslation->getUrlPart()) . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `catalogProductCategoryId`=VALUES(`catalogProductCategoryId`),
                        `languageId`=VALUES(`languageId`),
                        `name`=VALUES(`name`),
                        `content`=VALUES(`content`),
                        `windowTitle`=VALUES(`windowTitle`),
                        `metaKeywords`=VALUES(`metaKeywords`),
                        `metaDescription`=VALUES(`metaDescription`),
                        `urlPart`=VALUES(`urlPart`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oProductCategoryTranslation->catalogProductCategoryTranslationId === null) {
            $oProductCategoryTranslation->catalogProductCategoryTranslationId = $oDb->insert_id;
        }
    }

}
