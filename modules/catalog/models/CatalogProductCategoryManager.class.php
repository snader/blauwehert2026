<?php

class CatalogProductCategoryManager
{

    /**
     * get a CatalogProductCategory by id
     *
     * @param int $iProductCategoryId
     *
     * @return CatalogProductCategory
     */
    public static function getProductCategoryById($iProductCategoryId)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `catalog_product_categories`
                    WHERE
                        `catalogProductCategoryId` = ' . db_int($iProductCategoryId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "CatalogProductCategory");
    }

    /**
     * get CatalogProductCategory objects by parentCatalogProductCategoryId
     *
     * @param int  $iParentCategoryId
     * @param bool $bCheckOnline
     *
     * @return array CatalogProductCategory
     */
    public static function getProductCategoriesByParentCategoryId($iParentCategoryId, $bCheckOnline = false)
    {
        $sCheckOnline = '';
        if ($bCheckOnline) {
            $sCheckOnline = 'AND `online` = 1';
        }
        $sQuery = ' SELECT
                        *
                    FROM
                        `catalog_product_categories`
                    WHERE
                        `parentCatalogProductCategoryId` = ' . db_int($iParentCategoryId) . '
                        ' . $sCheckOnline . '
                    ORDER BY
                        `order` ASC,
                        `catalogProductCategoryId` ASC
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "CatalogProductCategory");
    }

    /**
     * save a CatalogProductCategory
     *
     * @param CatalogProductCategory $oProductCategory
     */
    public static function saveProductCategory(CatalogProductCategory $oProductCategory)
    {
        $oProductCategory->setLevel(); // recalculate level to page to be sure

        $sQuery = ' INSERT INTO `catalog_product_categories`(
                        `catalogProductCategoryId`,
                        `order`,
                        `online`,
                        `parentCatalogProductCategoryId`,
                        `level`,
                        `created`,
                        `lockParent`
                    )
                    VALUES (
                        ' . db_int($oProductCategory->catalogProductCategoryId) . ',
                        ' . db_int($oProductCategory->order) . ',
                        ' . db_int($oProductCategory->online) . ',
                        ' . db_int($oProductCategory->parentCatalogProductCategoryId) . ',
                        ' . db_int($oProductCategory->level) . ',
                        NOW(),
                        ' . db_int($oProductCategory->getLockParent()) . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `online`=VALUES(`online`),
                        `parentCatalogProductCategoryId`=VALUES(`parentCatalogProductCategoryId`),
                        `order`=VALUES(`order`),
                        `level`=VALUES(`level`),
                        `lockParent`=VALUES(`lockParent`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oProductCategory->catalogProductCategoryId === null) {
            $oProductCategory->catalogProductCategoryId = $oDb->insert_id;
        }
    }

    /**
     * update online status of CatalogProductCategory by id
     *
     * @param int $bOnline
     * @param int $iProductCategoryId
     *
     * @return bool
     */
    public static function updateOnlineByProductCategoryId($bOnline, $iProductCategoryId)
    {
        $sQuery = ' UPDATE
                        `catalog_product_categories`
                    SET
                        `online` = ' . db_int($bOnline) . '
                    WHERE
                        `catalogProductCategoryId` = ' . db_int($iProductCategoryId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        # check if something happened
        return $oDb->affected_rows > 0;
    }

    /**
     * delete a CatalogProductCategory
     *
     * @param CatalogProductCategory $oProductCategory
     *
     * @return bool
     */
    public static function deleteProductCategory(CatalogProductCategory $oProductCategory)
    {
        if ($oProductCategory->isDeletable()) {
            # delete object
            $sQuery = ' DELETE FROM
                        `catalog_product_categories`
                    WHERE
                        `catalogProductCategoryId` = ' . db_int($oProductCategory->catalogProductCategoryId) . '
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
     * return productCategories filtered by a few options
     *
     * @param array $aFilter    filter properties
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database coloumn name => order) add order by columns and orders
     *
     * @return array CatalogProductCategory objects
     */
    public static function getProductCategoriesByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`cpc`.`order`' => 'ASC'])
    {

        $sWhere = '';
        if (empty($aFilter['showAll'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cpc`.`online` = 1';
        }

        if (!empty($aFilter['level'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cpc`.`level` = ' . db_int($aFilter['level']);
        }

        if (!empty($aFilter['parentCatalogProductCategoryId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cpc`.`parentCatalogProductCategoryId` = ' . db_int($aFilter['parentCatalogProductCategoryId']);
        }

        # get product categories that changed last hour
        if (isset($aFilter['lastHourOnly'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . 'IFNULL(`cpc`.`modified`, `cpc`.`created`) > DATE_ADD(NOW(), INTERVAL -1 HOUR)';
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
                        `catalog_product_categories` AS `cpc`
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb                = DBConnections::get();
        $aProductCategories = $oDb->query($sQuery, QRY_OBJECT, "CatalogProductCategory");
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aProductCategories;
    }

}

?>