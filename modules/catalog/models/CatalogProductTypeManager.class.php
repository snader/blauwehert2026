<?php

class CatalogProductTypeManager
{

    /**
     * get a CatalogProductType by id
     *
     * @param int $iProductTypeId
     *
     * @return CatalogProductType
     */
    public static function getProductTypeById($iProductTypeId)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `catalog_product_types`
                    WHERE
                        `catalogProductTypeId` = ' . db_int($iProductTypeId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "CatalogProductType");
    }

    /**
     * get all CatalogProductType objects
     *
     * @return array CatalogProductType
     */
    public static function getAllProductTypes()
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `catalog_product_types` AS `cpt`
                    JOIN
                        `catalog_product_type_translations` AS `cptt` ON `cptt`.`catalogProductTypeId` = `cpt`.`catalogProductTypeId`
                    GROUP BY
                        `cpt`.`catalogProductTypeId`
                    ORDER BY
                        `cpt`.`order` ASC,
                        `cptt`.`title` ASC
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "CatalogProductType");
    }

    /**
     * save a CatalogProductType
     *
     * @param CatalogProductType $oProductType
     */
    public static function saveProductType(CatalogProductType $oProductType)
    {
        $sQuery = ' INSERT INTO `catalog_product_types`(
                        `catalogProductTypeId`,
                        `withSizes`,
                        `withColors`,
                        `withGenders`,
                        `order`,
                        `created`
                    )
                    VALUES (
                        ' . db_int($oProductType->catalogProductTypeId) . ',
                        ' . db_int($oProductType->withSizes) . ',
                        ' . db_int($oProductType->withColors) . ',
                        ' . db_int($oProductType->withGenders) . ',
                        ' . db_int($oProductType->order) . ',
                        NOW()
                    )
                    ON DUPLICATE KEY UPDATE
                        `withSizes`=VALUES(`withSizes`),
                        `withColors`=VALUES(`withColors`),
                        `withGenders`=VALUES(`withGenders`),
                        `order`=VALUES(`order`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oProductType->catalogProductTypeId === null) {
            $oProductType->catalogProductTypeId = $oDb->insert_id;
        }
    }

    /**
     * delete a CatalogProductType
     *
     * @param CatalogProductType $oCatalogProductType
     *
     * @return bool true
     */
    public static function deleteProductType(CatalogProductType $oCatalogProductType)
    {
        if ($oCatalogProductType->isDeletable()) {
            # delete object
            $sQuery = ' DELETE FROM
                        `catalog_product_types`
                    WHERE
                        `catalogProductTypeId` = ' . db_int($oCatalogProductType->catalogProductTypeId) . '
                    LIMIT 1
                    ;';
            $oDb    = DBConnections::get();
            $oDb->query($sQuery, QRY_NORESULT);

            return true;
        } else {
            return false;
        }
    }

    /**
     * count the number of product of a specified type
     *
     * @param int $iProductTypeId
     *
     * @return int
     */
    public static function getNumberOfProductsByProductTypeId($iProductTypeId)
    {
        # delete object
        $sQuery = ' SELECT
                        COUNT(*) AS `count`
                    FROM
                        `catalog_products`
                    WHERE
                        `catalogProductTypeId` = ' . db_int($iProductTypeId) . '
                    LIMIT 1
                    ;';
        $oDb    = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT)->count;
    }

    /**
     * return products filtered by a few options (!! ALSO APPLY CHANGES TO CataglogProductManager::getProductByFilter() !!)
     *
     * @param array $aFilter    filter properties
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database coloumn name => order) add order by columns and orders
     *
     * @return array CatalogProduct objects
     */
    public static function getProductTypesByProductFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`cptt`.`title`' => 'ASC'])
    {

        $sWhere = '';
        $sFrom  = '';
        if (empty($aFilter['showAll'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cp`.`online` = 1';
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cb`.`online` = 1';
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cpc1`.`online` = 1';
            // check categories backwards recursively
            for ($iC = 2; $iC <= CatalogProductCategory::MAX_LEVELS; $iC++) {
                $sFrom  .= 'LEFT JOIN `catalog_product_categories` AS `cpc' . $iC . '` ON `cpc' . ($iC) . '`.`catalogProductCategoryId` = `cpc' . ($iC - 1) . '`.`parentCatalogProductCategoryId`';
                $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`cpc' . $iC . '`.`online` = 1 OR `cpc' . ($iC - 1) . '`.`parentCatalogProductCategoryId` IS NULL)';
            }

            // check if product is in stock
            $sFrom  .= 'JOIN `catalog_product_size_color_relations` AS `cpscr` ON `cpscr`.`catalogProductId` = `cp`.`catalogProductId`';
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`cpscr`.`stock` > 0 OR `cpscr`.`stock` IS NULL)';
        }

        # search for q
        if (!empty($aFilter['q'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cp`.`title` LIKE ' . db_str('%' . $aFilter['q'] . '%');
        }

        # search for brand
        if (!empty($aFilter['catalogBrandId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cp`.`catalogBrandId` = ' . db_int($aFilter['catalogBrandId']);
        }

        # search for productType
        if (!empty($aFilter['catalogProductTypeId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cp`.`catalogProductTypeId` = ' . db_int($aFilter['catalogProductTypeId']);
        }

        # search for catalogProductCategory
        if (!empty($aFilter['catalogProductCategoryId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cpcpc`.`catalogProductCategoryId` = ' . db_int($aFilter['catalogProductCategoryId']);
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

        $sQuery = ' SELECT ' . ($iFoundRows === false ? 'SQL_CALC_FOUND_ROWS' : '') . '
                        `cpt`.*
                    FROM
                        `catalog_product_types` AS `cpt`
                    JOIN
                        `catalog_product_type_translations` AS `cptt` ON `cptt`.`catalogProductTypeId` = `cpt`.`catalogProductTypeId`
                    JOIN
                        `catalog_products` AS `cp` ON `cp`.`catalogProductTypeId` = `cpt`.`catalogProductTypeId`
                    JOIN
                        `catalog_brands` AS `cb` ON `cb`.`catalogBrandId` = `cp`.`catalogBrandId`                        
                    JOIN
                        `catalog_products_catalog_product_categories` AS `cpcpc` ON `cpcpc`.`catalogProductId` = `cp`.`catalogProductId`                        
                    JOIN
                        `catalog_product_categories` AS `cpc1` ON `cpc1`.`catalogProductCategoryId` = `cpcpc`.`catalogProductCategoryId`                        
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    GROUP BY
                        `cp`.`catalogProductTypeId`
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb       = DBConnections::get();
        $aProducts = $oDb->query($sQuery, QRY_OBJECT, "CatalogProductType");
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aProducts;
    }

}

?>