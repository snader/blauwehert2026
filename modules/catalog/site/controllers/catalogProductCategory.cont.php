<?php

/*
 * controller to handle the catalog's product pages
 */

# make pageLayout Object
$oPageLayout = new PageLayout();

# detail page
if (is_numeric(http_get('param1'))) {
    $oProductCategory = CatalogProductCategoryManager::getProductCategoryById(http_get('param1'));

    if (empty($oProductCategory) || !$oProductCategory->online) {
        showHttpError('404');
    }

    $oPageLayout->sWindowTitle     = $oProductCategory->getTranslations()
        ->getWindowTitle();
    $oPageLayout->sMetaDescription = $oProductCategory->getTranslations()
        ->getMetaDescription();
    $oPageLayout->sMetaKeywords    = $oProductCategory->getTranslations()
        ->getMetaKeywords();
    $oPageLayout->generateCustomCrumblePath(
        $oProductCategory->getTranslations()
            ->getCrumbles($oProductCategory)
    );
    $oPageLayout->sCanonical     = CLIENT_HTTP_URL . $oProductCategory->getTranslations()
            ->getUrlPath();
    $oPageLayout->sOGType        = 'product.group';
    $oPageLayout->sOGTitle       = $oProductCategory->getTranslations()
        ->getWindowTitle();
    $oPageLayout->sOGDescription = $oProductCategory->getTranslations()
        ->getMetaDescription();
    $oPageLayout->sOGUrl         = getCurrentUrl();

    // define default filter
    $aDefaultProductFilter = [];

    // set default filter in session if not exists
    if (!isset($_SESSION['aProductFiltersByCategory'][$oProductCategory->catalogProductCategoryId])) {
        $_SESSION['aProductFiltersByCategory'][$oProductCategory->catalogProductCategoryId] = $aDefaultProductFilter; // set filter in session
    }

    // unset product filter from session
    if (http_get('resetProductFilter')) {
        unset($_SESSION['aProductFiltersByCategory'][$oProductCategory->catalogProductCategoryId]);
        http_redirect(getBaseUrl() . getCurrentUrlPath());
    }

    if (http_get('productFilter')) {
        $aProductsFilter                                                                    = http_get('productFilter');
        $_SESSION['aProductFiltersByCategory'][$oProductCategory->catalogProductCategoryId] = $aProductsFilter; // set filter in session
    }

    // overwrite catalogProductCategoryId
    $_SESSION['aProductFiltersByCategory'][$oProductCategory->catalogProductCategoryId]['catalogProductCategoryId'] = $oProductCategory->catalogProductCategoryId;

    // get filter from session
    $aProductsFilter = $_SESSION['aProductFiltersByCategory'][$oProductCategory->catalogProductCategoryId];

    # handle perPage
    if (http_post('setPerPage')) {
        $_SESSION['productsPerPage'] = http_post('perPage');
    }

    $iPerPage  = http_session('productsPerPage', 6);
    $iCurrPage = http_get('page', 1);
    $iStart    = (($iCurrPage - 1) * $iPerPage);
    if (!is_numeric($iCurrPage) || $iCurrPage <= 0) {
        http_redirect(
            $oProductCategory->getTranslations()
                ->getUrlPath()
        );
    }

    $aProducts = CatalogProductManager::getProductsByFilter($aProductsFilter, $iPerPage, $iStart, $iFoundRows);

    $iPageCount = !empty($iPerPage) ? (ceil($iFoundRows / $iPerPage)) : 0;

    // Pagination
    // page is greater than max page count, redirect to main page
    if ($iPageCount > 0 && $iCurrPage > $iPageCount) {
        http_redirect(
            $oProductCategory->getTranslations()
                ->getUrlPath()
        );
    }

    // pagecount is greater than 1 and not last page
    if ($iPageCount > 1 && $iCurrPage < $iPageCount) {
        $oPageLayout->sRelNext = CLIENT_HTTP_URL . $oProductCategory->getTranslations()
                ->getUrlPath() . '?page=' . ($iCurrPage + 1);
    }

    // pagecount is greater than 1 and not first page
    if ($iPageCount > 1 && $iCurrPage > 1) {
        $oPageLayout->sRelPrev = CLIENT_HTTP_URL . $oProductCategory->getTranslations()
                ->getUrlPath();
        // is second page, previous is url without page
        if (($iCurrPage - 1) > 1) {
            $oPageLayout->sRelPrev .= '?page=' . ($iCurrPage - 1);
        }
    }

    // is first page, set canonical in case ?page=1 is set
    if ($iCurrPage == 1) {
        $oPageLayout->sCanonical = CLIENT_HTTP_URL . $oProductCategory->getTranslations()
                ->getUrlPath();
    }

    // set chosen categories for above filter
    $aChosenCategories   = [];
    $oChosenCategory     = $oProductCategory;
    $aChosenCategories[] = $oChosenCategory;
    while ($oChosenCategory = $oChosenCategory->getParent()) {
        $aChosenCategories[] = $oChosenCategory;
    }
    $aChosenCategories = array_reverse($aChosenCategories);

    $oPageLayout->sViewPath = getSiteView('catalogProductCategory_details', 'catalog');
} # overview
else {
    # get the objects
    $aProductCategories = CatalogProductCategoryManager::getProductCategoriesByFilter(['level' => 1]);

    # get page by urlPath (/categorieen)
    $oPage = PageManager::getPageByUrlPath(getCurrentUrlPath());

    if (empty($oPage) || !$oPage->online) {
        showHttpError('404');
    }

    $oPageLayout->sWindowTitle     = $oPage->getWindowTitle();
    $oPageLayout->sMetaDescription = $oPage->getMetaDescription();
    $oPageLayout->sMetaKeywords    = $oPage->getMetaKeywords();
    $oPageLayout->bIndexable       = $oPage->isIndexable();
    $oPageLayout->generateCustomCrumblePath($oPage->getCrumbles());
    $oPageLayout->sOGType        = 'website';
    $oPageLayout->sOGTitle       = $oPage->getWindowTitle();
    $oPageLayout->sOGDescription = $oPage->getMetaDescription();
    $oPageLayout->sOGUrl         = getCurrentUrl();

    $oPageLayout->sViewPath = getSiteView('catalogProductCategories_overview', 'catalog');
}

# Include the template
include_once getSiteView('layout');
?>