<?php

class FAQItemCategoryManager
{

    /**
     * get the full FAQItemCategory object by id
     *
     * @param int $ifaqItemCategoryId
     *
     * @return FAQItemCategory
     */
    public static function getFAQItemCategoryById($ifaqItemCategoryId)
    {
        $sQuery = ' SELECT
                        `fic`.*
                    FROM
                        `faq_item_categories` `fic`
                    WHERE
                        `fic`.`faqItemCategoryId` = ' . db_int($ifaqItemCategoryId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "FAQItemCategory");
    }

    /**
     * save FAQItemCategory object
     *
     * @param FAQItemCategory $oFAQItemCategory
     */
    public static function saveFAQItemCategory(FAQItemCategory $oFAQItemCategory)
    {

        # save FAQItem item
        $sQuery = ' INSERT INTO `faq_item_categories` (
                        `faqItemCategoryId`,
                        `languageId`,
                        `name`,
                        `online`,
                        `order`
                    ) 
                    VALUES (
                        ' . db_int($oFAQItemCategory->faqItemCategoryId) . ',
                        ' . db_int($oFAQItemCategory->languageId) . ',
                        ' . db_str($oFAQItemCategory->name) . ',
                        ' . db_int($oFAQItemCategory->online) . ',
                        ' . db_int($oFAQItemCategory->order) . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `languageId`=VALUES(`languageId`),
                        `name`=VALUES(`name`),
                        `online`=VALUES(`online`),
                        `order`=VALUES(`order`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oFAQItemCategory->faqItemCategoryId === null) {
            $oFAQItemCategory->faqItemCategoryId = $oDb->insert_id;
        }
    }

    /**
     * delete FAQItem item and all media
     *
     * @param FAQItemCategory $oFAQItemCategory
     *
     * @return Boolean
     */
    public static function deleteFAQItemCategory(FAQItemCategory $oFAQItemCategory)
    {
        /* check if FAQItem item exists and is deletable */
        if ($oFAQItemCategory->isDeletable()) {
            $sQuery = "DELETE FROM `faq_item_categories` WHERE `faqItemCategoryId` = " . db_int($oFAQItemCategory->faqItemCategoryId) . ";";

            $oDb = DBConnections::get();
            $oDb->query($sQuery, QRY_NORESULT);

            return true;
        }

        return false;
    }

    /**
     * update online by FAQItem item id
     *
     * @param int $bOnline
     * @param int $ifaqItemCategoryId
     *
     * @return boolean
     */
    public static function updateOnlineByfaqItemCategoryId($bOnline, $ifaqItemCategoryId)
    {
        $sQuery = ' UPDATE
                        `faq_item_categories`
                    SET
                        `online` = ' . db_int($bOnline) . '
                    WHERE
                        `faqItemCategoryId` = ' . db_int($ifaqItemCategoryId) . '
                    ;';
        $oDb    = DBConnections::get();

        $oDb->query($sQuery, QRY_NORESULT);

        # check if something happened
        return $oDb->affected_rows > 0;
    }

    /**
     * return FAQItemCategories filtered by a few options
     *
     * @param array $aFilter    filter properties
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database coloumn name => order) add order by columns and orders
     *
     * @return array Module
     */
    public static function getFAQItemCategoriesByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`fic`.`order`' => 'ASC', '`fic`.`faqItemCategoryId`' => 'ASC'])
    {
        $sFrom  = '';
        $sWhere = '';

        if (empty($aFilter['showAll'])) {
            $sFrom  .= 'JOIN `faq_item_categories_faq_items` AS `ficfi` USING(`faqItemCategoryId`)';
            $sFrom  .= 'JOIN `faq_items` AS `fi` USING(`faqItemId`)';
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`fic`.`online` = 1';
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '
                            `fi`.`online` = 1
                        ';
        }

        # get by languageId
        if (isset($aFilter['languageId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`fic`.`languageId` = ' . db_int($aFilter['languageId']);
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
                        `fic`.*
                    FROM
                        `faq_item_categories` AS `fic`
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    GROUP BY
                        `fic`.`faqItemCategoryId`
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb                = DBConnections::get();
        $aFAQItemCategories = $oDb->query($sQuery, QRY_OBJECT, "FAQItemCategory");
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aFAQItemCategories;
    }

    /**
     * get FAQItems for category by filter
     *
     * @param int   $ifaqItemCategoryId
     * @param array $aFilter
     * @param int   $iLimit
     * @param array $aOrderBy
     *
     * @return array FAQItem
     */
    public static function getFAQItemsByFilter($ifaqItemCategoryId, array $aFilter = [], $iLimit = null, $aOrderBy = ['`ficfi`.`order`' => 'ASC', '`fi`.`question`' => 'ASC'])
    {
        $sWhere = '';
        if (empty($aFilter['showAll'])) {
            $sWhere .= 'AND
                            `fi`.`online` = 1
                        ';
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
                        `fi`.*
                    FROM
                        `faq_items` AS `fi`
                    JOIN
                        `faq_item_categories_faq_items` AS `ficfi` USING (`faqItemId`)
                    WHERE
                        `ficfi`.`faqItemCategoryId` = ' . db_int($ifaqItemCategoryId) . '
                    ' . $sWhere . '
                    ' . $sOrderBy . '
                    ' . ($iLimit ? 'LIMIT ' . $iLimit : '') . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, 'FAQItem');
    }

    /**
     * set the Order of FAQitems in a FAQCategorie
     *
     * @param $iFaqItemId
     * @param $faqItemCategoryId
     * @param $iOrder
     */
    public static function saveFaqItemCategoryOrder($iFaqItemId, $faqItemCategoryId, $iOrder)
    {
        $sQuery = ' UPDATE
                        `faq_item_categories_faq_items`
                    SET
                        `order` = ' . db_int($iOrder) . '
                    WHERE
                        `faqItemId` = ' . db_int($iFaqItemId) . '
                      AND
                        `faqItemCategoryId` = ' . db_int($faqItemCategoryId) . '
                    LIMIT 1';
        $oDb    = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

}

?>