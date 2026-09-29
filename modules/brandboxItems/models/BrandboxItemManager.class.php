<?php

class BrandboxItemManager
{

    /**
     * get a BrandboxItem by id
     *
     * @param int $iBrandboxItemId
     *
     * @return BrandboxItem
     */
    public static function getBrandboxItemById($iBrandboxItemId)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `brandbox_items`
                    WHERE
                        `brandboxItemId` = ' . db_int($iBrandboxItemId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "BrandboxItem");
    }

    /**
     * return brandbox items filtered by a few options
     *
     * @param array $aFilter    filter properties
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database coloumn name => order) add order by columns and orders
     *
     * @return array BrandboxItem
     */
    public static function getBrandboxItemsByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`bi`.`order`' => 'ASC', '`bi`.`brandboxItemId`' => 'ASC'])
    {
        $sFrom  = '';
        $sWhere = '';

        // default is not to show all items
        if (empty($aFilter['showAll'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`bi`.`online` = 1';
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`bi`.`imageId` IS NOT NULL';
        }

        # get by languageId
        if (isset($aFilter['languageId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`bi`.`languageId` = ' . db_int($aFilter['languageId']);
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

        $sQuery = ' SELECT ' . ($iFoundRows !== false ? 'SQL_CALC_FOUND_ROWS' : '') . '
                        `bi`.*
                    FROM
                        `brandbox_items` AS `bi`
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb            = DBConnections::get();
        $aBrandboxItems = $oDb->query($sQuery, QRY_OBJECT, "BrandboxItem");
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aBrandboxItems;
    }

    /**
     * save a BrandboxItem
     *
     * @param BrandboxItem $oBrandboxItem
     */
    public static function saveBrandboxItem(BrandboxItem $oBrandboxItem)
    {
        $sQuery = ' INSERT INTO `brandbox_items`(
                        `brandboxItemId`,
                        `languageId`,
                        `name`,
                        `link`,
                        `pageId`,
                        `newsItemId`,
                        `line1`,
                        `line2`,
                        `online`,
                        `order`,
                        `created`
                    )
                    VALUES (
                        ' . db_int($oBrandboxItem->brandboxItemId) . ',
                        ' . db_int($oBrandboxItem->languageId) . ',
                        ' . db_str($oBrandboxItem->name) . ',
                        ' . db_str($oBrandboxItem->link) . ',
                        ' . db_int($oBrandboxItem->pageId) . ',
                        ' . db_int($oBrandboxItem->newsItemId) . ',
                        ' . db_str($oBrandboxItem->line1) . ',
                        ' . db_str($oBrandboxItem->line2) . ',
                        ' . db_int($oBrandboxItem->online) . ',
                        ' . db_int($oBrandboxItem->order) . ',
                        NOW()
                    )
                    ON DUPLICATE KEY UPDATE
                        `languageId`=VALUES(`languageId`),
                        `name`=VALUES(`name`),
                        `link`=VALUES(`link`),
                        `pageId`=VALUES(`pageId`),
                        `newsItemId`=VALUES(`newsItemId`),
                        `line1`=VALUES(`line1`),
                        `line2`=VALUES(`line2`),
                        `online`=VALUES(`online`),
                        `order`=VALUES(`order`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oBrandboxItem->brandboxItemId === null) {
            $oBrandboxItem->brandboxItemId = $oDb->insert_id;
        }
    }

    /**
     * update online status of BrandboxItem by id
     *
     * @param int $bOnline
     * @param int $iBrandboxItemId
     *
     * @return bool
     */
    public static function updateOnlineByBrandboxItemId($bOnline, $iBrandboxItemId)
    {
        $sQuery = ' UPDATE
                        `brandbox_items`
                    SET
                        `online` = ' . db_int($bOnline) . '
                    WHERE
                        `brandboxItemId` = ' . db_int($iBrandboxItemId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        # check if something happened
        return $oDb->affected_rows > 0;
    }

    /**
     * update a BrandboxItem's order
     *
     * @param BrandboxItem $oBrandboxItem
     */
    public static function updateBrandboxItemOrder(BrandboxItem $oBrandboxItem)
    {
        $sQuery = ' UPDATE 
                        `brandbox_items`
                    SET
                        `order` = ' . db_int($oBrandboxItem->order) . '
                    WHERE
                        `brandboxItemId` = ' . db_int($oBrandboxItem->brandboxItemId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    /**
     * update a BrandboxItem with the related imageId
     *
     * @param int $iBrandboxItemId
     * @param int $iImageId
     */
    public static function saveBrandboxItemImageRelations($iBrandboxItemId, $iImageId)
    {
        $sQuery = ' UPDATE 
                        `brandbox_items`
                    SET
                        `imageId` = ' . db_int($iImageId) . '
                    WHERE
                        `brandboxItemId` = ' . db_int($iBrandboxItemId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    /**
     * update a BrandboxItem with the related mediaId
     *
     * @param int $iBrandboxItemId
     * @param int $iMediaId
     */
    public static function saveBrandboxItemMediaRelations($iBrandboxItemId, $iMediaId)
    {
        $sQuery = ' UPDATE 
                        `brandbox_items`
                    SET
                        `mediaId` = ' . db_int($iMediaId) . '
                    WHERE
                        `brandboxItemId` = ' . db_int($iBrandboxItemId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    /**
     * delete a BrandboxItem
     *
     * @param BrandboxItem $oBrandboxItem
     *
     * @return bool true
     */
    public static function deleteBrandboxItem(BrandboxItem $oBrandboxItem)
    {
        # delete related Image
        if ($oBrandboxItem->getImage()) {
            ImageManager::deleteImage($oBrandboxItem->getImage());
        }

        # delete related VideoLink
        if ($oBrandboxItem->getVideoLinks()) {
            VideoLinkManager::deleteVideoLink($oBrandboxItem->getVideoLinks());
        }

        # delete object
        $sQuery = ' DELETE FROM
                        `brandbox_items`
                    WHERE
                        `brandboxItemId` = ' . db_int($oBrandboxItem->brandboxItemId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        return true;
    }

}
