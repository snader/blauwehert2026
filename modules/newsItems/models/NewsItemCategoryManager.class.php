<?php

class NewsItemCategoryManager
{

    /**
     * get the full NewsItemCategory object by id
     *
     * @param int $iNewsItemCategoryId
     *
     * @return NewsItemCategory
     */
    public static function getNewsItemCategoryById($iNewsItemCategoryId)
    {
        $sQuery = ' SELECT
                        `nic`.*
                    FROM
                        `news_item_categories` `nic`
                    WHERE
                        `nic`.`newsItemCategoryId` = ' . db_int($iNewsItemCategoryId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "NewsItemCategory");
    }

    /**
     * get the full NewsItemCategory object by urlPart
     *
     * @param string $sUrlPart
     * @param int    $iLanguageId
     *
     * @return NewsItemCategory
     */
    public static function getNewsItemCategoryByUrlPart($sUrlPart, $iLanguageId)
    {
        $sQuery = ' SELECT
                        `nic`.*
                    FROM
                        `news_item_categories` AS `nic`
                    WHERE
                        `nic`.`urlPart` = ' . db_str($sUrlPart) . '
                    AND
                        `nic`.`languageId` = ' . db_int($iLanguageId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "NewsItemCategory");
    }

    /**
     * check if urlPart exists excluding given NewsItemCategory
     *
     * @param string $sPartToCheck
     * @param int    $iNewsItemCategoryId
     * @param int    $iLanguageId
     *
     * @return bool
     */
    private static function urlPartExists($sPartToCheck, $iNewsItemCategoryId, $iLanguageId)
    {
        $oNewsItemCategory = self::getNewsItemCategoryByUrlPart($sPartToCheck, $iLanguageId);
        if ($oNewsItemCategory) {
            if ($oNewsItemCategory->newsItemCategoryId != $iNewsItemCategoryId) {
                return true;
            }
        }

        return false;
    }

    /**
     * save NewsItemCategory object
     *
     * @param NewsItemCategory $oNewsItemCategory
     */
    public static function saveNewsItemCategory(NewsItemCategory $oNewsItemCategory)
    {

        $sGeneratedUrlPart = $oNewsItemCategory->generateUrlPart();
        $iT                = 0;
        $sPartToCheck      = $sGeneratedUrlPart;

        # while urlPath is not unique, excluding this page, make unique
        while (self::urlPartExists($sPartToCheck, $oNewsItemCategory->newsItemCategoryId, $oNewsItemCategory->languageId)) {
            $iT++;
            $sPartToCheck = $sGeneratedUrlPart . "-$iT";
        }

        # part is last unique part
        $sGeneratedUrlPart = $sPartToCheck;

        # save newsItem item
        $sQuery = ' INSERT INTO `news_item_categories` (
                        `newsItemCategoryId`,
                        `languageId`,
                        `windowTitle`,
                        `metaKeywords`,
                        `metaDescription`,
                        `name`,
                        `urlPart`,
                        `urlPartText`,
                        `online`,
                        `order`,
                        `created`
                    ) 
                    VALUES (
                        ' . db_int($oNewsItemCategory->newsItemCategoryId) . ',
                        ' . db_int($oNewsItemCategory->languageId) . ',
                        ' . db_str($oNewsItemCategory->windowTitle) . ',
                        ' . db_str($oNewsItemCategory->metaKeywords) . ',
                        ' . db_str($oNewsItemCategory->metaDescription) . ',
                        ' . db_str($oNewsItemCategory->name) . ',
                        ' . db_str($sGeneratedUrlPart) . ',
                        ' . db_str($oNewsItemCategory->getUrlPartText()) . ',
                        ' . db_int($oNewsItemCategory->online) . ',
                        ' . db_int($oNewsItemCategory->order) . ',
                        ' . 'NOW()' . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `languageId`=VALUES(`languageId`),
                        `windowTitle`=VALUES(`windowTitle`),
                        `metaKeywords`=VALUES(`metaKeywords`),
                        `metaDescription`=VALUES(`metaDescription`),
                        `name`=VALUES(`name`),
                        `urlPart`=VALUES(`urlPart`),
                        `urlPartText`=VALUES(`urlPartText`),
                        `online`=VALUES(`online`),
                        `order`=VALUES(`order`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oNewsItemCategory->newsItemCategoryId === null) {
            $oNewsItemCategory->newsItemCategoryId = $oDb->insert_id;
        }
    }

    /**
     * delete newsItem item and all media
     *
     * @param NewsItemCategory $oNewsItemCategory
     *
     * @return Boolean
     */
    public static function deleteNewsItemCategory(NewsItemCategory $oNewsItemCategory)
    {
        /* check if newsItem item exists and is deletable */
        if ($oNewsItemCategory->isDeletable()) {
            $sQuery = "DELETE FROM `news_item_categories` WHERE `newsItemCategoryId` = " . db_int($oNewsItemCategory->newsItemCategoryId) . ";";

            $oDb = DBConnections::get();
            $oDb->query($sQuery, QRY_NORESULT);

            return true;
        }

        return false;
    }

    /**
     * update online by newsItem item id
     *
     * @param int $bOnline
     * @param int $iNewsItemCategoryId
     *
     * @return boolean
     */
    public static function updateOnlineByNewsItemCategoryId($bOnline, $iNewsItemCategoryId)
    {
        $sQuery = ' UPDATE
                        `news_item_categories`
                    SET
                        `online` = ' . db_int($bOnline) . '
                    WHERE
                        `newsItemCategoryId` = ' . db_int($iNewsItemCategoryId) . '
                    ;';
        $oDb    = DBConnections::get();

        $oDb->query($sQuery, QRY_NORESULT);

        # check if something happened
        return $oDb->affected_rows > 0;
    }

    /**
     * return newsItemCategories filtered by a few options
     *
     * @param array $aFilter    filter properties
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database coloumn name => order) add order by columns and orders
     *
     * @return array Module
     */
    public static function getNewsItemCategoriesByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`nic`.`order`' => 'ASC', '`nic`.`newsItemCategoryId`' => 'ASC'])
    {
        $sFrom  = '';
        $sWhere = '';

        if (empty($aFilter['showAll'])) {
            $sFrom  .= 'JOIN `news_item_categories_news_items` AS `nicni` USING(`newsItemCategoryId`)';
            $sFrom  .= 'JOIN `news_items` AS `ni` USING(`newsItemId`)';
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`nic`.`online` = 1';
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '
                            `ni`.`online` = 1
                        AND
                            `ni`.`onlineFrom` <= NOW()
                        AND
                            (`ni`.`onlineTo` >= NOW() OR `ni`.`onlineTo` IS NULL)
                        ';
        }

        # get by languageId
        if (isset($aFilter['languageId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`nic`.`languageId` = ' . db_int($aFilter['languageId']);
        }

        # get newsitemcategories that changed last hour
        if (isset($aFilter['lastHourOnly'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . 'IFNULL(`nic`.`modified`, `nic`.`created`) > DATE_ADD(NOW(), INTERVAL -1 HOUR)';
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
                        `nic`.*
                    FROM
                        `news_item_categories` AS `nic`
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    GROUP BY
                        `nic`.`newsItemCategoryId`
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb                 = DBConnections::get();
        $aNewsItemCategories = $oDb->query($sQuery, QRY_OBJECT, "NewsItemCategory");
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aNewsItemCategories;
    }

    /**
     * get newsItems for category by filter
     *
     * @param int   $iNewsItemCategoryId
     * @param array $aFilter
     * @param int   $iLimit
     *
     * @return array NewsItem
     */
    public static function getNewsItemsByFilter($iNewsItemCategoryId, array $aFilter = [], $iLimit = null)
    {
        $sWhere = '';
        if (empty($aFilter['showAll'])) {
            $sWhere .= 'AND
                            `ni`.`online` = 1
                        AND
                            `ni`.`onlineFrom` <= NOW()
                        AND
                            (`ni`.`onlineTo` >= NOW() OR `ni`.`onlineTo` IS NULL)
                        ';
        }

        $sQuery = ' SELECT
                        `ni`.*
                    FROM
                        `news_items` AS `ni`
                    JOIN
                        `news_item_categories_news_items` AS `nicni` USING (`newsItemId`)
                    WHERE
                        `nicni`.`newsItemCategoryId` = ' . db_int($iNewsItemCategoryId) . '
                    ' . $sWhere . '
                    ORDER BY
                        `ni`.`date` DESC, `ni`.`newsItemId` DESC
                    ' . ($iLimit ? 'LIMIT ' . $iLimit : '') . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, 'NewsItem');
    }

}

?>