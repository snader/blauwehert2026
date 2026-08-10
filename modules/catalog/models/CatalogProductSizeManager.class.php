<?php

class CatalogProductSizeManager
{

    /**
     * get a CatalogProductSize by id
     *
     * @param int $iProductSizeId
     *
     * @return CatalogProductSize
     */
    public static function getProductSizeById($iProductSizeId)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `catalog_product_sizes`
                    WHERE
                        `catalogProductSizeId` = ' . db_int($iProductSizeId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "CatalogProductSize");
    }

    /**
     * return productSizes filtered by a few options
     *
     * @param array $aFilter    filter properties (checkOnline)
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database coloumn name => order) add order by columns and orders
     *
     * @return array CatalogProductSize objects
     */
    public static function getProductSizesByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`cps`.`order`' => 'ASC', '`cpst`.`name`' => 'ASC'])
    {

        $sWhere = '';
        if (empty($aFilter['showAll'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cps`.`catalogProductSizeId` != -1';
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
                        `cps`.*
                    FROM
                        `catalog_product_sizes` AS `cps`
                    JOIN
                        `catalog_product_size_translations` AS `cpst` ON `cpst`.`catalogProductSizeId` = `cps`.`catalogProductSizeId`
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    GROUP BY
                        `cpst`.`catalogProductSizeId`
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb        = DBConnections::get();
        $aLocations = $oDb->query($sQuery, QRY_OBJECT, "CatalogProductSize");
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aLocations;
    }

    /**
     * save a CatalogProductSize
     *
     * @param CatalogProductSize $oProductSize
     */
    public static function saveProductSize(CatalogProductSize $oProductSize)
    {
        $sQuery = ' INSERT INTO `catalog_product_sizes`(
                        `catalogProductSizeId`,
                        `multilingual`,
                        `order`,
                        `created`
                    )
                    VALUES (
                        ' . db_int($oProductSize->catalogProductSizeId) . ',
                        ' . db_int($oProductSize->multilingual) . ',
                        ' . db_int($oProductSize->order) . ',
                        NOW()
                    )
                    ON DUPLICATE KEY UPDATE
                        `multilingual`=VALUES(`multilingual`),
                        `order`=VALUES(`order`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oProductSize->catalogProductSizeId === null) {
            $oProductSize->catalogProductSizeId = $oDb->insert_id;
        }
    }

    /**
     * delete a CatalogProductSize
     *
     * @param CatalogProductSize $oProductSize
     *
     * @return bool true
     */
    public static function deleteProductSize(CatalogProductSize $oProductSize)
    {
        if ($oProductSize->isDeletable()) {
            # delete object
            $sQuery = ' DELETE FROM
                        `catalog_product_sizes`
                    WHERE
                        `catalogProductSizeId` = ' . db_int($oProductSize->catalogProductSizeId) . '
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
     * return if size is unused
     *
     * @param int $iCatalogProductSizeId
     *
     * @return boolean
     */
    public static function isUnused($iCatalogProductSizeId)
    {
        $sQuery = ' SELECT
                        COUNT(*) AS `timesUsed`
                    FROM
                        `catalog_product_size_color_relations`
                    WHERE
                        `catalogProductSizeId` = ' . db_int($iCatalogProductSizeId) . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT)->timesUsed == 0;
    }

}
