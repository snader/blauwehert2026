<?php

class CatalogProductImageRelationManager
{
    /**
     * get images for catalogProduct by filter
     *
     * @param int   $iCatalogProductId
     * @param array $aFilter
     * @param int   $iLimit
     *
     * @return array Image
     */
    public static function getCatalogProductImageRelationById($iCatalogProductId, $iImageId)
    {
        $sQuery = ' SELECT
                        `cpi`.*
                    FROM
                        `catalog_products_images` AS `cpi`
                    WHERE
                        `cpi`.`catalogProductId` = ' . db_int($iCatalogProductId) . '
                    AND
                        `cpi`.`imageId` = ' . db_int($iImageId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, 'CatalogProductImageRelation');
    }

    /**
     * get proudct images relations
     *
     * @param int   $iCatalogProductId
     * @param array $aFilter
     * @param int   $iLimit
     *
     * @return array Image
     */
    public static function getCatalogProductImageRelationsByFilter(array $aFilter = [], $iLimit = null)
    {
        $sWhere = '';
        // I show all the Images, online and not online
        if (empty($aFilter['showAll'])) {
            $sWhere .= (!empty($sWhere) ? ' AND' : ' ') . ' `i`.`online` = 1';
        }

        // I filter for a product
        if (!empty($aFilter['catalogProductId'])) {
            $sWhere .= (!empty($sWhere) ? ' AND' : ' ') . ' `cpi`.`catalogProductId` =' . $aFilter['catalogProductId'];
        }

        // I filter for a color
        if (!empty($aFilter['catalogProductColorId'])) {
            $sWhere .= (!empty($sWhere) ? ' AND' : ' ') . ' `cpi`.`catalogProductColorId` =' . $aFilter['catalogProductColorId'];
        }

        // I filter for a Image id
        if (!empty($aFilter['imageId'])) {
            $sWhere .= (!empty($sWhere) ? ' AND' : ' ') . ' `cpi`.`imageId` = ' . $aFilter['imageId'];
        }

        // I filter for Not Null colors
        if (!empty($aFilter['notNullColors'])) {
            $sWhere .= (!empty($sWhere) ? ' AND' : ' ') . ' `cpi`.`catalogProductColorId` IS NOT NULL';
        }

        $sQuery = ' SELECT
                        `cpi`.*
                    FROM
                        `images` AS `i`
                    JOIN
                        `catalog_products_images` AS `cpi` USING (`imageId`)
                    WHERE
                    ' . $sWhere . '
                    ORDER BY
                        `cpi`.`catalogProductId` ASC, `cpi`.`catalogProductColorId` ASC
                    ' . ($iLimit ? 'LIMIT ' . $iLimit : '') . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, 'CatalogProductImageRelation');
    }

    /**
     * save a CatalogProductImageRelation
     *
     * @param CatalogProductImageRelation $oCatalogProductImageRelation
     */
    public static function saveCatalogProductImageRelation(CatalogProductImageRelation $oCatalogProductImageRelation)
    {

        $sQuery = ' INSERT INTO `catalog_products_images`(
                        `catalogProductId`,
                        `imageId`,
                        `catalogProductColorId`
                    )
                    VALUES (
                        ' . db_int($oCatalogProductImageRelation->catalogProductId) . ',
                        ' . db_int($oCatalogProductImageRelation->imageId) . ',
                        ' . db_str($oCatalogProductImageRelation->catalogProductColorId) . '

                    )
                    ON DUPLICATE KEY UPDATE
                        `catalogProductColorId`=VALUES(`catalogProductColorId`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    /**
     * delete a ProductImageRelation
     *
     * @param CatalogProductImageRelation $oCatalogProductImageRelation
     *
     * @return bool true
     */
    public static function deleteCatalogProductImageRelation(CatalogProductImageRelation $oCatalogProductImageRelation)
    {
        # delete object
        $sQuery = ' DELETE FROM
                        `catalogProductsImage`
                    WHERE
                        `catalogProductId` = ' . db_int($oCatalogProductImageRelation->catalogProductId) . '
                    AND
                        `imageId` = ' . db_int($oCatalogProductImageRelation->imageId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        return true;
    }

    /**
     * set to null a color in a ProductImageRelation
     *
     * @param CatalogProductImageRelation $oCatalogProductImageRelation
     *
     * @return bool true
     */
    public static function resetCatalogProductImageColorRelation(CatalogProductImageRelation $oCatalogProductImageRelation)
    {
        # reset Color object
        $sQuery = ' UPDATE
                        `catalog_products_images`
                    SET
                        `catalogProductColorId` = NULL
                    WHERE
                        `catalogProductId` = ' . db_int($oCatalogProductImageRelation->catalogProductId) . '
                    AND
                        `imageId` = ' . db_int($oCatalogProductImageRelation->imageId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        return true;
    }

    /**
     * get images for catalogProduct by filter
     *
     * @param int   $iCatalogProductId
     * @param array $aFilter
     * @param int   $iLimit
     *
     * @return array Image
     */
    public static function getCatalogProductImageRelationImageById($iCatalogProductId, $iImageid, $iCatalogProductColorId)
    {

        if (!empty($iCatalogProductColorId)) {
            $sWhere = ' AND `cpi`.`catalogProductColorId` = ' . db_int($iCatalogProductColorId) . '';
        }
        $sQuery = ' SELECT
                        `i`.*
                    FROM
                        `images` AS `i`
                    JOIN
                        `catalog_products_images` AS `cpi` USING (`imageId`)
                    WHERE
                        `cpi`.`catalogProductId` = ' . db_int($iCatalogProductId) . '
                        AND `cpi`.`imageId` = ' . db_int($iImageid) . '
                        ' . $sWhere . ' 
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, 'Image');
    }

}

?>