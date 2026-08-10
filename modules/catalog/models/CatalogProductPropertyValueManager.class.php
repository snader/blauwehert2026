<?php

// Models and managers used by this class
require_once 'CatalogProductPropertyValue.class.php';

class CatalogProductPropertyValueManager
{

    /**
     * get CatalogProductPropertyValue objects by catalogProductId
     *
     * @param int $iProductId
     *
     * @return array CatalogProductPropertyValue
     */
    public static function getAllProductPropertyValuesByProductId($iProductId)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `catalog_product_property_values`
                    WHERE
                        `catalogProductId` = ' . db_int($iProductId) . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "CatalogProductPropertyValue");
    }

    /**
     * get CatalogProductPropertyValue objects by catalogProductId
     *
     * @param int $iProductId
     * @param int $iLanguageId
     *
     * @return array CatalogProductPropertyValue
     */
    public static function getProductPropertyValuesByProductId($iProductId, $iLanguageId)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `catalog_product_property_values`
                    WHERE
                        `catalogProductId` = ' . db_int($iProductId) . '
                    AND
                        `languageId` = ' . db_int($iLanguageId) . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "CatalogProductPropertyValue");
    }

    /**
     * get CatalogProductPropertyValue objects by catalogProductId and catalogProductPropertyTypeId
     *
     * @param int $iProductId
     * @param int $iProductPropertyTypeId
     * @param int $iLanguageId
     *
     * @return array CatalogProductPropertyValue
     */
    public static function getProductPropertyValuesByProductIdProductPropertyTypeId($iProductId, $iProductPropertyTypeId, $iLanguageId)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `catalog_product_property_values`
                    WHERE
                        `catalogProductId` = ' . db_int($iProductId) . ' 
                    AND
                        `catalogProductPropertyTypeId` = ' . db_int($iProductPropertyTypeId) . '
                    AND
                        `languageId` = ' . db_int($iLanguageId) . '
                    ;';

        //_d($sQuery);

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "CatalogProductPropertyValue");
    }

}
