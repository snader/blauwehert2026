<?php

class CatalogProductPropertyTypePossibleValueManager
{

    /**
     * get a CatalogProductPropertyTypePossibleValue by id
     *
     * @param int $iProductPropertyTypePossibleValueId
     *
     * @return CatalogProductPropertyTypePossibleValue
     */
    public static function getProductPropertyTypePossibleValueById($iProductPropertyTypePossibleValueId)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `catalog_product_property_type_possible_values`
                    WHERE
                        `catalogProductPropertyTypePossibleValueId` = ' . db_int($iProductPropertyTypePossibleValueId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "CatalogProductPropertyTypePossibleValue");
    }

    /**
     * get all CatalogProductPropertyTypePossibleValue objects
     *
     * @return array CatalogProductPropertyTypePossibleValue
     */
    public static function getAllProductPropertyTypePossibleValues()
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `catalog_product_property_type_possible_values`
                    ORDER BY
                        `order` ASC,
                        `catalogProductPropertyTypePossibleValueId` ASC
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "CatalogProductPropertyTypePossibleValue");
    }

    /**
     * save a CatalogProductPropertyTypePossibleValue
     *
     * @param CatalogProductPropertyTypePossibleValue $oProductPropertyTypePossibleValue
     */
    public static function saveProductPropertyTypePossibleValue(CatalogProductPropertyTypePossibleValue $oProductPropertyTypePossibleValue)
    {
        $sQuery = ' INSERT INTO `catalog_product_property_type_possible_values`(
                        `catalogProductPropertyTypePossibleValueId`,
                        `multilingual`,
                        `order`,
                        `catalogProductPropertyTypeId`,
                        `created`
                    )
                    VALUES (
                        ' . db_int($oProductPropertyTypePossibleValue->catalogProductPropertyTypePossibleValueId) . ',
                        ' . db_int($oProductPropertyTypePossibleValue->multilingual) . ',
                        ' . db_int($oProductPropertyTypePossibleValue->order) . ',
                        ' . db_int($oProductPropertyTypePossibleValue->catalogProductPropertyTypeId) . ',
                        NOW()
                    )
                    ON DUPLICATE KEY UPDATE
                        `multilingual`=VALUES(`multilingual`),
                        `order`=VALUES(`order`),
                        `catalogProductPropertyTypeId`=VALUES(`catalogProductPropertyTypeId`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oProductPropertyTypePossibleValue->catalogProductPropertyTypePossibleValueId === null) {
            $oProductPropertyTypePossibleValue->catalogProductPropertyTypePossibleValueId = $oDb->insert_id;
        }
    }

    /**
     * save a CatalogProductPropertyTypePossibleValue
     *
     * @param CatalogProductPropertyTypePossibleValue $oProductPropertyTypePossibleValue object with old value
     * @param string                                  $sNewValue                         the new value for the object
     */
    public static function updateProductPropertyTypePossibleValue(CatalogProductPropertyTypePossibleValue $oProductPropertyTypePossibleValue, $sNewValue)
    {
        $sQuery = ' UPDATE `catalog_product_property_type_possible_values`
                    SET
                        `value` = ' . db_str($sNewValue) . '
                    WHERE
                        `catalogProductPropertyTypePossibleValueId` = ' . db_int($oProductPropertyTypePossibleValue->catalogProductPropertyTypePossibleValueId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        $sQuery = ' UPDATE `catalog_product_property_values`
                    SET
                        `value` = ' . db_str($sNewValue) . '
                    WHERE
                        `catalogProductPropertyTypeId` = ' . db_int($oProductPropertyTypePossibleValue->catalogProductPropertyTypeId) . ' AND
                        `value` = ' . db_str($oProductPropertyTypePossibleValue->value) . '
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    /**
     * delete a CatalogProductPropertyTypePossibleValue
     *
     * @param CatalogProductPropertyTypePossibleValue $oCatalogProductPropertyTypePossibleValue
     *
     * @return bool
     */
    public static function deleteProductPropertyTypePossibleValue(CatalogProductPropertyTypePossibleValue $oCatalogProductPropertyTypePossibleValue)
    {
        # delete object
        $sQuery = ' DELETE FROM
                        `catalog_product_property_type_possible_values`
                    WHERE
                        `catalogProductPropertyTypePossibleValueId` = ' . db_int($oCatalogProductPropertyTypePossibleValue->catalogProductPropertyTypePossibleValueId) . '
                    LIMIT 1
                    ;';
        $oDb    = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        return true;
    }

    /**
     * get catalogProductPropertyTypePossibleValue objects by catalogProductPropertyTypeId
     *
     * @param int $iPropertyTypeId
     *
     * @return array catalogProductPropertyTypePossibleValue
     */
    public static function getPossibleValuesByProductPropertyTypeId($iPropertyTypeId)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `catalog_product_property_type_possible_values`
                    WHERE
                        `catalogProductPropertyTypeId` = ' . db_int($iPropertyTypeId) . '
                    ORDER BY
                        `order` ASC,
                        `catalogProductPropertyTypePossibleValueId` ASC
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "CatalogProductPropertyTypePossibleValue");
    }

}

?>