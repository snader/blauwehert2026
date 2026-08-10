<?php

class CatalogProductColorManager
{

    /**
     * get a CatalogProductColor by id
     *
     * @param int $iProductColorId
     *
     * @return CatalogProductColor
     */
    public static function getProductColorById($iProductColorId)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `catalog_product_colors`
                    WHERE
                        `catalogProductColorId` = ' . db_int($iProductColorId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "CatalogProductColor");
    }

    /**
     * return productColors filtered by a few options
     *
     * @param array $aFilter    filter properties (checkOnline)
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database coloumn name => order) add order by columns and orders
     *
     * @return array CatalogProductColor objects
     */
    public static function getProductColorsByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`cpc`.`order`' => 'ASC', '`cpct`.`name`' => 'ASC'])
    {

        $sWhere = '';
        if (empty($aFilter['showAll'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cpc`.`catalogProductColorId` != -1';
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
            $sLimit .= $iLimit;
        }
        if ($sLimit !== '') {
            $sLimit = (is_numeric($iStart) ? $iStart . ',' : '0,') . $sLimit;
        }
        $sLimit = ($sLimit !== '' ? 'LIMIT ' : '') . $sLimit;

        $sQuery = ' SELECT ' . ($iFoundRows !== false ? 'SQL_CALC_FOUND_ROWS' : '') . '
                        `cpc`.*
                    FROM
                        `catalog_product_colors` AS `cpc`
                    JOIN
                        `catalog_product_color_translations` AS `cpct` ON `cpc`.`catalogProductColorId` = `cpct`.`catalogProductColorId`
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    GROUP BY
                        `cpct`.`catalogProductColorId`
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb        = DBConnections::get();
        $aLocations = $oDb->query($sQuery, QRY_OBJECT, "CatalogProductColor");
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aLocations;
    }

    /**
     * save a CatalogProductColor
     *
     * @param CatalogProductColor $oProductColor
     */
    public static function saveProductColor(CatalogProductColor $oProductColor)
    {
        $sQuery = ' INSERT INTO `catalog_product_colors`(
                        `catalogProductColorId`,
                        `order`,
                        `created`
                    )
                    VALUES (
                        ' . db_int($oProductColor->catalogProductColorId) . ',
                        ' . db_int($oProductColor->order) . ',
                        NOW()
                    )
                    ON DUPLICATE KEY UPDATE
                        `order`=VALUES(`order`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oProductColor->catalogProductColorId === null) {
            $oProductColor->catalogProductColorId = $oDb->insert_id;
        }
    }

    /**
     * delete a CatalogProductColor
     *
     * @param CatalogProductColor $oProductColor
     *
     * @return bool true
     */
    public static function deleteProductColor(CatalogProductColor $oProductColor)
    {
        if ($oProductColor->isDeletable()) {
            # delete object
            $sQuery = ' DELETE FROM
                        `catalog_product_colors`
                    WHERE
                        `catalogProductColorId` = ' . db_int($oProductColor->catalogProductColorId) . '
                    LIMIT 1
                    ;';

            $oDb = DBConnections::get();
            $oDb->query($sQuery, QRY_NORESULT);

            return true;
        } else {
            return false;
        }
    }

    /**
     * return if color is unused
     *
     * @param int $iCatalogProductColorId
     *
     * @return boolean
     */
    public static function isUnused($iCatalogProductColorId)
    {
        $sQuery = ' SELECT
                        COUNT(*) AS `timesUsed`
                    FROM
                        `catalog_product_size_color_relations`
                    WHERE
                        `catalogProductColorId` = ' . db_int($iCatalogProductColorId) . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT)->timesUsed == 0;
    }

}

?>