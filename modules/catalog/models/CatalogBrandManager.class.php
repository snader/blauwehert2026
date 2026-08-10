<?php

class CatalogBrandManager
{

    /**
     * get a CatalogBrand by id
     *
     * @param int $iBrandId
     *
     * @return CatalogBrand
     */
    public static function getBrandById($iBrandId)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `catalog_brands`
                    WHERE
                        `catalogBrandId` = ' . db_int($iBrandId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "CatalogBrand");
    }

    /**
     * get all CatalogBrand objects
     *
     * @return array CatalogBrand
     */
    public static function getAllBrands()
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `catalog_brands` AS `cb`
                    JOIN
                        `catalog_brand_translations` AS `cbt` ON `cbt`.`catalogBrandId` = `cb`.`catalogBrandId`
                    GROUP BY
                        `cb`.`catalogBrandId`
                    ORDER BY
                        `cb`.`order` ASC,
                        `cbt`.`name` ASC
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "CatalogBrand");
    }

    /**
     * return brands filtered by a few options
     *
     * @param array $aFilter    filter properties (checkOnline)
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database coloumn name => order) add order by columns and orders
     *
     * @return array CatalogBrand objects
     */
    public static function getBrandsByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`cb`.`order`' => 'ASC', '`cbt`.`name`' => 'ASC'])
    {

        $sWhere = '';
        if (empty($aFilter['showAll'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cb`.`online` = 1';
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
                        `cb`.*
                    FROM
                        `catalog_brands` AS `cb`
                    JOIN
                        `catalog_brand_translations` AS `cbt` ON `cbt`.`catalogBrandId` = `cb`.`catalogBrandId`
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb        = DBConnections::get();
        $aLocations = $oDb->query($sQuery, QRY_OBJECT, "CatalogBrand");
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aLocations;
    }

    /**
     * save a CatalogBrand
     *
     * @param CatalogBrand $oBrand
     */
    public static function saveBrand(CatalogBrand $oBrand)
    {
        $sQuery = ' INSERT INTO `catalog_brands`(
                        `catalogBrandId`,
                        `multilingual`,
                        `order`,
                        `online`,
                        `created`
                    )
                    VALUES (
                        ' . db_int($oBrand->catalogBrandId) . ',
                        ' . db_int($oBrand->multilingual) . ',
                        ' . db_int($oBrand->order) . ',
                        ' . db_int($oBrand->online) . ',
                        NOW()
                    )
                    ON DUPLICATE KEY UPDATE
                        `multilingual`=VALUES(`multilingual`),
                        `online`=VALUES(`online`),
                        `order`=VALUES(`order`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oBrand->catalogBrandId === null) {
            $oBrand->catalogBrandId = $oDb->insert_id;
        }
    }

    /**
     * update online status of CatalogBrand by id
     *
     * @param int $bOnline
     * @param int $iBrandId
     *
     * @return bool
     */
    public static function updateOnlineByBrandId($bOnline, $iBrandId)
    {
        $sQuery = ' UPDATE
                        `catalog_brands`
                    SET
                        `online` = ' . db_int($bOnline) . '
                    WHERE
                        `catalogBrandId` = ' . db_int($iBrandId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        # check if something happened
        return $oDb->affected_rows > 0;
    }

    /**
     * delete a CatalogBrand
     *
     * @param CatalogBrand $oBrand
     *
     * @return bool true
     */
    public static function deleteBrand(CatalogBrand $oBrand)
    {
        if ($oBrand->isDeletable()) {
            # delete object
            $sQuery = ' DELETE FROM
                        `catalog_brands`
                    WHERE
                        `catalogBrandId` = ' . db_int($oBrand->catalogBrandId) . '
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
     * count the number of product of a specified Brand
     *
     * @param int $iBrandId
     *
     * @return int
     */
    public static function getNumberOfProductsByBrandId($iBrandId)
    {
        # delete object
        $sQuery = ' SELECT
                        COUNT(*) AS `count`
                    FROM
                        `catalog_products`
                    WHERE
                        `catalogBrandId` = ' . db_int($iBrandId) . '
                    LIMIT 1
                    ;';
        $oDb    = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT)->count;
    }

}

?>