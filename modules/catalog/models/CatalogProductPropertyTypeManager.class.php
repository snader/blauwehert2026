<?php

class CatalogProductPropertyTypeManager
{

    /**
     * get a CatalogProductPropertyType by id
     *
     * @param int $iProductPropertyTypeId
     *
     * @return CatalogProductPropertyType
     */
    public static function getProductPropertyTypeById($iProductPropertyTypeId)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `catalog_product_property_types`
                    WHERE
                        `catalogProductPropertyTypeId` = ' . db_int($iProductPropertyTypeId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "CatalogProductPropertyType");
    }

    /**
     * get a CatalogProductPropertyType by $iPossibleValueId
     *
     * @param int $iPossibleValueId
     *
     * @return CatalogProductPropertyType
     */
    public static function getProductPropertyTypeByPossibleValueId($iPossibleValueId)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `catalog_product_property_type_possible_values`
                    WHERE
                        `catalogProductPropertyTypePossibleValueId` = ' . db_int($iPossibleValueId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "CatalogProductPropertyType");
    }

    /**
     * get all CatalogProductPropertyType objects
     *
     * @return array CatalogProductPropertyType
     */
    public static function getAllProductPropertyTypes()
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `catalog_product_property_types`
                    ORDER BY
                        `order` ASC,
                        `catalogProductPropertyTypeId` ASC
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "CatalogProductPropertyType");
    }

    /**
     * get CatalogProductPropertyType objects by catalogProductTypeId
     *
     * @param int $iProductTypeId
     *
     * @return array CatalogProductPropertyType
     */
    public static function getProductPropertyTypesByProductTypeId($iProductTypeId)
    {
        $sQuery = ' SELECT
                        `cppt`.*
                    FROM
                        `catalog_product_property_types` `cppt`
                    JOIN
                        `catalog_product_property_type_groups` `cpptg` USING (`catalogProductPropertyTypeGroupId`)
                    WHERE
                        `catalogProductTypeId` = ' . db_int($iProductTypeId) . '
                    ORDER BY
                        `order` ASC,
                        `catalogProductPropertyTypeId` ASC
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "CatalogProductPropertyType");
    }

    /**
     * get CatalogProductPropertyType objects by catalogProductPropertyTypeGroupId
     *
     * @param int $iProductPropertyTypeGroupId
     *
     * @return array CatalogProductPropertyType
     */
    public static function getProductPropertyTypesByProductPropertyTypeGroupId($iProductPropertyTypeGroupId)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `catalog_product_property_types`
                    WHERE
                        `catalogProductPropertyTypeGroupId` = ' . db_int($iProductPropertyTypeGroupId) . '
                    ORDER BY
                        `order` ASC,
                        `catalogProductPropertyTypeId` ASC
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "CatalogProductPropertyType");
    }

    /**
     * return products filtered by a few options
     *
     * @param array $aFilter    filter properties (catalogProductTypeId)
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database coloumn name => order) add order by columns and orders
     *
     * @return array CatalogProductPropertyType objects
     */
    public static function getProductPropertyTypesByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`cppt`.`order`' => 'ASC', '`cpptt`.`title`' => 'ASC'])
    {

        $sWhere = '';
        $sFrom  = '';
        # join the catalogProductPropertyTypeGroups table if necessary
        if (!empty($aFilter['catalogProductTypeId']) || !empty($aFilter['catalogProductPropertyTypeGroupId'])) {
            $sFrom .= 'JOIN `catalog_product_property_type_groups` `cpptg` USING (`catalogProductPropertyTypeGroupId`)';
        }

        # search for catalogProductTypeId
        if (!empty($aFilter['catalogProductTypeId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cpptg`.`catalogProductTypeId` = ' . db_int($aFilter['catalogProductTypeId']);
        }

        # search for catalogProductPropertyTypeGroupId
        if (!empty($aFilter['catalogProductPropertyTypeGroupId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cpptg`.`catalogProductPropertyTypeGroupId` = ' . db_int($aFilter['catalogProductPropertyTypeGroupId']);
        }

        # handle order by
        $sOrderBy = '';
        if (count($aOrderBy) > 0) {
            foreach ($aOrderBy AS $sColumn => $sOrder) {
                $sOrderBy .= ($sOrderBy !== '' ? ',' : '') . $sColumn . ' ' . $sOrder;
            }
        }
        $sOrderBy = ($sOrderBy !== '' ? 'ORDER BY ' : '') . $sOrderBy;

        # handle start,limit
        $sLimit = '';
        if (is_numeric($iLimit)) {
            $sLimit .= db_int($iLimit);
        }
        if ($sLimit !== '') {
            $sLimit = (is_numeric($iStart) ? db_int($iStart) . ',' : '0,') . $sLimit;
        }
        $sLimit = ($sLimit !== '' ? 'LIMIT ' : '') . $sLimit;

        $sQuery = ' SELECT SQL_CALC_FOUND_ROWS
                        `cppt`.*
                    FROM
                        `catalog_product_property_types` AS `cppt`
                    JOIN
                        `catalog_product_property_type_translations` AS `cpptt` ON `cpptt`.`catalogProductPropertyTypeId` = `cppt`.`catalogProductPropertyTypeId`
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    GROUP BY
                        `cpptt`.`catalogProductPropertyTypeId`
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb                   = DBConnections::get();
        $aProductPropertyTypes = $oDb->query($sQuery, QRY_OBJECT, "CatalogProductPropertyType");
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aProductPropertyTypes;
    }

    /**
     * save a CatalogProductPropertyType
     *
     * @param CatalogProductPropertyType $oProductPropertyType
     */
    public static function saveProductPropertyType(CatalogProductPropertyType $oProductPropertyType)
    {
        $sQuery = ' INSERT INTO `catalog_product_property_types`(
                        `catalogProductPropertyTypeId`,
                        `type`,
                        `inputTranslatable`,
                        `filterType`,
                        `order`,
                        `catalogProductPropertyTypeGroupId`,
                        `created`
                    )
                    VALUES (
                        ' . db_int($oProductPropertyType->catalogProductPropertyTypeId) . ',
                        ' . db_str($oProductPropertyType->type) . ',
                        ' . db_int($oProductPropertyType->inputTranslatable) . ',
                        ' . db_str($oProductPropertyType->filterType) . ',
                        ' . db_int($oProductPropertyType->order) . ',
                        ' . db_int($oProductPropertyType->catalogProductPropertyTypeGroupId) . ',
                        NOW()
                    )
                    ON DUPLICATE KEY UPDATE
                        `type`=VALUES(`type`),
                        `inputTranslatable`=VALUES(`inputTranslatable`),
                        `filterType`=VALUES(`filterType`),
                        `order`=VALUES(`order`),
                        `catalogProductPropertyTypeGroupId`=VALUES(`catalogProductPropertyTypeGroupId`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oProductPropertyType->catalogProductPropertyTypeId === null) {
            $oProductPropertyType->catalogProductPropertyTypeId = $oDb->insert_id;
        }
    }

    /**
     * delete a CatalogProductPropertyType
     *
     * @param CatalogProductPropertyType $oProductPropertyType
     *
     * @return bool true
     */
    public static function deleteProductPropertyType(CatalogProductPropertyType $oProductPropertyType)
    {
        # delete object
        $sQuery = ' DELETE FROM
                        `catalog_product_property_types`
                    WHERE
                        `catalogProductPropertyTypeId` = ' . db_int($oProductPropertyType->catalogProductPropertyTypeId) . '
                    LIMIT 1
                    ;';
        $oDb    = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        return true;
    }

}
