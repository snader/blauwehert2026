<?php

class NewsItemManager
{

    /**
     * get the full NewsItem object by id
     *
     * @param int $iNewsItemId
     * @param int $iLocaleId
     *
     * @return NewsItem
     */
    public static function getNewsItemById($iNewsItemId)
    {
        $sQuery = ' SELECT
                        `ni`.*
                    FROM
                        `news_items` AS `ni`
                    WHERE
                        `ni`.`newsItemId` = ' . db_int($iNewsItemId) . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "NewsItem");
    }

    /**
     * save NewsItem object
     *
     * @param NewsItem $oNewsItem
     */
    public static function saveNewsItem(NewsItem $oNewsItem)
    {
        # save news item
        $sQuery = ' INSERT INTO `news_items` (
                        `newsItemId`,
                        `languageId`,
                        `windowTitle`,
                        `metaKeywords`,
                        `metaDescription`,
                        `title`,
                        `intro`,
                        `content`,
                        `shortTitle`,
                        `date`,
                        `source`,
                        `onlineFrom`,
                        `onlineTo`,
                        `online`,
                        `created`
                    ) 
                    VALUES (
                        ' . db_int($oNewsItem->newsItemId) . ',
                        ' . db_int($oNewsItem->languageId) . ',
                        ' . db_str($oNewsItem->windowTitle) . ',
                        ' . db_str($oNewsItem->metaKeywords) . ',
                        ' . db_str($oNewsItem->metaDescription) . ',
                        ' . db_str($oNewsItem->title) . ',
                        ' . db_str($oNewsItem->intro) . ',
                        ' . db_str($oNewsItem->content) . ',
                        ' . db_str($oNewsItem->shortTitle) . ',
                        ' . db_date($oNewsItem->date) . ',
                        ' . db_str($oNewsItem->source) . ',
                        ' . db_date($oNewsItem->onlineFrom) . ',
                        ' . db_date($oNewsItem->onlineTo) . ',
                        ' . db_int($oNewsItem->online) . ',
                        ' . 'NOW()' . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `languageId`=VALUES(`languageId`),
                        `windowTitle`=VALUES(`windowTitle`),
                        `metaKeywords`=VALUES(`metaKeywords`),
                        `metaDescription`=VALUES(`metaDescription`),
                        `title`=VALUES(`title`),
                        `intro`=VALUES(`intro`),
                        `content`=VALUES(`content`),
                        `shortTitle`=VALUES(`shortTitle`),
                        `date`=VALUES(`date`),
                        `source`=VALUES(`source`),
                        `onlineFrom`=VALUES(`onlineFrom`),
                        `onlineTo`=VALUES(`onlineTo`),
                        `online`=VALUES(`online`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oNewsItem->newsItemId === null) {
            $oNewsItem->newsItemId = $oDb->insert_id;
        }

        // save categories
        self::saveNewsItemCategoryRelations($oNewsItem);
    }

    /**
     * save NewsItem's relations with NewsItemCategory objects
     *
     * @param NewsItem $oNewsItem
     */
    private static function saveNewsItemCategoryRelations(NewsItem $oNewsItem)
    {
        $oDb = DBConnections::get();

        # define the NOT IT and VALUES part of the DELETE and INSERT query
        $sDeleteNotIn  = '';
        $sInsertValues = '';
        foreach ($oNewsItem->getCategories('all') as $oNewsItemCategory) {
            $sDeleteNotIn  .= ($sDeleteNotIn == '' ? '' : ',') . db_int($oNewsItemCategory->newsItemCategoryId);
            $sInsertValues .= ($sInsertValues == '' ? '' : ', ') . '(' . db_int($oNewsItem->newsItemId) . ', ' . db_int($oNewsItemCategory->newsItemCategoryId) . ')';
        }

        # delete the old objects
        $sQuery = ' DELETE FROM
                        `news_item_categories_news_items`
                    WHERE
                        `newsItemId` = ' . db_int($oNewsItem->newsItemId) . '
                        ' . ($sDeleteNotIn != '' ? 'AND `newsItemCategoryId` NOT IN (' . $sDeleteNotIn . ')' : '') . '
                    ;';
        $oDb->query($sQuery, QRY_NORESULT);

        if (!empty($sInsertValues)) {
            # save the objects
            $sQuery = ' INSERT IGNORE INTO `news_item_categories_news_items`(
                        `newsItemId`,
                        `newsItemCategoryId`
                    )
                    VALUES ' . $sInsertValues . '
                    ;';

            $oDb->query($sQuery, QRY_NORESULT);
        }
    }

    /**
     * delete news item and all media
     *
     * @param NewsItem $oNewsItem
     *
     * @return Boolean
     */
    public static function deleteNewsItem(NewsItem $oNewsItem)
    {
        $oDb = DBConnections::get();

        /* check if news item exists and is deletable */
        if ($oNewsItem->isDeletable()) {

            # get and delete images
            foreach ($oNewsItem->getImages('all') AS $oImage) {
                ImageManager::deleteImage($oImage);
            }

            # get and delete files
            foreach ($oNewsItem->getFiles('all') AS $oFile) {
                FileManager::deleteFile($oFile);
            }

            # get and delete links
            foreach ($oNewsItem->getLinks('all') AS $oLink) {
                LinkManager::deleteLink($oLink);
            }

            # get and delete video links
            foreach ($oNewsItem->getVideoLinks('all') AS $oVideoLink) {
                VideoLinkManager::deleteVideoLink($oVideoLink);
            }

            $sQuery = "DELETE FROM `news_items` WHERE `newsItemId` = " . db_int($oNewsItem->newsItemId) . ";";
            $oDb->query($sQuery, QRY_NORESULT);

            return true;
        }

        return false;
    }

    /**
     * update online by news item id
     *
     * @param int $bOnline
     * @param int $iNewsItemId
     *
     * @return boolean
     */
    public static function updateOnlineByNewsItemId($bOnline, $iNewsItemId)
    {
        $sQuery = ' UPDATE
                        `news_items`
                    SET
                        `online` = ' . db_int($bOnline) . '
                    WHERE
                        `newsItemId` = ' . db_int($iNewsItemId) . '
                    ;';
        $oDb    = DBConnections::get();

        $oDb->query($sQuery, QRY_NORESULT);

        # check if something happened
        return $oDb->affected_rows > 0;
    }

    /**
     * return news items filtered by a few options
     *
     * @param array $aFilter    filter properties (checkOnline)
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database coloumn name => order) add order by columns and orders
     *
     * @return array NewsItem
     */
    public static function getNewsItemsByFilter(
        array $aFilter = [],
        $iLimit = null,
        $iStart = 0,
        &$iFoundRows = false,
        $aOrderBy = [
            '`ni`.`date`'       => 'DESC',
            '`ni`.`newsItemId`' => 'DESC',
        ]
    ) {
        $sFrom    = '';
        $sWhere   = '';
        $sGroupBy = '';

        if (class_exists('NewsItemCategoryManager')) {
            // with news categories so join
            $sFrom    .= 'LEFT JOIN `news_item_categories_news_items` AS `nicni` ON `nicni`.`newsItemId` = `ni`.`newsItemId`';
            $sGroupBy .= '`ni`.`newsItemId`'; // when join, also group by
            // check for categoryId in filter
            if (!empty($aFilter['newsItemCategoryId'])) {
                $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`nicni`.`newsItemCategoryId` = ' . db_int($aFilter['newsItemCategoryId']);
            }
        }

        // no show all? only show online items
        if (empty($aFilter['showAll'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '
                            `ni`.`online` = 1
                        AND
                            `ni`.`onlineFrom` <= NOW()
                        AND
                            (`ni`.`onlineTo` >= NOW() OR `ni`.`onlineTo` IS NULL)
                        ';

            // check if newsitems have at least 1 online category
            if (class_exists('NewsItemCategoryManager')) {
                $sFrom  .= 'JOIN `news_item_categories` AS `nic` ON `nic`.`newsItemCategoryId` = `nicni`.`newsItemCategoryId`
                        ';
                $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`nic`.`online` = 1';
            }
        }

        # get by languageId
        if (isset($aFilter['languageId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`ni`.`languageId` = ' . db_int($aFilter['languageId']);
        }

        # search for q
        if (!empty($aFilter['q'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`ni`.`title` LIKE ' . db_str('%' . $aFilter['q'] . '%') . ' OR `ni`.`intro` LIKE ' . db_str('%' . $aFilter['q'] . '%') . ' OR `ni`.`content` LIKE ' . db_str(
                    '%' . $aFilter['q'] . '%'
                ) . ')';
        }

        # get newsitems with that changed last hour
        if (isset($aFilter['lastHourOnly'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . 'IFNULL(`ni`.`modified`, `ni`.`created`) > DATE_ADD(NOW(), INTERVAL -1 HOUR)';
        }

        // get newsItems by pageId
        if (!empty($aFilter['pageId'])) {
            $sFrom  .= 'JOIN `pages_news_items` AS `pni` ON `pni`.`newsItemId` = `ni`.`newsItemId`';
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`pni`.`pageId` = ' . db_int($aFilter['pageId']);
        }

        // get newsItems by pageId
        if (!empty($aFilter['NOTpageId'])) {
            $sFrom  .= 'LEFT OUTER JOIN `pages_news_items` AS `pni` ON `pni`.`newsItemId` = `ni`.`newsItemId` AND `pni`.`pageId` = ' . db_int($aFilter['NOTpageId']);
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`pni`.`pageId` IS NULL)';
        }

        if (!empty($aFilter['whitePaperId'])) {
            $sFrom  .= 'JOIN `white_papers_news_items` AS `wpni` ON `wpni`.`newsItemId` = `ni`.`newsItemId`';
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`wpni`.`whitePaperId` = ' . db_int($aFilter['whitePaperId']);
        }

        if (!empty($aFilter['NOTwhitePaperId'])) {
            $sFrom  .= 'LEFT OUTER JOIN `white_papers_news_items` AS `wpni` ON `wpni`.`newsItemId` = `ni`.`newsItemId` AND `wpni`.`newsItemId` = ' . db_int($aFilter['NOTwhitePaperId']);
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`wpni`.`newsItemId` IS NULL)';
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
                        `ni`.*
                    FROM
                        `news_items` AS `ni`
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ' . ($sGroupBy != '' ? 'GROUP BY ' . $sGroupBy : '') . '
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb        = DBConnections::get();
        $aNewsItems = $oDb->query($sQuery, QRY_OBJECT, "NewsItem");
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aNewsItems;
    }

    /**
     * save connection between a newsItem and an image
     *
     * @param int $iNewsItemId
     * @param int $iImageId
     */
    public static function saveNewsItemImageRelation($iNewsItemId, $iImageId)
    {
        $sQuery = ' INSERT IGNORE INTO
                        `news_items_images`
                    (
                        `newsItemId`,
                        `imageId`
                    )
                    VALUES
                    (
                        ' . db_int($iNewsItemId) . ',
                        ' . db_int($iImageId) . '
                    )
                    ;';
        $oDb    = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    /**
     * get images for newsItem by filter
     *
     * @param int   $iNewsItemId
     * @param array $aFilter
     * @param int   $iLimit
     *
     * @return array Image
     */
    public static function getImagesByFilter($iNewsItemId, array $aFilter = [], $iLimit = null)
    {
        $sWhere = '';
        if (empty($aFilter['showAll'])) {
            $sWhere .= ' AND `i`.`online` = 1';
        }

        $sQuery = ' SELECT
                        `i`.*
                    FROM
                        `images` AS `i`
                    JOIN
                        `news_items_images` AS `pi` USING (`imageId`)
                    WHERE
                        `pi`.`newsItemId` = ' . db_int($iNewsItemId) . '
                    ' . $sWhere . '
                    ORDER BY
                        `i`.`order` ASC, `i`.`imageId` ASC
                    ' . ($iLimit ? 'LIMIT ' . db_int($iLimit) : '') . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, 'Image');
    }

    /**
     * save connection between a newsItem and a file
     *
     * @param int $iNewsItemId
     * @param int $iMediaId
     */
    public static function saveNewsItemFileRelation($iNewsItemId, $iMediaId)
    {
        $sQuery = ' INSERT IGNORE INTO
                        `news_items_files`
                    (
                        `newsItemId`,
                        `mediaId`
                    )
                    VALUES
                    (
                        ' . db_int($iNewsItemId) . ',
                        ' . db_int($iMediaId) . '
                    )
                    ;';
        $oDb    = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    /**
     * get files for newsItem by filter
     *
     * @param int   $iNewsItemId
     * @param array $aFilter
     * @param int   $iLimit
     *
     * @return array File
     */
    public static function getFilesByFilter($iNewsItemId, array $aFilter = [], $iLimit = null)
    {

        $sWhere = '';
        if (empty($aFilter['showAll'])) {
            $sWhere .= ' AND `m`.`online` = 1';
        }

        $sQuery = ' SELECT
                        `m`.*,
                        `f`.*
                    FROM
                        `files` AS `f`
                    JOIN
                        `news_items_files` AS `pf` USING (`mediaId`)
                    JOIN
                        `media` AS `m` USING (`mediaId`)
                    WHERE
                        `pf`.`newsItemId` = ' . db_int($iNewsItemId) . '
                    ' . $sWhere . '
                    ORDER BY
                        `m`.`order` ASC, `m`.`mediaId` ASC
                    ' . ($iLimit ? 'LIMIT ' . db_int($iLimit) : '') . '
                    ;';
        $oDb    = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, 'File');
    }

    /**
     * save connection between a newsItem and a link
     *
     * @param int $iNewsItemId
     * @param int $iMediaId
     */
    public static function saveNewsItemLinkRelation($iNewsItemId, $iMediaId)
    {
        $sQuery = ' INSERT IGNORE INTO
                        `news_items_links`
                    (
                        `newsItemId`,
                        `mediaId`
                    )
                    VALUES
                    (
                        ' . db_int($iNewsItemId) . ',
                        ' . db_int($iMediaId) . '
                    )
                    ;';
        $oDb    = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    /**
     * get links for newsItem by filter
     *
     * @param int   $iNewsItemId
     * @param array $aFilter
     * @param int   $iLimit
     *
     * @return array Link
     */
    public static function getLinksByFilter($iNewsItemId, array $aFilter = [], $iLimit = null)
    {

        $sWhere = '';
        if (empty($aFilter['showAll'])) {
            $sWhere .= ' AND `m`.`online` = 1';
        }

        $sQuery = ' SELECT
                        `m`.*
                    FROM
                        `media` AS `m`
                    JOIN
                        `news_items_links` AS `pl` USING (`mediaId`)
                    WHERE
                        `pl`.`newsItemId` = ' . db_int($iNewsItemId) . '
                    ' . $sWhere . '
                    ORDER BY
                        `m`.`order` ASC, `m`.`mediaId` ASC
                    ' . ($iLimit ? 'LIMIT ' . db_int($iLimit) : '') . '
                    ;';
        $oDb    = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, 'Link');
    }

    /**
     * save connection between a newsItem and a VideoLink
     *
     * @param int $iNewsItemId
     * @param int $iMediaId
     */
    public static function saveNewsItemVideoLinkRelation($iNewsItemId, $iMediaId)
    {
        $sQuery = ' INSERT IGNORE INTO
                        `news_items_video_links`
                    (
                        `newsItemId`,
                        `mediaId`
                    )
                    VALUES
                    (
                        ' . db_int($iNewsItemId) . ',
                        ' . db_int($iMediaId) . '
                    )
                    ;';
        $oDb    = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    /**
     * get video links for newsItem by filter
     *
     * @param int   $iNewsItemId
     * @param array $aFilter
     * @param int   $iLimit
     *
     * @return array VideoLink
     */
    public static function getVideoLinksByFilter($iNewsItemId, array $aFilter = [], $iLimit = null)
    {
        $sWhere = '';
        if (empty($aFilter['showAll'])) {
            $sWhere .= ' AND `m`.`online` = 1';
        }

        $sQuery = ' SELECT
                        `m`.*
                    FROM
                        `media` AS `m`
                    JOIN
                        `news_items_video_links` AS `py` USING (`mediaId`)
                    WHERE
                        `py`.`newsItemId` = ' . db_int($iNewsItemId) . '
                    ' . $sWhere . '
                    ORDER BY
                        `m`.`order` ASC, `m`.`mediaId` ASC
                    ' . ($iLimit ? 'LIMIT ' . db_int($iLimit) : '') . '
                    ;';

        return VideoLinkManager::getVideoLinkByQuery($sQuery);
    }

    /**
     * get categories for newsItem by filter
     *
     * @param int   $iNewsItemId
     * @param array $aFilter
     * @param int   $iLimit
     *
     * @return array NewsItemCategory
     */
    public static function getCategoriesByFilter($iNewsItemId, array $aFilter = [], $iLimit = null)
    {

        $sWhere = '';
        if (empty($aFilter['showAll'])) {
            $sWhere .= ' AND `nic`.`online` = 1';
        }

        $sQuery = ' SELECT
                        `nic`.*
                    FROM
                        `news_item_categories` AS `nic`
                    JOIN
                        `news_item_categories_news_items` AS `nicni` USING (`newsItemCategoryId`)
                    WHERE
                        `nicni`.`newsItemId` = ' . db_int($iNewsItemId) . '
                    ' . $sWhere . '
                    ORDER BY
                        `nic`.`order` ASC, `nic`.`newsItemCategoryId` ASC
                    ' . ($iLimit ? 'LIMIT ' . db_int($iLimit) : '') . '
                    ;';
        $oDb    = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, 'NewsItemCategory');
    }

    public static function saveNewsItemPageRelation(int $iNewsItemId, int $iPageId)
    {
        $sQuery = ' INSERT IGNORE INTO `pages_news_items`(
                        `pageId`,
                        `newsItemId`
                    )
                    VALUES (
                            ' . db_int($iPageId) . ',
                            ' . db_int($iNewsItemId) . '
                            );';
        $oDb    = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    public static function deleteNewsItemPageRelation(int $iNewsItemId, int $iPageId)
    {
        $sQuery = 'DELETE FROM `pages_news_items` WHERE `pageId` = ' . db_int($iPageId) . ' AND `newsItemId` = ' . db_int($iNewsItemId);
        $oDb    = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

}

?>