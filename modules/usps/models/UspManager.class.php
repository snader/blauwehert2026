<?php

class UspManager
{

    /**
     * get a Usp by id
     *
     * @param int $iUspId
     *
     * @return Usp
     */
    public static function getUspById($iUspId)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `usps`
                    WHERE
                        `uspId` = ' . db_int($iUspId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "Usp");
    }

    /**
     * return Usp items filtered by a few options
     *
     * @param array $aFilter    filter properties
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database coloumn name => order) add order by columns and orders
     *
     * @return array Usp
     */
    public static function getUspsByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`u`.`order`' => 'ASC', '`u`.`uspId`' => 'ASC'])
    {
        $sFrom  = '';
        $sWhere = '';

        // default is not to show all items
        if (empty($aFilter['showAll'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`u`.`online` = 1';
        }

        # get by languageId
        if (isset($aFilter['languageId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`u`.`languageId` = ' . db_int($aFilter['languageId']);
        }

        if (!empty($aFilter['pageId'])) {
            $sFrom  .= 'JOIN `pages_usps` AS `pu` ON `pu`.`uspId` = `u`.`uspId`
                ';
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`pu`.`pageId` = ' . db_int($aFilter['pageId']);
        }

        if (!empty($aFilter['NOTpageId'])) {
            $sFrom  .= 'LEFT OUTER JOIN `pages_usps` AS `pu` ON `pu`.`uspId` = `u`.`uspId` AND `pu`.`pageId` = ' . db_int($aFilter['NOTpageId']);
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`pu`.`pageId` IS NULL)';
        }

        if (!empty($aFilter['q'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`u`.`name` LIKE ' . db_str('%' . $aFilter['q'] . '%') . ' OR `u`.`textLine` LIKE ' . db_str('%' . $aFilter['q'] . '%') . ')';
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
                        `u`.*
                    FROM
                        `usps` AS `u`
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb   = DBConnections::get();
        $aUsps = $oDb->query($sQuery, QRY_OBJECT, "Usp");
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aUsps;
    }

    /**
     * save a Usp
     *
     * @param Usp $oUsp
     */
    public static function saveUsp(Usp $oUsp)
    {
        $sQuery = ' INSERT INTO `usps`(
                        `uspId`,
                        `languageId`,
                        `pageId`,
                        `textLine`,
                        `name`,
                        `link`,
                        `online`,
                        `order`,
                        `created`
                    )
                    VALUES (
                        ' . db_int($oUsp->uspId) . ',
                        ' . db_int($oUsp->languageId) . ',
                        ' . db_int($oUsp->pageId) . ',
                        ' . db_str($oUsp->textLine) . ',
                        ' . db_str($oUsp->name) . ',
                        ' . db_str($oUsp->link) . ',
                        ' . db_int($oUsp->online) . ',
                        ' . db_int($oUsp->order) . ',
                        NOW()
                    )
                    ON DUPLICATE KEY UPDATE
                        `languageId`=VALUES(`languageId`),
                        `pageId`=VALUES(`pageId`),
                        `textLine`=VALUES(`textLine`),
                        `name`=VALUES(`name`),
                        `link`=VALUES(`link`),
                        `online`=VALUES(`online`),
                        `order`=VALUES(`order`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oUsp->uspId === null) {
            $oUsp->uspId = $oDb->insert_id;
        }
    }

    /**
     * update online status of Usp by id
     *
     * @param int $bOnline
     * @param int $iUspId
     *
     * @return bool
     */
    public static function updateOnlineByUspId($bOnline, $iUspId)
    {
        $sQuery = ' UPDATE
                        `usps`
                    SET
                        `online` = ' . db_int($bOnline) . '
                    WHERE
                        `uspId` = ' . db_int($iUspId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        # check if something happened
        return $oDb->affected_rows > 0;
    }

    /**
     * update a Usp's order
     *
     * @param Usp $oUsp
     */
    public static function updateUspOrder(Usp $oUsp)
    {
        $sQuery = ' UPDATE 
                        `usps`
                    SET
                        `order` = ' . db_int($oUsp->order) . '
                    WHERE
                        `uspId` = ' . db_int($oUsp->uspId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    /**
     * update a Usp with the related imageId
     *
     * @param int $iUspId
     * @param int $iFileId
     */
    public static function saveFileRelation($iUspId, $iFileId)
    {
        $sQuery = ' UPDATE 
                        `usps`
                    SET
                        `fileId` = ' . db_int($iFileId) . '
                    WHERE
                        `uspId` = ' . db_int($iUspId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    /**
     * update a Usp with the related imageId
     *
     * @param int $iUspId
     * @param int $iImageId
     */
    public static function saveImageRelation($iUspId, $iImageId)
    {
        $sQuery = ' UPDATE 
                        `usps`
                    SET
                        `imageId` = ' . db_int($iImageId) . '
                    WHERE
                        `uspId` = ' . db_int($iUspId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    /**
     * delete a Usp
     *
     * @param Usp $oUsp
     *
     * @return bool true
     */
    public static function deleteUsp(Usp $oUsp)
    {
        # delete related Image
        if ($oUsp->getFile()) {
            FileManager::deleteFile($oUsp->getFile());
        }

        # delete object
        $sQuery = ' DELETE FROM
                        `usps`
                    WHERE
                        `uspId` = ' . db_int($oUsp->uspId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        return true;
    }
}
