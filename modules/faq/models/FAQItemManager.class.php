<?php

class FAQItemManager
{

    /**
     * get the full FAQItem object by id
     *
     * @param int $ifaqItemId
     *
     * @return FAQItem
     */
    public static function getFAQItemById($ifaqItemId)
    {
        $sQuery = ' SELECT
                        `fi`.*
                    FROM
                        `faq_items` AS `fi`
                    WHERE
                        `fi`.`faqItemId` = ' . db_int($ifaqItemId) . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "FAQItem");
    }

    /**
     * save FAQItem object
     *
     * @param FAQItem $oFAQItem
     */
    public static function saveFAQItem(FAQItem $oFAQItem)
    {
        # save FAQ item
        $sQuery = ' INSERT INTO `faq_items` (
                        `faqItemId`,
                        `languageId`,
                        `question`,
                        `answer`,
                        `online`
                    ) 
                    VALUES (
                        ' . db_int($oFAQItem->faqItemId) . ',
                        ' . db_int($oFAQItem->languageId) . ',
                        ' . db_str($oFAQItem->question) . ',
                        ' . db_str($oFAQItem->answer) . ',
                        ' . db_int($oFAQItem->online) . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `languageId`=VALUES(`languageId`),
                        `question`=VALUES(`question`),
                        `answer`=VALUES(`answer`),
                        `online`=VALUES(`online`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oFAQItem->faqItemId === null) {
            $oFAQItem->faqItemId = $oDb->insert_id;
        }

        // save categories
        self::saveFAQItemCategoryRelations($oFAQItem);
    }

    /**
     * save FAQItem's relations with FAQItemCategory objects
     *
     * @param FAQItem $oFAQItem
     */
    private static function saveFAQItemCategoryRelations(FAQItem $oFAQItem)
    {
        $oDb = DBConnections::get();

        # define the NOT IT and VALUES part of the DELETE and INSERT query
        $sDeleteNotIn  = '';
        $sInsertValues = '';
        foreach ($oFAQItem->getCategories('all') as $oFAQItemCategory) {
            $sDeleteNotIn  .= ($sDeleteNotIn == '' ? '' : ',') . db_int($oFAQItemCategory->faqItemCategoryId);
            $sInsertValues .= ($sInsertValues == '' ? '' : ', ') . '(' . db_int($oFAQItem->faqItemId) . ', ' . db_int($oFAQItemCategory->faqItemCategoryId) . ')';
        }

        # delete the old objects
        $sQuery = ' DELETE FROM
                        `faq_item_categories_faq_items`
                    WHERE
                        `faqItemId` = ' . db_int($oFAQItem->faqItemId) . '
                        ' . ($sDeleteNotIn != '' ? 'AND `faqItemCategoryId` NOT IN (' . $sDeleteNotIn . ')' : '') . '
                    ;';
        $oDb->query($sQuery, QRY_NORESULT);

        if (!empty($sInsertValues)) {
            # save the objects
            $sQuery = ' INSERT IGNORE INTO `faq_item_categories_faq_items`(
                        `faqItemId`,
                        `faqItemCategoryId`
                    )
                    VALUES ' . $sInsertValues . '
                    ;';

            $oDb->query($sQuery, QRY_NORESULT);
        }
    }

    /**
     * delete FAQ item and all media
     *
     * @param FAQItem $oFAQItem
     *
     * @return Boolean
     */
    public static function deleteFAQItem(FAQItem $oFAQItem)
    {
        $oDb = DBConnections::get();

        /* check if FAQ item exists and is deletable */
        if ($oFAQItem->isDeletable()) {
            $sQuery = "DELETE FROM `faq_items` WHERE `faqItemId` = " . db_int($oFAQItem->faqItemId) . ";";
            $oDb->query($sQuery, QRY_NORESULT);

            return true;
        }

        return false;
    }

    /**
     * update online by FAQ item id
     *
     * @param int $bOnline
     * @param int $ifaqItemId
     *
     * @return boolean
     */
    public static function updateOnlineByfaqItemId($bOnline, $ifaqItemId)
    {
        $sQuery = ' UPDATE
                        `faq_items`
                    SET
                        `online` = ' . db_int($bOnline) . '
                    WHERE
                        `faqItemId` = ' . db_int($ifaqItemId) . '
                    ;';
        $oDb    = DBConnections::get();

        $oDb->query($sQuery, QRY_NORESULT);

        # check if something happened
        return $oDb->affected_rows > 0;
    }

    /**
     * return FAQ items filtered by a few options
     *
     * @param array $aFilter    filter properties (checkOnline)
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param bool|int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database coloumn name => order) add order by columns and orders
     *
     * @return array FAQItem
     */
    public static function getFAQItemsByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`fi`.`faqItemId`' => 'DESC'])
    {
        $sFrom    = '';
        $sWhere   = '';
        $sGroupBy = '';

        if (class_exists('FAQItemCategoryManager')) {
            // with FAQ categories so join
            $sFrom    .= 'LEFT JOIN `faq_item_categories_faq_items` AS `ficfi` ON `ficfi`.`faqItemId` = `fi`.`faqItemId`';
            $sGroupBy .= '`fi`.`faqItemId`'; // when join, also group by
            // check for categoryId in filter
            if (!empty($aFilter['faqItemCategoryId'])) {
                $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`ficfi`.`faqItemCategoryId` = ' . db_int($aFilter['faqItemCategoryId']);
            }
        }

        // no show all? only show online items
        if (empty($aFilter['showAll'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '
                            `fi`.`online` = 1
                        ';

            // check if FAQitems have at least 1 online category
            if (class_exists('FAQItemCategoryManager')) {
                $sFrom  .= 'JOIN `faq_item_categories` AS `fic` ON `fic`.`faqItemCategoryId` = `ficfi`.`faqItemCategoryId`';
                $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`fic`.`online` = 1';
            }
        }

        # get by languageId
        if (isset($aFilter['languageId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`fi`.`languageId` = ' . db_int($aFilter['languageId']);
        }

        # search for q
        if (!empty($aFilter['q'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`fi`.`question` LIKE ' . db_str('%' . $aFilter['q'] . '%') . ' OR `fi`.`answer` LIKE ' . db_str('%' . $aFilter['q'] . '%') . ')';
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
                        `fi`.*
                    FROM
                        `faq_items` AS `fi`
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ' . ($sGroupBy != '' ? 'GROUP BY ' . $sGroupBy : '') . '
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb       = DBConnections::get();
        $aFAQItems = $oDb->query($sQuery, QRY_OBJECT, "FAQItem");
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aFAQItems;
    }

    /**
     * get categories for FAQItem by filter
     *
     * @param int   $ifaqItemId
     * @param array $aFilter
     * @param int   $iLimit
     *
     * @return array FAQItemCategory
     */
    public static function getCategoriesByFilter($ifaqItemId, array $aFilter = [], $iLimit = null)
    {

        $sWhere = '';
        if (empty($aFilter['showAll'])) {
            $sWhere .= ' AND `fic`.`online` = 1';
        }

        $sQuery = ' SELECT
                        `fic`.*
                    FROM
                        `faq_item_categories` AS `fic`
                    JOIN
                        `faq_item_categories_faq_items` AS `ficfi` USING (`faqItemCategoryId`)
                    WHERE
                        `ficfi`.`faqItemId` = ' . db_int($ifaqItemId) . '
                    ' . $sWhere . '
                    ORDER BY
                        `fic`.`order` ASC, `fic`.`faqItemCategoryId` ASC
                    ' . ($iLimit ? 'LIMIT ' . db_int($iLimit) : '') . '
                    ;';
        $oDb    = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, 'FAQItemCategory');
    }

}

?>