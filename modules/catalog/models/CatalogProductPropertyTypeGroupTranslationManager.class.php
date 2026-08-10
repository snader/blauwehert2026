<?php

class CatalogProductPropertyTypeGroupTranslationManager
{

    /**
     * return CatalogProductPropertyTypeGroup translations
     *
     * @param array $aFilter filter properties
     */
    public static function getPropertyTypeGroupTranslationsByFilter($aFilter = [])
    {

        $sWhere = '';

        // get by propertyTypeGroupId
        if (isset($aFilter['propertyTypeGroupId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cpptgt`.`catalogProductPropertyTypeGroupId` = ' . db_int($aFilter['propertyTypeGroupId']);
        }

        // get by languageId
        if (isset($aFilter['languageId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cpptgt`.`languageId` = ' . db_int($aFilter['languageId']);
        }

        $sQuery = ' SELECT 
                        `cpptgt`.*
                    FROM
                        `catalog_product_property_type_group_translations` AS `cpptgt`
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "CatalogProductPropertyTypeGroupTranslation");
    }

    /**
     * save a CatalogProductPropertyTypeGroupTranslation
     *
     * @param CatalogProductPropertyTypeGroupTranslation $oProductPropertyTypeGroupTranslation
     */
    public static function saveProductPropertyTypeGroupTranslation(CatalogProductPropertyTypeGroupTranslation $oProductPropertyTypeGroupTranslation)
    {
        $sQuery = ' INSERT INTO `catalog_product_property_type_group_translations`(
                        `catalogProductPropertyTypeGroupTranslationId`,
                        `catalogProductPropertyTypeGroupId`,
                        `languageId`,
                        `title`
                    )
                    VALUES (
                        ' . db_int($oProductPropertyTypeGroupTranslation->catalogProductPropertyTypeGroupTranslationId) . ',
                        ' . db_int($oProductPropertyTypeGroupTranslation->catalogProductPropertyTypeGroupId) . ',
                        ' . db_int($oProductPropertyTypeGroupTranslation->languageId) . ',
                        ' . db_str($oProductPropertyTypeGroupTranslation->title) . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `catalogProductPropertyTypeGroupId`=VALUES(`catalogProductPropertyTypeGroupId`),
                        `languageId`=VALUES(`languageId`),
                        `title`=VALUES(`title`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oProductPropertyTypeGroupTranslation->catalogProductPropertyTypeGroupTranslationId === null) {
            $oProductPropertyTypeGroupTranslation->catalogProductPropertyTypeGroupTranslationId = $oDb->insert_id;
        }
    }

}
