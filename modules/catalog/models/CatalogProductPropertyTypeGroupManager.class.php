<?php

class CatalogProductPropertyTypeGroupManager
{

    /**
     * get a CatalogProductPropertyTypeGroup by id
     *
     * @param int $iProductPropertyTypeGroupId
     *
     * @return CatalogProductPropertyTypeGroup
     */
    public static function getProductPropertyTypeGroupById($iProductPropertyTypeGroupId)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `catalog_product_property_type_groups`
                    WHERE
                        `catalogProductPropertyTypeGroupId` = ' . db_int($iProductPropertyTypeGroupId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "CatalogProductPropertyTypeGroup");
    }

    /**
     * get all CatalogProductPropertyTypeGroup objects
     *
     * @return array CatalogProductPropertyTypeGroup
     */
    public static function getAllProductPropertyTypeGroups()
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `catalog_product_property_type_groups`
                    ORDER BY
                        `order` ASC,
                        `catalogProductPropertyTypeGroupId` ASC
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "CatalogProductPropertyTypeGroup");
    }

    /**
     * get CatalogProductPropertyTypeGroup objects by catalogProductTypeId
     *
     * @param int $iProductTypeId
     *
     * @return array CatalogProductPropertyTypeGroup
     */
    public static function getProductPropertyTypeGroupsByProductTypeId($iProductTypeId)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `catalog_product_property_type_groups`
                    WHERE
                        `catalogProductTypeId` = ' . db_int($iProductTypeId) . '
                    ORDER BY
                        `order` ASC,
                        `catalogProductPropertyTypeGroupId` ASC
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "CatalogProductPropertyTypeGroup");
    }

    /**
     * return CatalogProductPropertyTypeGroup objects filtered by a few options
     *
     * @param array $aFilter    filter properties (catalogProductTypeId)
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database coloumn name => order) add order by columns and orders
     *
     * @return array CatalogProductPropertyTypeGroup objects
     */
    public static function getProductPropertyTypeGroupsByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`cpptg`.`order`' => 'ASC', '`cpptgt`.`title`' => 'ASC'])
    {

        $sWhere = '';
        # search for productType
        if (!empty($aFilter['catalogProductTypeId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cpptg`.`catalogProductTypeId` = ' . db_int($aFilter['catalogProductTypeId']);
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
                        `cpptg`.*
                    FROM
                        `catalog_product_property_type_groups` AS `cpptg`
                    JOIN
                        `catalog_product_property_type_group_translations` AS `cpptgt` ON `cpptgt`.`catalogProductPropertyTypeGroupId` = `cpptg`.`catalogProductPropertyTypeGroupId`
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    GROUP BY
                        `cpptg`.`catalogProductPropertyTypeGroupId`
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb           = DBConnections::get();
        $aProductTypes = $oDb->query($sQuery, QRY_OBJECT, "CatalogProductPropertyTypeGroup");
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aProductTypes;
    }

    /**
     * save a CatalogProductPropertyTypeGroup
     *
     * @param CatalogProductPropertyTypeGroup $oProductPropertyTypeGroup
     */
    public static function saveProductPropertyTypeGroup(CatalogProductPropertyTypeGroup $oProductPropertyTypeGroup)
    {
        $sQuery = ' INSERT INTO `catalog_product_property_type_groups`(
                        `catalogProductPropertyTypeGroupId`,
                        `order`,
                        `created`,
                        `catalogProductTypeId`
                    )
                    VALUES (
                        ' . db_int($oProductPropertyTypeGroup->catalogProductPropertyTypeGroupId) . ',
                        ' . db_int($oProductPropertyTypeGroup->order) . ',
                        NOW(),
                        ' . db_int($oProductPropertyTypeGroup->catalogProductTypeId) . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `order`=VALUES(`order`),
                        `catalogProductTypeId`=VALUES(`catalogProductTypeId`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oProductPropertyTypeGroup->catalogProductPropertyTypeGroupId === null) {
            $oProductPropertyTypeGroup->catalogProductPropertyTypeGroupId = $oDb->insert_id;
        }
    }

    /**
     * delete a CatalogProductPropertyTypeGroup
     *
     * @param CatalogProductPropertyTypeGroup $oProductPropertyTypeGroup
     *
     * @return bool true
     */
    public static function deleteProductPropertyTypeGroup(CatalogProductPropertyTypeGroup $oProductPropertyTypeGroup)
    {
        # delete object
        $sQuery = ' DELETE FROM
                        `catalog_product_property_type_groups`
                    WHERE
                        `catalogProductPropertyTypeGroupId` = ' . db_int($oProductPropertyTypeGroup->catalogProductPropertyTypeGroupId) . '
                    LIMIT 1
                    ;';
        $oDb    = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        return true;
    }

    /**
     * count the number of product property types of a specified catalogProductPropertyTypeGroupId
     *
     * @param int $iProductPropertyTypeGroupId
     *
     * @return int
     */
    public static function getNumberOfProductPropertyTypesByProductPropertyTypeGroupId($iProductPropertyTypeGroupId)
    {
        # delete object
        $sQuery = ' SELECT
                        COUNT(*) AS `count`
                    FROM
                        `catalog_product_property_types`
                    WHERE
                        `catalogProductPropertyTypeGroupId` = ' . db_int($iProductPropertyTypeGroupId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT)->count;
    }

}
