<?php

class CatalogProductManager
{
    /*
     * get a CatalogProduct by id
     * @param int $iProduct
     * @return CatalogProduct
     */

    public static function getProductById($iProduct)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `catalog_products`
                    WHERE
                        `catalogProductId` = ' . db_int($iProduct) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "CatalogProduct");
    }

    /**
     * save a CatalogProduct
     *
     * @param CatalogProduct $oProduct
     */
    public static function saveProduct(CatalogProduct $oProduct)
    {

        $sQuery = ' INSERT INTO `catalog_products`(
                        `catalogProductId`,
                        `salePrice`,
                        `purchasePrice`,
                        `reducedPrice`,
                        `catalogProductMPN`,
                        `gender`,
                        `online`,
                        `created`,
                        `catalogBrandId`,
                        `catalogProductTypeId`,
                        `taxPercentageId`,
                        `showOnHome`
                    )
                    VALUES (
                        ' . db_int($oProduct->catalogProductId) . ',
                        ' . db_str(round($oProduct->getSalePrice(Settings::get('taxIncluded'), null, null, false, false), 4)) . ',
                        ' . db_str(round($oProduct->getPurchasePrice(Settings::get('taxIncluded')), 4)) . ',
                        ' . db_str($oProduct->getReducedPrice(Settings::get('taxIncluded')) !== null ? round($oProduct->getReducedPrice(Settings::get('taxIncluded')), 4) : null) . ',
                        ' . db_str($oProduct->catalogProductMPN) . ',
                        ' . db_str($oProduct->gender) . ',
                        ' . db_int($oProduct->online) . ',
                        NOW(),
                        ' . db_int($oProduct->catalogBrandId) . ',
                        ' . db_int($oProduct->catalogProductTypeId) . ',
                        ' . db_int($oProduct->taxPercentageId) . ',
                        ' . db_int($oProduct->showOnHome) . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `salePrice`=VALUES(`salePrice`),
                        `purchasePrice`=VALUES(`purchasePrice`),
                        `reducedPrice`=VALUES(`reducedPrice`),
                        `catalogProductMPN`=VALUES(`catalogProductMPN`),
                        `gender`=VALUES(`gender`),
                        `online`=VALUES(`online`),
                        `catalogBrandId`=VALUES(`catalogBrandId`),
                        `catalogProductTypeId`=VALUES(`catalogProductTypeId`),
                        `taxPercentageId`=VALUES(`taxPercentageId`),
                        `showOnHome`=VALUES(`showOnHome`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oProduct->catalogProductId === null) {
            $oProduct->catalogProductId = $oDb->insert_id;
        }

        #save product property value relations
        self::saveProductPropertyValueRelations($oProduct);

        #save product category relations
        self::saveProductCategoryRelations($oProduct);
    }

    /**
     * save a CatalogProduct's CatalogProductPropertyValue objects
     *
     * @param CatalogProduct $oProduct
     */
    private static function saveProductPropertyValueRelations(CatalogProduct $oProduct)
    {
        $oDb = DBConnections::get();

        # set property values in object if not yet done otherwise all will be deleted if none is set
        $oProduct->getAllPropertyValues();

        # define the VALUES part of the INSERT query
        $sDeleteNotIn = '';
        $sValues      = '';
        foreach ($oProduct->getPropertyValues() as $oPropertyValue) {
            $sDeleteNotIn .= ($sDeleteNotIn == '' ? '' : ', ') . db_str($oPropertyValue->catalogProductPropertyTypeId . $oPropertyValue->value);
            $sQuery       = ' SELECT * FROM
                            `catalog_product_property_values`
                        WHERE
                            `catalogProductId` = ' . db_int($oProduct->catalogProductId) . '
                        AND 
                            `catalogProductPropertyTypeId` = ' . db_int($oPropertyValue->catalogProductPropertyTypeId) . '
                        AND
                            `languageId` = ' . db_int($oPropertyValue->languageId) . '
                        AND 
                            `value` ' . ($oPropertyValue->value === '' || $oPropertyValue->value === null ? 'IS NULL' : '= ' . db_str($oPropertyValue->value)) . '
                        ';
            $aResult      = $oDb->query($sQuery, QRY_UNIQUE_ARRAY);
            if (!empty($aResult)) {
                continue;
            }

            $sValues .= ($sValues == '' ? '' : ', ') . '(' . db_int($oProduct->catalogProductId) . ', ' . db_int($oPropertyValue->catalogProductPropertyTypeId) . ', ' . db_int($oPropertyValue->languageId) . ', ' . db_str(
                    $oPropertyValue->value
                ) . ')';
        }

        # delete the old objects
        $sQuery = ' DELETE FROM
                        `catalog_product_property_values`
                    WHERE
                        `catalogProductId` = ' . db_int($oProduct->catalogProductId) . '
                        ' . ($sDeleteNotIn != '' ? 'AND CONCAT(`catalogProductPropertyTypeId`,IFNULL(`value`, \'\')) NOT IN (' . $sDeleteNotIn . ')' : '') . '
                    ;';

        $oDb->query($sQuery, QRY_NORESULT);

        if (!empty($sValues)) {
            # save the objects
            $sQuery = ' INSERT INTO `catalog_product_property_values`(
                        `catalogProductId`,
                        `catalogProductPropertyTypeId`,
                        `languageId`,
                        `value`
                    )
                    VALUES ' . $sValues . '
                    ON DUPLICATE KEY UPDATE
                        `catalogProductId`=`catalogProductId`,
                        `catalogProductPropertyTypeId`=`catalogProductPropertyTypeId`,
                        `languageId`=`languageId`,
                        `value`=`value`
                    ;';

            $oDb->query($sQuery, QRY_NORESULT);
        }
    }

    /**
     * save product category relations
     *
     * @param CatalogProduct $oProduct
     */
    private static function saveProductCategoryRelations(CatalogProduct $oProduct)
    {
        $oDb = DBConnections::get();

        # define the NOT IN and VALUES part of the DELETE and INSERT query
        $sDeleteNotIn  = '';
        $sInsertValues = '';
        foreach ($oProduct->getCategories('all') as $oCatalogProductCategory) {
            $sDeleteNotIn  .= ($sDeleteNotIn == '' ? '' : ',') . db_int($oCatalogProductCategory->catalogProductCategoryId);
            $sInsertValues .= ($sInsertValues == '' ? '' : ', ') . '(' . db_int($oProduct->catalogProductId) . ', ' . db_int($oCatalogProductCategory->catalogProductCategoryId) . ')';
        }

        # delete the old objects
        $sQuery = ' DELETE FROM
                        `catalog_products_catalog_product_categories`
                    WHERE
                        `catalogProductId` = ' . db_int($oProduct->catalogProductId) . '
                        ' . ($sDeleteNotIn != '' ? 'AND `catalogProductCategoryId` NOT IN (' . $sDeleteNotIn . ')' : '') . '
                    ;';
        $oDb->query($sQuery, QRY_NORESULT);

        if (!empty($sInsertValues)) {
            # save the objects
            $sQuery = ' INSERT IGNORE INTO `catalog_products_catalog_product_categories`(
                        `catalogProductId`,
                        `catalogProductCategoryId`
                    )
                    VALUES ' . $sInsertValues . '
                    ;';

            $oDb->query($sQuery, QRY_NORESULT);
        }
    }

    /**
     * update online status of CatalogProduct by id
     *
     * @param int $bOnline
     * @param int $iProductId
     *
     * @return bool
     */
    public static function updateOnlineByProductId($bOnline, $iProductId)
    {
        $sQuery = ' UPDATE
                        `catalog_products`
                    SET
                        `online` = ' . db_int($bOnline) . '
                    WHERE
                        `catalogProductId` = ' . db_int($iProductId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        # check if something happened
        return $oDb->affected_rows > 0;
    }

    /**
     * delete a CatalogProduct
     *
     * @param CatalogProduct $oProduct
     *
     * @return bool true
     */
    public static function deleteProduct(CatalogProduct $oProduct)
    {
        # delete related Image objects first
        foreach ($oProduct->getImages('all') as $oImage) {
            ImageManager::deleteImage($oImage);
        }

        # delete object
        $sQuery = ' DELETE FROM
                        `catalog_products`
                    WHERE
                        `catalogProductId` = ' . db_int($oProduct->catalogProductId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        return true;
    }

    /**
     * search within CatalogProduct objects
     *
     * @param string $sQ              search query
     * @param int    $iLimit          maximum number of products to return
     * @param int    $iLimitStart     the starting number of the LIMIT
     * @param int    &$iCountProducts reference for the number of total items without the LIMIT
     *
     * @return array CatalogProduct
     */
    public static function searchProducts($sQ, $iLimit = null, $iLimitStart = 0, &$iCountProducts = 0)
    {
        # trim the search query first
        $sQ = trim($sQ);

        # check if the search query is empty
        if (empty($sQ)) {
            return [];
        }

        # explode the search query to split it into words
        $aQ = explode(' ', $sQ);

        # check if the search query is empty
        if (empty($aQ) || empty($aQ[0])) {
            return [];
        }

        # define the WHERE part
        $sWherePart = "";
        foreach ($aQ as $sQPart) {
            $sWherePart .= "    AND
                                (
                                    `cpTRANS`.`name` LIKE " . db_str("%" . $sQPart . "%") . " OR 
                                    `cptTRANS`.`title` LIKE " . db_str("%" . $sQPart . "%") . " OR 
                                    `cpbTRANS`.`name` LIKE " . db_str("%" . $sQPart . "%") . "
                                ) ";
        }

        # define the LIMIT part
        $sLimit = "";
        if (is_numeric($iLimit) && is_numeric($iLimitStart)) {
            $sLimit = " LIMIT " . db_int($iLimitStart) . ", " . db_int($iLimit) . " ";
        }

        # define the query
        $sQuery = " SELECT SQL_CALC_FOUND_ROWS
                        `cp`.*,
                        `cpTRANS`.*
                    FROM
                        `catalog_products` AS `cp`
                    JOIN
                        `catalog_product_translations` AS `cpTRANS` ON `cpTRANS`.`catalogProductId` = `cp`.`catalogProductId`
                    JOIN 
                        `catalog_product_types` `cpt` USING (`catalogProductTypeId`)
                    JOIN
                        `catalog_product_type_translations` AS `cptTRANS` ON `cptTRANS`.`catalogProductTypeId` = `cp`.`catalogProductTypeId`
                    JOIN 
                        `catalog_brands` `cb` USING (`catalogBrandId`)
                    JOIN
                        `catalog_product_brand_translations` AS `cpbTRANS` ON `cpbTRANS`.`catalogBrandId` = `cp`.`catalogBrandId`
                    WHERE
                        `cp`.`online` = 1
                        " . $sWherePart . "
                    ORDER BY
                        CASE
                            WHEN CONCAT_WS(' ', `cpbTRANS`.`name`, `cpTRANS`.`name`) LIKE " . db_str($sQ . "%") . " THEN 0
                            WHEN `cpTRANS`.`name` LIKE " . db_str($sQ . "%") . " THEN 0
                            WHEN CONCAT_WS(' ', `cpbTRANS`.`name`, `cpTRANS`.`name`) LIKE " . db_str("%" . $sQ . "%") . " THEN 1
                            ELSE 99
                        END,
                        `cpTRANS`.`name` ASC
                    " . $sLimit . "
                    ;";

        $oDb = DBConnections::get();

        # get the objects
        $aProducts = $oDb->query($sQuery, QRY_OBJECT, "CatalogProduct");

        # get the total number of items
        $sQuery         = " SELECT FOUND_ROWS() 'count'";
        $aCount         = $oDb->query($sQuery, QRY_UNIQUE_ARRAY);
        $iCountProducts = $aCount['count'];

        # return the objects
        return $aProducts;
    }

    /**
     * get categories for CatalogProduct by filter
     *
     * @param int   $iCatalogProductId
     * @param array $aFilter
     * @param int   $iLimit
     *
     * @return array CatalogProductCategory
     */
    public static function getCategoriesByFilter($iCatalogProductId, array $aFilter = [], $iLimit = null)
    {
        $sFrom  = '';
        $sWhere = '';
        if (empty($aFilter['showAll'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cpc1`.`online` = 1';
            // check categories backwards recursively
            for ($iC = 2; $iC <= CatalogProductCategory::MAX_LEVELS; $iC++) {
                $sFrom  .= 'LEFT JOIN `catalog_product_categories` AS `cpc' . $iC . '` ON `cpc' . ($iC) . '`.`catalogProductCategoryId` = `cpc' . ($iC - 1) . '`.`parentCatalogProductCategoryId`';
                $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`cpc' . $iC . '`.`online` = 1 OR `cpc' . ($iC - 1) . '`.`parentCatalogProductCategoryId` IS NULL)';
            }
        }

        // If I have set actualCategory and the next level, I get all the subcategories
        if (!empty($aFilter['level'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cpc1`.`level` = ' . db_int($aFilter['level']);
        }

        if (!empty($aFilter['parentCatalogProductCategoryId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cpc1`.`parentCatalogProductCategoryId` = ' . db_int($aFilter['parentCatalogProductCategoryId']);
        }

        $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cpcpc`.`catalogProductId` = ' . db_int($iCatalogProductId);
        $sQuery = ' SELECT
                        `cpc1`.*
                    FROM
                        `catalog_product_categories` AS `cpc1`
                    JOIN
                        `catalog_products_catalog_product_categories` AS `cpcpc` ON `cpcpc`.`catalogProductCategoryId` = `cpc1`.`catalogProductCategoryId`
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    ORDER BY
                        `cpc1`.`order` ASC
                    ' . ($iLimit ? 'LIMIT ' . db_int($iLimit) : '') . '
                    ;';
        $oDb    = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, 'CatalogProductCategory');
    }

    /**
     * save connection between a CatalogProduct and an Image
     *
     * @param int $iProductId
     * @param int $iImageId
     */
    public static function saveProductImageRelation($iProductId, $iImageId)
    {
        $sQuery = ' INSERT IGNORE INTO
                        `catalog_products_images`
                    (
                        `catalogProductId`,
                        `imageId`
                    )
                    VALUES
                    (
                        ' . db_int($iProductId) . ',
                        ' . db_int($iImageId) . '
                    )
                    ;';
        $oDb    = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
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
    public static function getImagesByFilter($iCatalogProductId, array $aFilter = [], $iLimit = null)
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
                        `catalog_products_images` AS `cpi` USING (`imageId`)
                    WHERE
                        `cpi`.`catalogProductId` = ' . db_int($iCatalogProductId) . '
                    ' . $sWhere . '
                    ORDER BY
                        `i`.`order` ASC, `i`.`imageId` ASC
                    ' . ($iLimit ? 'LIMIT ' . db_int($iLimit) : '') . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, 'Image');
    }

    /**
     * get number of color size relations for catalogProduct by filter
     *
     * @param int   $iCatalogProductId
     * @param array $aFilter
     *
     * @return CatalogProductSizeColorRelation[]
     */
    public static function getNOSizeColorRelationsByFilter($iCatalogProductId, array $aFilter = [])
    {
        $sWhere = '';
        if (empty($aFilter['showAll'])) {
            $sWhere .= ' AND (`cpcsr`.`stock` > 0 OR `cpcsr`.`stock` IS NULL)';
        }

        // check for products with extra price
        if (!empty($aFilter['withExtraPrice'])) {
            $sWhere .= ' AND `cpcsr`.`extraPrice` > 0';
        }

        $sQuery = ' SELECT
                        COUNT(*) AS `number`
                    FROM
                        `catalog_product_size_color_relations` AS `cpcsr`
                    WHERE
                        `cpcsr`.`catalogProductId` = ' . db_int($iCatalogProductId) . '
                    ' . $sWhere . '
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT)->number;
    }

    /**
     * generate the filter for products by catalogProductTypeId
     *
     * @param array allowed ProductType objects
     * @param array  $aFilter    filter used to filter products
     * @param string $sActionUrl the page to go to
     *
     * @return string
     */
    public static function generateProductFilterHtml(array $aFilter = [], array $aSettings = [], $sActionUrl = null)
    {
        $aProductTypes = self::getProductTypesForProductFilterByProductFilter($aFilter);
        if (count($aProductTypes) == 1) {
            // overwrite catalogProductTypeId
            $aFilter['catalogProductTypeId'] = $aProductTypes[0]['catalogProductTypeId'];
        }

        if (empty($aDefaultSettings)) {
            $aDefaultSettings['showName']          = true;
            $aDefaultSettings['showPrice']         = true;
            $aDefaultSettings['showBrands']        = true;
            $aDefaultSettings['showProductTypes']  = empty($aFilter['catalogProductTypeId']); // productType not selected? show producttypes if needed
            $aDefaultSettings['showGenders']       = !empty($aFilter['catalogProductTypeId']); // productType selected? show genders
            $aDefaultSettings['showSizes']         = !empty($aFilter['catalogProductTypeId']); // productType selected? show sizes
            $aDefaultSettings['showColors']        = !empty($aFilter['catalogProductTypeId']); // productType selected? show colors
            $aDefaultSettings['showSpecificProps'] = !empty($aFilter['catalogProductTypeId']); // productType selected? show specific props filter
            $aDefaultSettings['alwaysShowOptions'] = !empty($aFilter['catalogProductTypeId']); // productType selected? show all options despite of the fact that there is no productType or Category set
        }

        $aSettings = array_merge($aDefaultSettings, $aSettings);
        $iMaxChars = Settings::get('catalogFilterMaxChars'); // number of character to use in the filter for the values
        # create form
        $sHtml = '<form action="' . getBaseUrl() . (empty($sActionUrl) ? getCurrentUrlPath() : $sActionUrl) . '" method="GET">';

        # put the catalogProductTypeId as a hidden input field
        if (!empty($aFilter['catalogProductTypeId']) && is_numeric($aFilter['catalogProductTypeId'])) {
            $sHtml .= '<input type="hidden" name="productFilter[catalogProductTypeId]" value="' . $aFilter['catalogProductTypeId'] . '" />';
        }

        if ($aSettings['showName']) {
            # create filter for name (q)
            $sHtml .= '<div class="field">';
            $sHtml .= '<div class="control">';
            $sHtml .= '<label class="label is-small property" for="q">' . _e(SiteTranslations::get('site_name')) . '</label>';
            $sHtml .= '<input class="input is-small" placeholder="' . _e(SiteTranslations::get('site_search_by_name')) . '" id="q" type="text" name="productFilter[q]" value="' . (empty($aFilter['q']) ? '' : _e($aFilter['q'])) . '" />';
            $sHtml .= '</div>';
            $sHtml .= '</div>';
        }

        if ($aSettings['showPrice']) {
            # crate filter for min/max. price (with high tax percentage)
            $sHtml .= '<div class="field is-horizontal">';
            $sHtml .= '<div class="control min-price">';
            $sHtml .= '<label class="label is-small property" for="minSalePrice">' . _e(SiteTranslations::get('site_min_price')) . '</label>';
            $sHtml .= '<input class="input is-small priceFloatOnly" id="minSalePrice" title="' . _e(
                    SiteTranslations::get('site_fill_in_just_numbers_min_max')
                ) . '"  type="text" name="productFilter[minSalePrice]" value="' . (isset($aFilter['minSalePrice']) && is_numeric($aFilter['minSalePrice']) ? _e($aFilter['minSalePrice']) : '') . '" />';
            $sHtml .= '</div>';
            $sHtml .= '<div class="control max-price">';
            $sHtml .= '<label class="label is-small property" for="maxSalePrice">' . _e(SiteTranslations::get('site_max_price')) . '</label>';
            $sHtml .= '<input class="input is-small priceFloatOnly" id="maxSalePrice" title="' . _e(
                    SiteTranslations::get('site_fill_in_just_numbers_min_max')
                ) . '"  type="text" name="productFilter[maxSalePrice]" value="' . (isset($aFilter['maxSalePrice']) && is_numeric($aFilter['maxSalePrice']) ? _e($aFilter['maxSalePrice']) : '') . '" />';
            $sHtml .= '</div>';
            $sHtml .= '</div>';
        }

        if ($aSettings['showBrands']) {
            # create brand filter field
            $sHtml .= '<div class="field">';
            $sHtml .= '<span class="label is-size-7">' . _e(SiteTranslations::get('site_brands')) . '</span>';
            foreach (self::getBrandsForProductFilterByProductFilter($aFilter) as $aBrand) {
                $sHtml .= '<div class="control">';
                $sHtml .= '<label for="brand-' . $aBrand['catalogBrandId'] . '" title="' . _e($aBrand['name']) . '">';
                $sHtml .= '<input class="checkbox" id="brand-' . $aBrand['catalogBrandId'] . '" type="checkbox" name="productFilter[catalogBrandIds][]" value="' . _e($aBrand['catalogBrandId']) . '" ' . (in_array(
                        $aBrand['catalogBrandId'],
                        (isset($aFilter['catalogBrandIds']) ? $aFilter['catalogBrandIds'] : [])
                    ) ? 'checked' : '') . ' /> ';
                $sHtml .= '<span class="is-size-7">' . firstXCharacters($aBrand['name'], $iMaxChars) . ' (' . $aBrand['occurrences'] . ')' . '</span>';
                $sHtml .= '</label>';
                $sHtml .= '</div>';
            }
            $sHtml .= '</div>';
        }

        if ($aSettings['showProductTypes']) {
            # only show productType select when more than 1 type is present in the filter (only the product types present in the current category are shown)
            if (count($aProductTypes) > 1) {
                $sHtml .= '<div class="field">';
                $sHtml .= '<label class="label is-small property" for="catalogProductTypeId">' . _e(SiteTranslations::get('site_type')) . '</label>';
                $sHtml .= '<div class="control">';
                $sHtml .= '<div class="select is-small">';
                $sHtml .= '<select id="catalogProductTypeId" name="productFilter[catalogProductTypeId]">';
                $sHtml .= '<option value="">' . _e(SiteTranslations::get('site_no_preference')) . '</option>';
                foreach ($aProductTypes as $aProductType) {
                    $sHtml .= '<option value="' . $aProductType['catalogProductTypeId'] . '" ' . (isset($aFilter['catalogProductTypeId']) && $aProductType['catalogProductTypeId'] == $aFilter['catalogProductTypeId'] ? 'selected' : '') . '>' . _e(
                            $aProductType['title']
                        ) . ' (' . $aProductType['occurrences'] . ')</option>';
                }
                $sHtml .= '</select>';
                $sHtml .= '</div>';
                $sHtml .= '</div>';
                $sHtml .= '</div>';
            }
        }

        if ($aSettings['alwaysShowOptions'] || (!empty($aFilter['catalogProductTypeId']) && is_numeric($aFilter['catalogProductTypeId']))) {

            // not with all options so only one product type is set
            if (!$aSettings['alwaysShowOptions']) {
                $oCatalogProductType = CatalogProductTypeManager::getProductTypeById($aFilter['catalogProductTypeId']);
            }
            # create gender filter field if product type needs it
            if (($aSettings['alwaysShowOptions'] || $oCatalogProductType->withGenders) && $aSettings['showGenders']) {
                $sHtml .= '<div class="field">';
                $sHtml .= '<span class="label is-size-7 property">' . _e(SiteTranslations::get('site_gender')) . '</span>';
                foreach (self::getGendersForProductFilterByProductFilter($aFilter) as $sGender => $iOccurrences) {
                    $sHtml .= '<div class="control">';
                    $sHtml .= '<label for="gender-' . $sGender . '">';
                    $sHtml .= '<input class="checkbox" id="gender-' . $sGender . '" type="checkbox" name="productFilter[genders][]" value="' . _e($sGender) . '" ' . (in_array(
                            $sGender,
                            (isset($aFilter['genders']) ? $aFilter['genders'] : [])
                        ) ? 'checked' : '') . ' /> ';
                    $sHtml .= '<span class="is-size-7">' . _e(self::getLabelByGender($sGender)) . ' (' . $iOccurrences . ')' . '</span>';
                    $sHtml .= '</label>';
                    $sHtml .= '</div>';
                }
                $sHtml .= '</div>';
            }

            # create sizes filter field
            if (($aSettings['alwaysShowOptions'] || $oCatalogProductType->withSizes) && $aSettings['showSizes']) {
                $sHtml .= '<div class="field">';
                $sHtml .= '<span class="label is-size-7 property">' . _e(SiteTranslations::get('site_sizes')) . '</span>';
                foreach (self::getSizesForProductFilterByProductFilter($aFilter) as $aSize) {
                    $sHtml .= '<div class="control">';
                    $sHtml .= '<label for="size-' . $aSize['catalogProductSizeId'] . '" title="' . _e($aSize['name']) . '">';
                    $sHtml .= '<input class="checkbox" id="size-' . $aSize['catalogProductSizeId'] . '" type="checkbox" name="productFilter[catalogProductSizeIds][]" value="' . _e($aSize['catalogProductSizeId']) . '" ' . (in_array(
                            $aSize['catalogProductSizeId'],
                            (isset($aFilter['catalogProductSizeIds']) ? $aFilter['catalogProductSizeIds'] : [])
                        ) ? 'checked' : '') . ' /> ';
                    $sHtml .= '<span class="is-size-7">' . firstXCharacters($aSize['name'], $iMaxChars) . ' (' . $aSize['occurrences'] . ')' . '</span>';
                    $sHtml .= '</label>';
                    $sHtml .= '</div>';
                }
                $sHtml .= '</div>';
            }

            # create colors filter field
            if (($aSettings['alwaysShowOptions'] || $oCatalogProductType->withColors) && $aSettings['showColors']) {
                $sHtml .= '<div class="field">';
                $sHtml .= '<span class="label is-size-7 property">' . _e(SiteTranslations::get('site_colors')) . '</span>';
                foreach (self::getColorsForProductFilterByProductFilter($aFilter) as $aColor) {
                    $sHtml .= '<div class="control">';
                    $sHtml .= '<label for="color-' . $aColor['catalogProductColorId'] . '" title="' . _e($aColor['name']) . '">';
                    $sHtml .= '<input class="checkbox" id="color-' . $aColor['catalogProductColorId'] . '" type="checkbox" name="productFilter[catalogProductColorIds][]" value="' . _e(
                            $aColor['catalogProductColorId']
                        ) . '" ' . (in_array($aColor['catalogProductColorId'], (isset($aFilter['catalogProductColorIds']) ? $aFilter['catalogProductColorIds'] : [])) ? 'checked' : '') . ' /> ';
                    $sHtml .= '<span class="is-size-7">' . firstXCharacters($aColor['name'], $iMaxChars) . ' (' . $aColor['occurrences'] . ')' . '</span>';
                    $sHtml .= '</label>';
                    $sHtml .= '</div>';
                }
                $sHtml .= '</div>';
            }

            if ($aSettings['showSpecificProps']) {
                # go through each CatalogProductPropertyType with a filterType
                foreach (self::getProductPropertyTypesForProductFilterByProductFilter($aFilter) as $aCatalogProductPropertyType) {
                    $aValues = self::getProductPropertyValuesForProductFilterByProductFilter($aCatalogProductPropertyType['catalogProductPropertyTypeId'], $aFilter);

                    # check if there are any values
                    if (empty($aValues)) {
                        continue;
                    }

                    # check which values are selected (if there are any)
                    $aSelectedValues = [];
                    if (!empty($aFilter['aProductPropertyValues'][$aCatalogProductPropertyType['catalogProductPropertyTypeId']]) && is_array(
                            $aFilter['aProductPropertyValues'][$aCatalogProductPropertyType['catalogProductPropertyTypeId']]
                        )) {
                        foreach ($aFilter['aProductPropertyValues'][$aCatalogProductPropertyType['catalogProductPropertyTypeId']] AS $sSelectedValue) {
                            $aSelectedValues[$sSelectedValue] = $sSelectedValue;
                        }
                    }

                    # define filter as a textfield if the filterType is 'text'
                    if ($aCatalogProductPropertyType['filterType'] == 'text') {
                        $sHtml .= '<div class="field">';
                        $sHtml .= '<div class="control">';
                        $sHtml .= '<label class="label is-small property" for="prop-' . $aCatalogProductPropertyType['catalogProductPropertyTypeId'] . '">' . _e($aCatalogProductPropertyType['title']) . '</label>';
                        $sHtml .= '<input class="input is-small" id="prop-' . $aCatalogProductPropertyType['catalogProductPropertyTypeId'] . '" type="text" name="productFilter[aProductPropertyValues][' . $aCatalogProductPropertyType['catalogProductPropertyTypeId'] . '][like]" value="' . (empty($aFilter['aProductPropertyValues'][$aCatalogProductPropertyType['catalogProductPropertyTypeId']]['like']) ? '' : _e(
                                $aFilter['aProductPropertyValues'][$aCatalogProductPropertyType['catalogProductPropertyTypeId']]['like']
                            )) . '" />';
                        $sHtml .= '</div>';
                        $sHtml .= '</div>';
                    } # define filter as a checkboxes if the filterType is 'checkbox'
                    elseif ($aCatalogProductPropertyType['filterType'] == 'checkbox') {
                        $sHtml .= '<div class="field">';
                        $sHtml .= '<span class="label is-size-7 property">' . _e($aCatalogProductPropertyType['title']) . '</span>';
                        foreach ($aValues AS $aValue) {
                            $sHtml .= '<div class="control">';
                            $sHtml .= '<label for="prop-' . $aCatalogProductPropertyType['catalogProductPropertyTypeId'] . '-' . _e($aValue['value']) . '" title="' . _e($aValue['value']) . '">';
                            $sHtml .= '<input class="checkbox" id="prop-' . $aCatalogProductPropertyType['catalogProductPropertyTypeId'] . '-' . _e(
                                    $aValue['value']
                                ) . '" type="checkbox" name="productFilter[aProductPropertyValues][' . $aCatalogProductPropertyType['catalogProductPropertyTypeId'] . '][]" value="' . _e($aValue['value']) . '" ' . (in_array(
                                    $aValue['value'],
                                    $aSelectedValues
                                ) ? 'checked' : '') . ' /> ';
                            $sHtml .= '<span class="is-size-7 ">' . firstXCharacters($aValue['value'], $iMaxChars) . ' (' . $aValue['occurrences'] . ')' . '</span>';
                            $sHtml .= '</label>';
                            $sHtml .= '</div>';
                        }
                        $sHtml .= '</div>';
                    } # define filter as a select field if the filterType is 'select'
                    elseif ($aCatalogProductPropertyType['filterType'] == 'select') {
                        $sHtml .= '<div class="field">';
                        $sHtml .= '<label class="label is-small property" for="prop-' . $aCatalogProductPropertyType['catalogProductPropertyTypeId'] . '">' . _e($aCatalogProductPropertyType['title']) . '</label>';
                        $sHtml .= '<div class="control">';
                        $sHtml .= '<div class="select is-small">';
                        $sHtml .= '<select type="text" name="productFilter[aProductPropertyValues][' . $aCatalogProductPropertyType['catalogProductPropertyTypeId'] . '][]" value="">';
                        $sHtml .= '<option value="">' . _e(SiteTranslations::get('site_no_preference')) . '</option>';
                        foreach ($aValues AS $aValue) {
                            $sHtml .= '<option value="' . _e($aValue['value']) . '" ' . (in_array($aValue['value'], $aSelectedValues) ? 'selected' : '') . '>' . _e($aValue['value']) . ' (' . $aValue['occurrences'] . ')</option>';
                        }
                        $sHtml .= '</select>';
                        $sHtml .= '</div>';
                        $sHtml .= '</div>';
                        $sHtml .= '</div>';
                    } # define filter as a min. and max. fields if the filterType is 'min-max'
                    elseif ($aCatalogProductPropertyType['filterType'] == 'min-max') {
                        $sHtml .= '<div class="field is-horizontal">';
                        $sHtml .= '<div class="control min-price">';
                        $sHtml .= '<label class="label is-small property" for="prop-' . $aCatalogProductPropertyType['catalogProductPropertyTypeId'] . '-min">Min. ' . _e($aCatalogProductPropertyType['title']) . '</label>';
                        $sHtml .= '<input class="input is-small priceFloatOnly" id="prop-' . $aCatalogProductPropertyType['catalogProductPropertyTypeId'] . '-min" title="Vul bij min/max alleen getallen in" type="text" name="productFilter[aProductPropertyValues][' . $aCatalogProductPropertyType['catalogProductPropertyTypeId'] . '][min]" value="' . (empty($aFilter['aProductPropertyValues'][$aCatalogProductPropertyType['catalogProductPropertyTypeId']]['min']) ? '' : _e(
                                $aFilter['aProductPropertyValues'][$aCatalogProductPropertyType['catalogProductPropertyTypeId']]['min']
                            )) . '" />';
                        $sHtml .= '</div>';
                        $sHtml .= '<div class="control max-price">';
                        $sHtml .= '<label class="label is-small property" for="prop-' . $aCatalogProductPropertyType['catalogProductPropertyTypeId'] . '-max">Max. ' . _e($aCatalogProductPropertyType['title']) . '</label>';
                        $sHtml .= '<input class="input is-small priceFloatOnly" id="prop-' . $aCatalogProductPropertyType['catalogProductPropertyTypeId'] . '-max" title="Vul bij min/max alleen getallen in" type="text" name="productFilter[aProductPropertyValues][' . $aCatalogProductPropertyType['catalogProductPropertyTypeId'] . '][max]" value="' . (empty($aFilter['aProductPropertyValues'][$aCatalogProductPropertyType['catalogProductPropertyTypeId']]['max']) ? '' : _e(
                                $aFilter['aProductPropertyValues'][$aCatalogProductPropertyType['catalogProductPropertyTypeId']]['max']
                            )) . '" />';
                        $sHtml .= '</div>';
                        $sHtml .= '</div>';
                    }
                }
            }
        }

        $sHtml .= '<div class="field is-grouped">';
        $sHtml .= '<div class="control">';
        $sHtml .= '<input class="button is-small is-primary" type="submit" value="' . _e(SiteTranslations::get('site_filter')) . '" />';
        $sHtml .= '</div>';
        $sHtml .= '<div class="control">';
        $sHtml .= '<input class="button is-small"  type="submit" name="resetProductFilter" value="' . _e(SiteTranslations::get('site_reset')) . '" />';
        $sHtml .= '</div>';
        $sHtml .= '</div>';

        $sHtml .= '</form>';

        return $sHtml;
    }

    /**
     * return products filtered by a few options (!! ALSO APPLY CHANGES TO CatalogProductTypeManager::getProductTypesByProductFilter() !!)
     *
     * @param array $aFilter    filter properties
     * @param int   $iLimit     limit number of records returned
     * @param int   $iStart     start from this record
     * @param int   $iFoundRows foundRows when there was no limit (default = false so doesn't check by default)
     * @param array $aOrderBy   array(database coloumn name => order) add order by columns and orders
     *
     * @return array CatalogProduct objects
     */
    public static function getProductsByFilter(array $aFilter = [], $iLimit = null, $iStart = 0, &$iFoundRows = false, $aOrderBy = ['`cpTRANS`.`name`' => 'ASC'])
    {

        $sWhere = '';
        $sFrom  = '';

        #ONLY USE FUNCTION BELOW TO ADD FILTER OPTIONS (OR DO EXACTLY KNOW WHAT YOU ARE DOING!)
        self::setProductFilterSQLPartsByFilter($sFrom, $sWhere, $aFilter);

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
                        `cp`.*,
                        `cpTRANS`.*
                    FROM
                        `catalog_products` AS `cp`
                    JOIN
                        `catalog_product_translations` AS `cpTRANS` ON `cpTRANS`.`catalogProductId` = `cp`.`catalogProductId`
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    GROUP BY
                        `cp`.`catalogProductId`
                    ' . $sOrderBy . '
                    ' . $sLimit . '
                    ;';

        $oDb       = DBConnections::get();
        $aProducts = $oDb->query($sQuery, QRY_OBJECT, "CatalogProduct");
        if ($iFoundRows !== false) {
            $iFoundRows = $oDb->query('SELECT FOUND_ROWS() AS `found_rows`;', QRY_UNIQUE_OBJECT)->found_rows;
        }

        return $aProducts;
    }

    /**
     * set reusable filter part of product filter
     *
     * @param type   $sFrom
     * @param type   $sWhere
     * @param type   $aFilter
     * @param string $sProductReference database table reference without ``
     */
    public static function setProductFilterSQLPartsByFilter(&$sFrom, &$sWhere, $aFilter, $sProductReference = 'cp')
    {

        // by default join these tables
        $sFrom = '  JOIN
                        `catalog_brands` AS `cb` ON `cb`.`catalogBrandId` = `' . $sProductReference . '`.`catalogBrandId`                        
                    JOIN
                        `catalog_products_catalog_product_categories` AS `cpcpc` ON `cpcpc`.`catalogProductId` = `' . $sProductReference . '`.`catalogProductId`                        
                    JOIN
                        `catalog_product_categories` AS `cpc1` ON `cpc1`.`catalogProductCategoryId` = `cpcpc`.`catalogProductCategoryId`                        
                    ';

        if (empty($aFilter['showAll'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`' . $sProductReference . '`.`online` = 1';
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cb`.`online` = 1';
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cpc1`.`online` = 1';
            // check categories backwards recursively
            for ($iC = 2; $iC <= CatalogProductCategory::MAX_LEVELS; $iC++) {
                $sFrom  .= ' LEFT JOIN `catalog_product_categories` AS `cpc' . $iC . '` ON `cpc' . ($iC) . '`.`catalogProductCategoryId` = `cpc' . ($iC - 1) . '`.`parentCatalogProductCategoryId`';
                $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`cpc' . $iC . '`.`online` = 1 OR `cpc' . ($iC - 1) . '`.`parentCatalogProductCategoryId` IS NULL)';
            }

            // check if product is in stock
            $sFrom  .= ' JOIN `catalog_product_size_color_relations` AS `cpscr` ON `cpscr`.`catalogProductId` = `' . $sProductReference . '`.`catalogProductId`';
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`cpscr`.`stock` > 0 OR `cpscr`.`stock` IS NULL)';
        }

        // search for color or size
        if (!empty($aFilter['catalogProductSizeIds']) || !empty($aFilter['catalogProductColorIds'])) {
            if (!empty($aFilter['showAll'])) {
                // do set if not yet set by showAll
                $sFrom .= ' JOIN `catalog_product_size_color_relations` AS `cpscr` ON `cpscr`.`catalogProductId` = `' . $sProductReference . '`.`catalogProductId`';
            }

            // search for size
            if (!empty($aFilter['catalogProductSizeIds'])) {
                $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cpscr`.`catalogProductSizeId` IN (';
                $sIn    = '';
                foreach ($aFilter['catalogProductSizeIds'] AS $iSizeId) {
                    $sIn .= ($sIn != '' ? ',' : '') . db_int($iSizeId);
                }
                $sWhere .= $sIn . ')';
            }

            // search for color
            if (!empty($aFilter['catalogProductColorIds'])) {
                $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cpscr`.`catalogProductColorId` IN (';
                $sIn    = '';
                foreach ($aFilter['catalogProductColorIds'] AS $iColorId) {
                    $sIn .= ($sIn != '' ? ',' : '') . db_int($iColorId);
                }
                $sWhere .= $sIn . ')';
            }
        }

        # search for q
        if (!empty($aFilter['q'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cpTRANS`.`name` LIKE ' . db_str('%' . $aFilter['q'] . '%');
        }
        # filter on language
        if (!empty($aFilter['languageId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cpTRANS`.`languageId` = ' . db_str($aFilter['languageId']);
        }

        if (!empty($aFilter['catalogProductId>'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`' . $sProductReference . '`.`catalogProductId` > ' . db_int($aFilter['catalogProductId>']);
        }
        if (!empty($aFilter['catalogProductId<'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`' . $sProductReference . '`.`catalogProductId` < ' . db_int($aFilter['catalogProductId<']);
        }
        if (!empty($aFilter['emptyGoogleCategory'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`' . $sProductReference . '`.`googleCategory` IS NULL ';
        }

        # search for brand
        if (!empty($aFilter['catalogBrandId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`' . $sProductReference . '`.`catalogBrandId` = ' . db_int($aFilter['catalogBrandId']);
        }

        # search for brands
        if (!empty($aFilter['catalogBrandIds'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`' . $sProductReference . '`.`catalogBrandId` IN (';
            $sIn    = '';
            foreach ($aFilter['catalogBrandIds'] AS $iBrandId) {
                $sIn .= ($sIn != '' ? ',' : '') . db_int($iBrandId);
            }
            $sWhere .= $sIn . ')';
        }

        # search for genders
        if (!empty($aFilter['genders'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`' . $sProductReference . '`.`gender` IN (';
            $sIn    = '';
            foreach ($aFilter['genders'] AS $sGender) {
                $sIn .= ($sIn != '' ? ',' : '') . db_str($sGender);
            }
            $sWhere .= $sIn . ')';
        }

        # get products that changed last hour
        if (isset($aFilter['lastHourOnly'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . 'IFNULL(`' . $sProductReference . '`.`modified`, `' . $sProductReference . '`.`created`) > DATE_ADD(NOW(), INTERVAL -1 HOUR)';
        }

        # get products for homepage
        if (!empty($aFilter['showOnHome'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`' . $sProductReference . '`.`showOnHome` = 1';
        }

        #search for min/max salePrice
        if (!empty($aFilter['minSalePrice']) || !empty($aFilter['maxSalePrice'])) {
            // I calculate the price to get filtered depending on the taxPercentage
            if (Settings::get('taxIncluded')) {
                $sIncrement = '1';
            } else {
                $sIncrement = '1 + ('
                    . 'CASE `' . $sProductReference . '`.taxPercentageId';
                foreach (TaxManager::getAllPercentageIds() AS $iPerecentageId) {
                    $sIncrement .= ' WHEN ' . $iPerecentageId . ' THEN ' . TaxManager::getPercentageById($iPerecentageId, false);
                }
                $sIncrement .= ' ELSE ' . TaxManager::getPercentageById(TaxManager::taxPercentageId_high, false)
                    . ' END'
                    . ' / 100)';
            }
        }

        if (!empty($aFilter['minSalePrice'])) {

            $sWhere .= ($sWhere != '' ? ' AND ' : '') . 'IFNULL(`' . $sProductReference . '`.reducedPrice,`' . $sProductReference . '`.`salePrice`) * (' . $sIncrement . ') >= ' . db_str($aFilter['minSalePrice']);
        }

        if (!empty($aFilter['maxSalePrice'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . 'IFNULL(`' . $sProductReference . '`.reducedPrice,`' . $sProductReference . '`.`salePrice`) * (' . $sIncrement . ') <= ' . db_str($aFilter['maxSalePrice']);
        }

        # search for productType
        if (!empty($aFilter['catalogProductTypeId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`' . $sProductReference . '`.`catalogProductTypeId` = ' . db_int($aFilter['catalogProductTypeId']);
        }

        # search for catalogProductCategory
        if (!empty($aFilter['catalogProductCategoryId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cpcpc`.`catalogProductCategoryId` = ' . db_int($aFilter['catalogProductCategoryId']);
        }

        # filter on specific product type specific props
        if (!empty($aFilter['aProductPropertyValues']) && is_array($aFilter['aProductPropertyValues'])) {
            // add all selected props
            $iC = 1;
            foreach ($aFilter['aProductPropertyValues'] AS $iProductPropertyTypeId => $aValues) {
                if (empty($aValues)) {
                    continue;
                }

                $sPropValues = '';
                foreach ($aValues AS $mKey => $mValue) {
                    if ($mValue === '') {
                        continue;
                    }
                    if ($mKey === 'min') {
                        $sPropValues .= ($sPropValues != '' ? ' AND ' : '') . '`cppv' . $iC . '`.`value` >= CAST(' . db_str($mValue) . ' AS DECIMAL(10,4))';
                    } elseif ($mKey === 'max') {
                        $sPropValues .= ($sPropValues != '' ? ' AND ' : '') . '`cppv' . $iC . '`.`value` <= CAST(' . db_str($mValue) . ' AS DECIMAL(10,4))';
                    } elseif ($mKey === 'like') {
                        $sPropValues .= ($sPropValues != '' ? ' OR ' : '') . '`cppv' . $iC . '`.`value` LIKE ' . db_str('%' . $mValue . '%');
                    } else {
                        $sPropValues .= ($sPropValues != '' ? ' OR ' : '') . '`cppv' . $iC . '`.`value` = ' . db_str($mValue);
                    }
                }

                if (!empty($sPropValues)) {
                    $sFrom  .= 'JOIN `catalog_product_property_values` AS `cppv' . $iC . '` ON `cppv' . $iC . '`.`catalogProductId` = `' . $sProductReference . '`.`catalogProductId`';
                    $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cppv' . $iC . '`.`catalogProductPropertyTypeId` = ' . db_int($iProductPropertyTypeId);
                    $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(' . $sPropValues . ')';
                }
                $iC++;
            }
        }
    }

    /**
     * get unique brands for a product filter
     *
     * @param array $aProductFilter
     */
    public static function getBrandsForProductFilterByProductFilter(array $aProductFilter = [])
    {
        // reset to force calculation for all brands, not only selected
        unset($aProductFilter['catalogBrandIds']);
        self::setProductFilterSQLPartsByFilter($sFrom1, $sWhere1, []);
        self::setProductFilterSQLPartsByFilter($sFrom2, $sWhere2, $aProductFilter);

        // only get brands for category
        if (isset($aProductFilter['catalogProductCategoryId'])) {
            $sWhere1 .= ($sWhere1 != '' ? ' AND ' : '') . '`cpcpc`.`catalogProductCategoryId` = ' . db_int($aProductFilter['catalogProductCategoryId']);
        }

        $sWhere1 .= ($sWhere1 != '' ? ' AND ' : '') . '`cbTRANSa`.`languageId` = ' . db_int(Locales::language());

        $sQuery = ' SELECT
                        `cbA`.*,
                        `cbTRANSa`.*,
                        IFNULL(`occurrencesByBrand`.`occurrences`, 0) AS `occurrences`
                    FROM
                        `catalog_brands` AS `cbA`
                    JOIN
                        `catalog_brand_translations` AS `cbTRANSa` ON `cbTRANSa`.`catalogBrandId` = `cbA`.`catalogBrandId`
                    LEFT JOIN(
                        SELECT
                            COUNT(DISTINCT(`cp`.`catalogProductId`)) AS `occurrences`,
                            `cb`.`catalogBrandId`
                        FROM
                            `catalog_products` AS `cp`
                        JOIN
                            `catalog_product_translations` AS `cpTRANS` ON `cpTRANS`.`catalogProductId` = `cp`.`catalogProductId`
                        ' . $sFrom2 . '
                        ' . ($sWhere2 != '' ? 'WHERE ' . $sWhere2 : '') . '
                        GROUP BY
                            `cb`.`catalogBrandId`
                    ) AS `occurrencesByBrand` ON `occurrencesByBrand`.`catalogBrandId` = `cbA`.`catalogBrandId`
                    JOIN
                        `catalog_products` AS `cp` ON `cp`.`catalogBrandId` = `cbA`.`catalogBrandId`
                    JOIN
                        `catalog_product_translations` AS `cpTRANS` ON `cpTRANS`.`catalogProductId` = `cp`.`catalogProductId`
                        ' . $sFrom1 . '
                        ' . ($sWhere1 != '' ? 'WHERE ' . $sWhere1 : '') . '
                    GROUP BY
                        `cbA`.`catalogBrandId`
                    ORDER BY
                        `cbA`.`order` ASC
                    ;';
        $oDb    = DBConnections::get();

        return $oDb->query($sQuery, QRY_ASSOC);
    }

    /**
     * get unique genders for a product filter
     *
     * @param array $aProductFilter
     */
    public static function getGendersForProductFilterByProductFilter(array $aProductFilter = [])
    {
        // reset to force calculation for all brands, not only selected
        unset($aProductFilter['genders']);
        self::setProductFilterSQLPartsByFilter($sFrom2, $sWhere2, $aProductFilter);

        $sQuery              = ' SELECT
                        COUNT(DISTINCT(`cp`.`catalogProductId`)) AS `occurrences`,
                        `cp`.`gender`
                    FROM
                        `catalog_products` AS `cp`
                    JOIN
                        `catalog_product_translations` AS `cpTRANS` ON `cpTRANS`.`catalogProductId` = `cp`.`catalogProductId`
                    ' . $sFrom2 . '
                    ' . ($sWhere2 != '' ? 'WHERE ' . $sWhere2 : '') . '
                    GROUP BY
                        `cp`.`gender`
                    ;';
        $oDb                 = DBConnections::get();
        $aOccurencesByGender = $oDb->query($sQuery, QRY_ASSOC);

        $aGenders = [];
        foreach (self::getAllGenders() As $sGender) {
            $iOccurences = 0;
            foreach ($aOccurencesByGender AS $aOccurenceByGender) {
                if (in_array($sGender, $aOccurenceByGender)) {
                    $iOccurences = $aOccurenceByGender['occurrences'];
                }
            }
            $aGenders[$sGender] = $iOccurences;
        }

        return $aGenders;
    }

    /**
     * get unique sizes for a product filter
     *
     * @param array $aProductFilter
     */
    public static function getSizesForProductFilterByProductFilter(array $aProductFilter = [])
    {
        // reset to force calculation for all brands, not only selected
        unset($aProductFilter['catalogProductSizeIds']);
        self::setProductFilterSQLPartsByFilter($sFrom1, $sWhere1, []);
        self::setProductFilterSQLPartsByFilter($sFrom2, $sWhere2, $aProductFilter);

        // do only get data from chosen category and product type
        $sWhere1 .= ($sWhere1 != '' ? ' AND ' : '') . '`cpsA`.`catalogProductSizeId` != ' . CatalogProductSize::sizeId_nosize;
        //$sWhere1 .= ($sWhere1 != '' ? ' AND ' : '') . '(`cpscrA`.`stock` > 0 OR `cpscrA`.`stock` IS NULL)';
        if (isset($aProductFilter['catalogProductCategoryId'])) {
            $sWhere1 .= ($sWhere1 != '' ? ' AND ' : '') . '`cpcpc`.`catalogProductCategoryId` = ' . db_int($aProductFilter['catalogProductCategoryId']);
        }
        if (isset($aProductFilter['catalogProductTypeId'])) {
            $sWhere1 .= ($sWhere1 != '' ? ' AND ' : '') . '`cp`.`catalogProductTypeId` = ' . db_int($aProductFilter['catalogProductTypeId']);
        }

        $sWhere1 .= ($sWhere1 != '' ? ' AND ' : '') . '`cpsTRANSa`.`languageId` = ' . db_int(Locales::language());

        $sQuery = ' SELECT
                        `cpsA`.*,
                        `cpsTRANSa`.*,
                        IFNULL(`occurrencesBySize`.`occurrences`, 0) AS `occurrences`
                    FROM
                        `catalog_product_sizes` AS `cpsA`
                    JOIN
                        `catalog_product_size_translations` AS `cpsTRANSa` ON `cpsTRANSa`.`catalogProductSizeId` = `cpsA`.`catalogProductSizeId`
                    JOIN
                        `catalog_product_size_color_relations` AS `cpscrA` ON `cpscrA`.`catalogProductSizeId` = `cpsA`.`catalogProductSizeId`
                    LEFT JOIN(
                        SELECT
                            COUNT(DISTINCT(`cp`.`catalogProductId`)) AS `occurrences`,
                            `cpscr`.`catalogProductSizeId`
                        FROM
                            `catalog_products` AS `cp`
                        JOIN
                            `catalog_product_translations` AS `cpTRANS` ON `cpTRANS`.`catalogProductId` = `cp`.`catalogProductId`
                        ' . $sFrom2 . '
                        ' . ($sWhere2 != '' ? 'WHERE ' . $sWhere2 : '') . '
                        GROUP BY
                            `cpscr`.`catalogProductSizeId`
                    ) AS `occurrencesBySize` ON `occurrencesBySize`.`catalogProductSizeId` = `cpsA`.`catalogProductSizeId`
                    JOIN
                        `catalog_products` AS `cp` ON `cp`.`catalogProductId` = `cpscrA`.`catalogProductId`
                    JOIN
                        `catalog_product_translations` AS `cpTRANS` ON `cpTRANS`.`catalogProductId` = `cp`.`catalogProductId`
                        ' . $sFrom1 . '
                        ' . ($sWhere1 != '' ? 'WHERE ' . $sWhere1 : '') . '
                    GROUP BY
                        `cpsA`.`catalogProductSizeId`
                    ORDER BY
                        `cpsA`.`order` ASC,
                        `cpsTRANSa`.`name` ASC
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_ASSOC);
    }

    /**
     * get unique colors for a product filter
     *
     * @param array $aProductFilter
     */
    public static function getColorsForProductFilterByProductFilter(array $aProductFilter = [])
    {
        // reset to force calculation for all brands, not only selected
        unset($aProductFilter['catalogProductColorIds']);
        self::setProductFilterSQLPartsByFilter($sFrom1, $sWhere1, []);
        self::setProductFilterSQLPartsByFilter($sFrom2, $sWhere2, $aProductFilter);

        // do only get data from chosen category and product type
        $sWhere1 .= ($sWhere1 != '' ? ' AND ' : '') . '`cpcA`.`catalogProductColorId` != ' . CatalogProductColor::colorId_nocolor;
        //$sWhere1 .= ($sWhere1 != '' ? ' AND ' : '') . '(`cpscrA`.`stock` > 0 OR `cpscrA`.`stock` IS NULL)';
        if (isset($aProductFilter['catalogProductCategoryId'])) {
            $sWhere1 .= ($sWhere1 != '' ? ' AND ' : '') . '`cpcpc`.`catalogProductCategoryId` = ' . db_int($aProductFilter['catalogProductCategoryId']);
        }
        if (isset($aProductFilter['catalogProductTypeId'])) {
            $sWhere1 .= ($sWhere1 != '' ? ' AND ' : '') . '`cp`.`catalogProductTypeId` = ' . db_int($aProductFilter['catalogProductTypeId']);
        }

        $sWhere1 .= ($sWhere1 != '' ? ' AND ' : '') . '`cpcTRANSa`.`languageId` = ' . db_int(Locales::language());

        $sQuery = ' SELECT
                        `cpcA`.*,
                        `cpcTRANSa`.*,
                        IFNULL(`occurrencesByColor`.`occurrences`, 0) AS `occurrences`
                    FROM
                        `catalog_product_colors` AS `cpcA`
                    JOIN
                        `catalog_product_color_translations` AS `cpcTRANSa` ON `cpcTRANSa`.`catalogProductColorId` = `cpcA`.`catalogProductColorId`
                    JOIN
                        `catalog_product_size_color_relations` AS `cpscrA` ON `cpscrA`.`catalogProductColorId` = `cpcA`.`catalogProductColorId`
                    LEFT JOIN(
                        SELECT
                            COUNT(DISTINCT(`cp`.`catalogProductId`)) AS `occurrences`,
                            `cpscr`.`catalogProductColorId`
                        FROM
                            `catalog_products` AS `cp`
                        JOIN
                            `catalog_product_translations` AS `cpTRANS` ON `cpTRANS`.`catalogProductId` = `cp`.`catalogProductId`
                        ' . $sFrom2 . '
                        ' . ($sWhere2 != '' ? 'WHERE ' . $sWhere2 : '') . '
                        GROUP BY
                            `cpscr`.`catalogProductColorId`
                    ) AS `occurrencesByColor` ON `occurrencesByColor`.`catalogProductColorId` = `cpcA`.`catalogProductColorId`
                    JOIN
                        `catalog_products` AS `cp` ON `cp`.`catalogProductId` = `cpscrA`.`catalogProductId`
                    JOIN
                        `catalog_product_translations` AS `cpTRANS` ON `cpTRANS`.`catalogProductId` = `cp`.`catalogProductId`
                        ' . $sFrom1 . '
                        ' . ($sWhere1 != '' ? 'WHERE ' . $sWhere1 : '') . '
                    GROUP BY
                        `cpcA`.`catalogProductColorId`
                    ORDER BY
                        `cpcA`.`order` ASC,
                        `cpcTRANSa`.`name` ASC
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_ASSOC);
    }

    /**
     * get unique productTypes for a product filter
     *
     * @param array $aProductFilter
     */
    public static function getProductTypesForProductFilterByProductFilter(array $aProductFilter = [])
    {
        // reset to force calculation for all productTypes, not only selected
        unset($aProductFilter['catalogProductTypeId']);
        // always do filter on category to not show options when multiple productTypes are present. (otherwise there will be a lot of filter options of all produtTypes #overkill)
        $aFilter = [];
        if (isset($aProductFilter['catalogProductCategoryId'])) {
            $aFilter['catalogProductCategoryId'] = $aProductFilter['catalogProductCategoryId'];
        }
        self::setProductFilterSQLPartsByFilter($sFrom1, $sWhere1, $aFilter);
        self::setProductFilterSQLPartsByFilter($sFrom2, $sWhere2, $aProductFilter);

        $sQuery = ' SELECT
                        `cptA`.*,
                        `cptTRANSa`.*,
                        `cpTRANS`.*,
                        IFNULL(`occurrencesByProductType`.`occurrences`, 0) AS `occurrences`
                    FROM
                        `catalog_product_types` AS `cptA`
                    JOIN
                        `catalog_product_type_translations` AS `cptTRANSa` ON `cptTRANSa`.`catalogProductTypeId` = `cptA`.`catalogProductTypeId`
                    LEFT JOIN(
                        SELECT
                            COUNT(DISTINCT(`cp`.`catalogProductId`)) AS `occurrences`,
                            `cp`.`catalogProductTypeId`
                        FROM
                            `catalog_products` AS `cp`
                        JOIN
                            `catalog_product_translations` AS `cpTRANS` ON `cpTRANS`.`catalogProductId` = `cp`.`catalogProductId`
                        ' . $sFrom2 . '
                        ' . ($sWhere2 != '' ? 'WHERE ' . $sWhere2 : '') . '
                        GROUP BY
                            `cp`.`catalogProductTypeId`
                    ) AS `occurrencesByProductType` ON `occurrencesByProductType`.`catalogProductTypeId` = `cptA`.`catalogProductTypeId`
                    JOIN
                        `catalog_products` AS `cp` ON `cp`.`catalogProductTypeId` = `cptA`.`catalogProductTypeId`
                    JOIN
                        `catalog_product_translations` AS `cpTRANS` ON `cpTRANS`.`catalogProductId` = `cp`.`catalogProductId`
                        ' . $sFrom1 . '
                        ' . ($sWhere1 != '' ? 'WHERE ' . $sWhere1 : '') . '
                    GROUP BY
                        `cptA`.`catalogProductTypeId`
                    ORDER BY
                        `cptA`.`order` ASC
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_ASSOC);
    }

    /**
     * get unique product property types for a product filter
     *
     * @param array $aProductFilter
     */
    public static function getProductPropertyTypesForProductFilterByProductFilter(array $aProductFilter = [])
    {
        self::setProductFilterSQLPartsByFilter($sFrom, $sWhere, []);
        if (isset($aProductFilter['catalogProductCategoryId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cpcpc`.`catalogProductCategoryId` = ' . db_int($aProductFilter['catalogProductCategoryId']);
        }
        if (isset($aProductFilter['catalogProductTypeId'])) {
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cp`.`catalogProductTypeId` = ' . db_int($aProductFilter['catalogProductTypeId']);
        }

        $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cppt`.`filterType` IS NOT NULL';
        $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cppv`.`languageId` = ' . db_int(Locales::language());
        $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cpptTRANS`.`languageId` = ' . db_int(Locales::language());

        $sQuery = ' SELECT
                        `cppt`.*,
                        `cpptTRANS`.*
                    FROM
                        `catalog_product_property_types` AS `cppt`
                    JOIN
                        `catalog_product_property_type_translations` AS `cpptTRANS` ON `cpptTRANS`.`catalogProductPropertyTypeId` = `cppt`.`catalogProductPropertyTypeId`
                    JOIN
                        `catalog_product_property_values` AS `cppv` ON `cppv`.`catalogProductPropertyTypeId` = `cppt`.`catalogProductPropertyTypeId`
                    JOIN
                        `catalog_products` AS `cp` ON `cp`.`catalogProductId` = `cppv`.`catalogProductId`
                    JOIN
                        `catalog_product_translations` AS `cpTRANS` ON `cpTRANS`.`catalogProductId` = `cp`.`catalogProductId`
                        ' . $sFrom . '
                        ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    GROUP BY
                        `cppt`.`catalogProductPropertyTypeId`
                    ORDER BY
                        `cppt`.`order` ASC,
                        `cpptTRANS`.`title` ASC
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_ASSOC);
    }

    /**
     * get unique product property values for a product filter
     *
     * @param int   $iCatalogProductPropertyTypeId
     * @param array $aProductFilter
     */
    public static function getProductPropertyValuesForProductFilterByProductFilter($iCatalogProductPropertyTypeId, array $aProductFilter = [])
    {
        // reset to force calculation for all types, not only selected
        if (!empty($aProductFilter['aProductPropertyValues'][$iCatalogProductPropertyTypeId])) {
            unset($aProductFilter['aProductPropertyValues'][$iCatalogProductPropertyTypeId]);
        }

        self::setProductFilterSQLPartsByFilter($sFrom1, $sWhere1, []);
        self::setProductFilterSQLPartsByFilter($sFrom2, $sWhere2, $aProductFilter);

        $sWhere1 .= ($sWhere1 != '' ? ' AND ' : '') . '`cppvA`.`catalogProductPropertyTypeId` = ' . db_int($iCatalogProductPropertyTypeId);
        $sWhere1 .= ($sWhere1 != '' ? ' AND ' : '') . '`cpptpvTRANSa`.`languageId` = ' . db_int(Locales::language()) . ' AND  `cppvA`.`languageId` = ' . db_int(Locales::language());

        $sQuery = ' SELECT
                        `cppvA`.*,
                        `cpptpvTRANSa`.*,
                        `cpTRANS`.*,
                        IFNULL(`occurrencesByProductPropertyValue`.`occurrences`, 0) AS `occurrences`
                    FROM
                        `catalog_product_property_values` AS `cppvA`
                    LEFT JOIN
                        `catalog_product_property_type_possible_values` AS `cpptpvA` ON `cpptpvA`.`catalogProductPropertyTypeId` = `cppvA`.`catalogProductPropertyTypeId`
                    LEFT JOIN
                        `catalog_product_property_type_possible_value_translations` AS `cpptpvTRANSa` ON (`cpptpvTRANSa`.`catalogProductPropertyTypePossibleValueId` = `cpptpvA`.`catalogProductPropertyTypePossibleValueId` AND `cpptpvTRANSa`.`value` = `cppvA`.`value`)
                    LEFT JOIN(
                        SELECT
                            COUNT(DISTINCT(`cp`.`catalogProductId`)) AS `occurrences`,
                            `cppv`.`catalogProductPropertyTypeId`,
                            `cppv`.`value`
                        FROM
                            `catalog_products` AS `cp`
                        JOIN
                            `catalog_product_translations` AS `cpTRANS` ON `cpTRANS`.`catalogProductId` = `cp`.`catalogProductId`
                        JOIN
                            `catalog_product_property_values` AS `cppv` ON `cppv`.`catalogProductId` = `cp`.`catalogProductId`
                        ' . $sFrom2 . '
                        ' . ($sWhere2 != '' ? 'WHERE ' . $sWhere2 : '') . '
                        GROUP BY
                            `cppv`.`catalogProductPropertyTypeId`, `cppv`.`value`
                    ) AS `occurrencesByProductPropertyValue` ON (`occurrencesByProductPropertyValue`.`catalogProductPropertyTypeId` = `cppvA`.`catalogProductPropertyTypeId` AND `occurrencesByProductPropertyValue`.`value` = `cppvA`.`value`)
                    JOIN
                        `catalog_products` AS `cp` ON `cp`.`catalogProductId` = `cppvA`.`catalogProductId`
                    JOIN
                        `catalog_product_translations` AS `cpTRANS` ON `cpTRANS`.`catalogProductId` = `cp`.`catalogProductId`
                        ' . $sFrom1 . '
                        ' . ($sWhere1 != '' ? 'WHERE ' . $sWhere1 : '') . '
                    GROUP BY
                        `cppvA`.`catalogProductPropertyTypeId`, `cppvA`.`value`
                    ORDER BY
                        IFNULL(`cpptpvA`.`order`,1) ASC,
                        `cppvA`.`value` ASC
                    ;';
        $oDb    = DBConnections::get();

        return $oDb->query($sQuery, QRY_ASSOC);
    }

    /**
     * get colors for CatalogProduct by filter
     *
     * @param int   $iCatalogProductId
     * @param array $aFilter
     * @param int   $iLimit
     *
     * @return array CatalogProductColor
     */
    public static function getColorsByFilter($iCatalogProductId, array $aFilter = [], $iLimit = null)
    {
        $sFrom  = '';
        $sWhere = '';
        if (empty($aFilter['showAll'])) {
            // check if product is in stock
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`cpscr`.`stock` > 0 OR `cpscr`.`stock` IS NULL)';
        }

        $sFrom  .= 'JOIN `catalog_product_size_color_relations` AS `cpscr` ON `cpscr`.`catalogProductColorId` = `cpc`.`catalogProductColorId`';
        $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cpscr`.`catalogProductId` = ' . db_int($iCatalogProductId);
        $sQuery = ' SELECT
                        `cpc`.*
                    FROM
                        `catalog_product_colors` AS `cpc`
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    GROUP BY
                        `cpc`.`catalogProductColorId`
                    ORDER BY
                        `cpc`.`order` ASC
                    ' . ($iLimit ? 'LIMIT ' . db_int($iLimit) : '') . '
                    ;';
        $oDb    = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, 'CatalogProductColor');
    }

    /**
     * get sizes for CatalogProduct by filter
     *
     * @param int   $iCatalogProductId
     * @param array $aFilter
     * @param int   $iLimit
     *
     * @return array CatalogProductSize
     */
    public static function getSizesByFilter($iCatalogProductId, array $aFilter = [], $iLimit = null)
    {
        $sFrom  = '';
        $sWhere = '';
        if (empty($aFilter['showAll'])) {
            // check if product is in stock
            $sWhere .= ($sWhere != '' ? ' AND ' : '') . '(`cpscr`.`stock` > 0 OR `cpscr`.`stock` IS NULL)';
        }

        $sFrom  .= 'JOIN `catalog_product_size_color_relations` AS `cpscr` ON `cpscr`.`catalogProductSizeId` = `cps`.`catalogProductSizeId`';
        $sWhere .= ($sWhere != '' ? ' AND ' : '') . '`cpscr`.`catalogProductId` = ' . db_int($iCatalogProductId);
        $sQuery = ' SELECT
                        `cps`.*
                    FROM
                        `catalog_product_sizes` AS `cps`
                    ' . $sFrom . '
                    ' . ($sWhere != '' ? 'WHERE ' . $sWhere : '') . '
                    GROUP BY
                        `cps`.`catalogProductSizeId`
                    ORDER BY
                        `cps`.`order` ASC
                    ' . ($iLimit ? 'LIMIT ' . db_int($iLimit) : '') . '
                    ;';
        $oDb    = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, 'CatalogProductSize');
    }

    /**
     * get all genders
     *
     * @return array
     */
    public static function getAllGenders()
    {
        return [
            CatalogProduct::GENDER_MALE,
            CatalogProduct::GENDER_FEMALE,
            CatalogProduct::GENDER_UNISEX,
        ];
    }

    /*
     * get a label of agender
     * @return string
     */

    public static function getLabelByGender($sGender)
    {
        switch ($sGender) {
            case CatalogProduct::GENDER_MALE:
                return SiteTranslations::get('site_male');
                break;
            case CatalogProduct::GENDER_FEMALE:
                return SiteTranslations::get('site_female');
                break;
            case CatalogProduct::GENDER_UNISEX:
                return SiteTranslations::get('site_unisex');
                break;
        }
    }

    /*
     * get related products
     * @return array
     */

    public static function getRelatedProductsByFilter($iProductId, array $aFilter = [], $iLimit = null)
    {

        $sWhere = '';
        //$sOrderBy = 'ORDER BY `p`.description ';
        $sOrderBy = '';
        if (!empty($aFilter['notRelated'])) {
            $sJoin    = ' LEFT JOIN ';
            $sWhere   .= ($sWhere != '' ? ' AND ' : 'WHERE ') . '(`rp`.productId != ' . db_int($iProductId) . ' OR  `rp`.productId is null) AND `p`.catalogProductId != ' . db_int($iProductId);
            $sGroupBy = ' GROUP BY `p`.catalogProductId';
        } else {
            $sJoin    = ' JOIN ';
            $sWhere   .= ($sWhere != '' ? ' AND ' : 'WHERE ') . '`rp`.productId = ' . db_int($iProductId);
            $sGroupBy = '';
        }

        $sQuery = ' SELECT
                        `p`.*
                    FROM
                        (`catalog_products` AS `p`
                        ' . $sJoin . ' 
                            `catalog_product_related_products` AS `rp`
                        ON
                            `rp`.relatedProductId = `p`.catalogProductId)
                        ' . $sWhere . ' 
                        ' . $sGroupBy . '
                        ' . $sOrderBy . '
                    ;';
        $oDb    = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "CatalogProduct");
    }

    /*
     * add a releated product to a product
     * @return array
     */

    public static function saveRelatedProductById($iProductIdOrigen, $iProductIdRelated)
    {
        $sQuery = ' INSERT IGNORE INTO
                        `catalog_product_related_products`(
                            `productId`,
                            `relatedProductId`
                        )
                        VALUES(
                        ' . $iProductIdOrigen . ',
                        ' . $iProductIdRelated . '  
                        )
                    ;';
        $oDb    = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

    /*
     * delete a releated product from a product
     * @return array
     */

    public static function deleteRelatedProductById($iProductIdOrigen, $iProductIdRelated)
    {
        $sQuery = ' DELETE FROM
                        `catalog_product_related_products`     
                    WHERE
                        `productId` = ' . db_int($iProductIdOrigen) . '
                        AND `relatedProductId` = ' . db_int($iProductIdRelated) . '    
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);
    }

}

?>
