<?php

class ReviewManager
{

    /**
     * get a Review by id
     *
     * @param int $iReviewId
     *
     * @return Review
     */
    public static function getReviewById($iReviewId)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `reviews`
                    WHERE
                        `reviewId` = ' . db_int($iReviewId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "Review");
    }

    /**
     * get a Review by importId
     *
     * @param int $iImportId
     *
     * @return Review
     */
    public static function getReviewByImportId($iImportId)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `reviews`
                    WHERE
                        `importId` = ' . db_int($iImportId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "Review");
    }

    /**
     * return reviews filtered by a few options
     *
     * @param array $aFilter    filter properties
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database coloumn name => order) add order by columns and orders
     *
     * @return array Review
     */
    public static function getReviewsByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`r`.`order`' => 'ASC', '`r`.`reviewId`' => 'ASC'])
    {
        $sFrom  = '';
        $sWhere = '';

        // default is not to show all items
        if (empty($aFilter['showAll'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`r`.`online` = 1';
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
                        `r`.*
                    FROM
                        `reviews` AS `r`
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb      = DBConnections::get();
        $aReviews = $oDb->query($sQuery, QRY_OBJECT, "Review");
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aReviews;
    }

    /**
     * save a Review
     *
     * @param Review $oReview
     */
    public static function saveReview(Review $oReview)
    {
        $sQuery = ' INSERT INTO `reviews`(
                        `reviewId`,
                        `localeId`,
                        `importId`,
                        `title`,
                        `author`,
                        `review`,
                        `link`,
                        `reference`,
                        `rating`,
                        `online`,
                        `order`,
                        `created`
                    )
                    VALUES (
                        ' . db_int($oReview->reviewId) . ',
                        ' . db_int($oReview->localeId) . ',
                        ' . db_int($oReview->importId) . ',
                        ' . db_str($oReview->title) . ',
                        ' . db_str($oReview->author) . ',
                        ' . db_str($oReview->review) . ',
                        ' . db_str($oReview->link) . ',
                        ' . db_str($oReview->reference) . ',
                        ' . db_int($oReview->rating) . ',
                        ' . db_int($oReview->online) . ',
                        ' . db_int($oReview->order) . ',
                        NOW()
                    )
                    ON DUPLICATE KEY UPDATE
                        `localeId`=VALUES(`localeId`),
                        `title`=VALUES(`title`),
                        `author`=VALUES(`author`),
                        `review`=VALUES(`review`),
                        `link`=VALUES(`link`),
                        `reference`=VALUES(`reference`),
                        `rating`=VALUES(`rating`),
                        `online`=VALUES(`online`),
                        `order`=VALUES(`order`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oReview->reviewId === null) {
            $oReview->reviewId = $oDb->insert_id;
        }
    }

    /**
     * update online status of Review by id
     *
     * @param int $bOnline
     * @param int $iReviewId
     *
     * @return bool
     */
    public static function updateOnlineByReviewId($bOnline, $iReviewId)
    {
        $sQuery = ' UPDATE
                        `reviews`
                    SET
                        `online` = ' . db_int($bOnline) . '
                    WHERE
                        `reviewId` = ' . db_int($iReviewId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        # check if something happened
        return $oDb->affected_rows > 0;
    }

    /**
     * update a Review's order
     *
     * @param Review $oReview
     */
    public static function updateReviewOrder(Review $oReview)
    {
        $sQuery = ' UPDATE 
                        `reviews`
                    SET
                        `order` = ' . db_int($oReview->order) . '
                    WHERE
                        `reviewId` = ' . db_int($oReview->reviewId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    /**
     * delete a Review
     *
     * @param Review $oReview
     *
     * @return bool true
     */
    public static function deleteReview(Review $oReview)
    {
        # delete object
        $sQuery = ' DELETE FROM
                        `reviews`
                    WHERE
                        `reviewId` = ' . db_int($oReview->reviewId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        return true;
    }

    /**
     * save connection between a review and an image
     *
     * @param int $iReviewId
     * @param int $iImageId
     */
    public static function saveReviewImageRelation($iReviewId, $iImageId)
    {
        $sQuery = ' INSERT IGNORE INTO
                        `reviews_images`
                    (
                        `reviewId`,
                        `imageId`
                    )
                    VALUES
                    (
                        ' . db_int($iReviewId) . ',
                        ' . db_int($iImageId) . '
                    )
                    ;';
        $oDb    = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    /**
     * get images for reviews by filter
     *
     * @param int   $iReviewId
     * @param array $aFilter
     * @param int   $iLimit
     *
     * @return array Image
     */
    public static function getImagesByFilter($iReviewId, array $aFilter = [], $iLimit = null)
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
                        `reviews_images` AS `ri` USING (`imageId`)
                    WHERE
                        `ri`.`reviewId` = ' . db_int($iReviewId) . '
                    ' . $sWhere . '
                    ORDER BY
                        `i`.`order` ASC, `i`.`imageId` ASC
                    ' . ($iLimit ? 'LIMIT ' . db_int($iLimit) : '') . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, 'Image');
    }

}