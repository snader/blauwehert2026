<?php

class CatalogProductSizeColorRelationManager
{

    public static function getCatalogProductSizeColorRelation($iCatalogProductId, $iCatalogProductSizeId, $iCatalogProductColorId)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `catalog_product_size_color_relations`
                    WHERE
                        `catalogProductId` = ' . db_int($iCatalogProductId) . '
                    AND
                        `catalogProductSizeId` = ' . db_int($iCatalogProductSizeId) . '
                    AND
                        `catalogProductColorId` = ' . db_int($iCatalogProductColorId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "CatalogProductSizeColorRelation");
    }

    /**
     * get color size relations for catalogProduct by filter
     *
     * @param int   $iCatalogProductId
     * @param array $aFilter
     * @param int   $iLimit
     *
     * @return array CatalogProductColorSizeRelation
     */
    public static function getColorSizeRelationsByFilter(array $aFilter = [], $iLimit = null, $aOrderBy = ['`cps`.`order`' => 'ASC'])
    {
        $sWhere = '';
        if (empty($aFilter['showAll'])) {
            $sWhere .= (!empty($sWhere) ? ' AND' : ' ') . ' (`cpcsr`.`stock` > 0 OR `cpcsr`.`stock` IS NULL)';
        }

        if (!empty($aFilter['catalogProductId'])) {
            $sWhere .= (!empty($sWhere) ? ' AND' : ' ') . ' `cpcsr`.`catalogProductId` = ' . db_int($aFilter['catalogProductId']);
        }

        if (!empty($aFilter['catalogProductColorId'])) {
            $sWhere .= (!empty($sWhere) ? ' AND' : ' ') . ' `cpcsr`.`catalogProductColorId` = ' . db_int($aFilter['catalogProductColorId']);
        }

        if (!empty($aFilter['catalogProductSizeId'])) {
            $sWhere .= (!empty($sWhere) ? ' AND' : ' ') . ' `cpcsr`.`catalogProductSizeId` = ' . db_int($aFilter['catalogProductSizeId']);
        }

        # handle order by
        $sOrderBy = '';
        if (count($aOrderBy) > 0) {
            foreach ($aOrderBy AS $sColumn => $sOrder) {
                $sOrderBy .= ($sOrderBy !== '' ? ',' : '') . $sColumn . ' ' . $sOrder;
            }
        }
        $sOrderBy = ($sOrderBy !== '' ? 'ORDER BY ' : '') . $sOrderBy;

        $sQuery = ' SELECT
                        `cpcsr`.*
                    FROM
                        `catalog_product_size_color_relations` AS `cpcsr`
                    LEFT JOIN
                        `catalog_product_colors` AS `cpc` ON `cpc`.`catalogProductColorId` = `cpcsr`.`catalogProductColorId`
                    LEFT JOIN
                        `catalog_product_sizes` AS `cps` ON `cps`.`catalogProductSizeId` = `cpcsr`.`catalogProductSizeId`
                    WHERE
                    ' . $sWhere . '
                    ' . $sOrderBy . '
                    ' . ($iLimit ? 'LIMIT ' . $iLimit : '') . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, 'CatalogProductSizeColorRelation');
    }

    /**
     * save a CatalogProductSizeColorRelation
     *
     * @param CatalogProductSizeColorRelation $oProductSizeColorRelation
     */
    public static function saveCatalogProductSizeColorRelation(CatalogProductSizeColorRelation $oProductSizeColorRelation)
    {

        $sQuery = ' INSERT INTO `catalog_product_size_color_relations`(
                        `catalogProductId`,
                        `catalogProductSizeId`,
                        `catalogProductColorId`,
                        `catalogProductSizeColorMPN`,
                        `stock`,
                        `extraPrice`
                    )
                    VALUES (
                        ' . db_int($oProductSizeColorRelation->catalogProductId) . ',
                        ' . db_int($oProductSizeColorRelation->catalogProductSizeId) . ',
                        ' . db_int($oProductSizeColorRelation->catalogProductColorId) . ',
                        ' . db_str($oProductSizeColorRelation->catalogProductSizeColorMPN) . ',
                        ' . db_int($oProductSizeColorRelation->stock) . ',
                        ' . db_deci($oProductSizeColorRelation->extraPrice) . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `stock`=VALUES(`stock`),
                        `extraPrice`=VALUES(`extraPrice`),
                        `catalogProductSizeColorMPN`=VALUES(`catalogProductSizeColorMPN`)
                    ;';
        $oDb    = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    /**
     * delete a CatalogProductSizeColorRelation
     *
     * @param CatalogProductSizeColorRelation $oProductSizeColorRelation
     *
     * @return bool true
     */
    public static function deleteCatalogProductSizeColorRelation(CatalogProductSizeColorRelation $oProductSizeColorRelation)
    {
        # delete object
        $sQuery = ' DELETE FROM
                        `catalog_product_size_color_relations`
                    WHERE
                        `catalogProductId` = ' . db_int($oProductSizeColorRelation->catalogProductId) . '
                    AND
                        `catalogProductSizeId` ' . ($oProductSizeColorRelation->catalogProductSizeId ? ' = ' . db_int($oProductSizeColorRelation->catalogProductSizeId) : 'IS NULL') . '
                    AND
                        `catalogProductColorId` ' . ($oProductSizeColorRelation->catalogProductColorId ? ' = ' . db_int($oProductSizeColorRelation->catalogProductColorId) : 'IS NULL') . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        return true;
    }

}

?>