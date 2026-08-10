<?php

class DynamicContentManager
{

    /**
     * get a DynamicContent by id
     *
     * @param int $iDynamicContentId
     *
     * @return Card
     */
    public static function getDynamicContentById($iDynamicContentId)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `dynamic_content`
                    WHERE
                        `dynamicContentId` = ' . db_int($iDynamicContentId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "DynamicContent");
    }

    /**
     * @param string $sName
     * @param null $iLanguageId
     * @param int  $bOnline
     *
     * @return array|mixed|null
     */
    public static function getDynamicContentByName($sName, $iLanguageId = null, $bOnline = 1)
    {
        if (empty($iLanguageId)) {
            $iLanguageId = Locales::language();
        }
        $sQuery = ' SELECT
                        *
                    FROM
                        `dynamic_content`
                    WHERE
                        `name` = ' . db_str($sName) . '
                    AND
                        `languageId` = ' . db_int($iLanguageId);

        if ($bOnline) {
            $sQuery .= ' AND `online` = ' . db_int($bOnline);
        }

        $sQuery .= ' LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "DynamicContent");
    }

    /**
     * return DynamicContents filtered by a few options
     *
     * @param array $aFilter    filter properties
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database coloumn name => order) add order by columns and orders
     *
     * @return array Card
     */
    public static function getDynamicContentByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`dc`.`name`' => 'ASC'])
    {
        $sFrom  = '';
        $sWhere = '';

        // default is not to show all items
        if (empty($aFilter['showAll'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`dc`.`online` = 1';
        }

        # get by languageId
        if (isset($aFilter['languageId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`dc`.`languageId` = ' . db_int($aFilter['languageId']);
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
                        `dc`.*
                    FROM
                        `dynamic_content` AS `dc`
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb    = DBConnections::get();
        $aDynamicContents = $oDb->query($sQuery, QRY_OBJECT, "DynamicContent");
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aDynamicContents;
    }

    /**
     * @param      $sName
     * @param null $iDynamicContentId
     * @param      $iLanguageId
     *
     * @return bool
     */
    public static function nameExists($sName, $iDynamicContentId = null, $iLanguageId)
    {
        $oDynamicContent = self::getDynamicContentByName($sName, $iLanguageId, null);
        if (!empty($oDynamicContent)) {
            if ($iDynamicContentId === null || $oDynamicContent->dynamicContentId != $iDynamicContentId) {
                return true;
            }
        }

        return false;
    }

    /**
     * save a DynamicContent
     *
     * @param DynamicContent $oDynamicContent
     */
    public static function saveDynamicContent(DynamicContent $oDynamicContent)
    {
        $sQuery = ' INSERT INTO `dynamic_content`(
                        `dynamicContentId`,
                        `languageId`,
                        `name`,
                        `content`,
                        `online`,
                        `type`,
                        `adminOnly`,
                        `created`
                    )
                    VALUES (
                        ' . db_int($oDynamicContent->dynamicContentId) . ',
                        ' . db_int($oDynamicContent->languageId) . ',
                        ' . db_str($oDynamicContent->name) . ',
                        ' . db_str($oDynamicContent->content) . ',
                        ' . db_int($oDynamicContent->online) . ',
                        ' . db_str($oDynamicContent->type) . ',
                        ' . db_int($oDynamicContent->adminOnly) . ',
                        NOW()
                    )
                    ON DUPLICATE KEY UPDATE
                        `languageId`=VALUES(`languageId`),
                        `name`=VALUES(`name`),
                        `content`=VALUES(`content`),
                        `online`=VALUES(`online`),
                        `type`=VALUES(`type`),
                        `adminOnly`=VALUES(`adminOnly`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oDynamicContent->dynamicContentId === null) {
            $oDynamicContent->dynamicContentId = $oDb->insert_id;
        }
    }

    /**
     * update online status of Card by id
     *
     * @param int $bOnline
     * @param int $iDynamicContentId
     *
     * @return bool
     */
    public static function updateOnlineByDynamicContentId($bOnline, $iDynamicContentId)
    {
        $sQuery = ' UPDATE
                        `dynamic_content`
                    SET
                        `online` = ' . db_int($bOnline) . '
                    WHERE
                        `dynamicContentId` = ' . db_int($iDynamicContentId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        # check if something happened
        return $oDb->affected_rows > 0;
    }

    /**
     * delete dynamic content
     *
     * @param DynamicContent $oDynamicContent
     *
     * @return bool true
     */
    public static function deleteDynamicContent(DynamicContent $oDynamicContent)
    {

        # delete object
        $sQuery = ' DELETE FROM
                        `dynamic_content`
                    WHERE
                        `dynamicContentId` = ' . db_int($oDynamicContent->dynamicContentId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        return true;
    }

}

