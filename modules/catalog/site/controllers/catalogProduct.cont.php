<?php

/*
 * controller to handle the catalog's product pages
 */

# make pageLayout Object
$oPageLayout = new PageLayout();

# detail page
if (is_numeric(http_get('param1'))) {
    $oProduct = CatalogProductManager::getProductById(http_get('param1'));

    if (empty($oProduct) || !$oProduct->online) {
        showHttpError('404');
    }

    $oPageLayout->sWindowTitle     = $oProduct->getTranslations()
        ->getWindowTitle($oProduct);
    $oPageLayout->sMetaDescription = $oProduct->getTranslations()
        ->getMetaDescription();
    $oPageLayout->sMetaKeywords    = $oProduct->getTranslations()
        ->getMetaKeywords();
    $oPageLayout->generateCustomCrumblePath(
        $oProduct->getTranslations()
            ->getCrumbles(http_get('catId'))
    );
    $oPageLayout->sCanonical     = CLIENT_HTTP_URL . $oProduct->getTranslations()
            ->getUrlPath();
    $oPageLayout->sOGType        = 'product';
    $oPageLayout->sOGTitle       = $oProduct->getTranslations()
        ->getWindowTitle($oProduct);
    $oPageLayout->sOGDescription = $oProduct->getTranslations()
        ->getMetaDescription();
    $oPageLayout->sOGUrl         = getCurrentUrl();

    $oPageLayout->sStructuredData = $oProduct->getStucturedData();

    if (($oImage = $oProduct->getImages('first-online')) && ($oImageFile = $oImage->getImageFileByReference('crop_small'))) {
        $oPageLayout->sOGImage       = CLIENT_HTTP_URL . $oImageFile->link;
        $oPageLayout->sOGImageWidth  = $oImageFile->getWidth();
        $oPageLayout->sOGImageHeight = $oImageFile->getHeight();
    }

    // with sizes and colors present
    if ($oProduct->getProductType()->withSizes && count($oProduct->getColorSizeRelations()) > 0) {
        $aSizes = $oProduct->getSizes();
    }

    // with colors and colors present
    if ($oProduct->getProductType()->withColors && count($oProduct->getColorSizeRelations()) > 0) {
        $aColors = $oProduct->getColors();
    }

    // get cheapest color size relation
    $oCheapestProductColorSizeRelation = $oProduct->getColorSizeRelations('cheapest');

    // no stock so any size/color combination show 404
    if (!$oCheapestProductColorSizeRelation) {
        showHttpError(404);
    }

    if (http_get('catId') && ($oCatalogProductCategory = CatalogProductCategoryManager::getProductCategoryById(http_get('catId')))) {
        $sOverviewLink = $oCatalogProductCategory->getTranslations()
            ->getUrlPath();
    } else {
        $sOverviewLink = '/' . http_get('controller');
    }

    $oPageLayout->sViewPath = getSiteView('catalogProduct_details', 'catalog');
} elseif (http_get('param1') == 'ajax-getProductDetails') {
    $oResObj                           = new stdClass();
    $oResObj->saleOriginalPriceInclVAT = null;
    $oResObj->salePriceInclVAT         = null;
    $oResObj->salePriceExclVAT         = null;
    $oResObj->aColors                  = []; // id, name, extraPrice, stock, disabled
    $oResObj->aSizes                   = []; // id, name, extraPrice, stock, disabled

    if (($oProduct = CatalogProductManager::getProductById(http_post('catalogProductId'))) && $oProduct->online) {

        $iCatalogProductId      = http_post('catalogProductId');
        $iCatalogProductColorId = http_post('catalogProductColorId');
        $iCatalogProductSizeId  = http_post('catalogProductSizeId');

        if (http_post('resetColors') == 'true') {
            $iCatalogProductColorId = null;
        }

        // first is cheapest
        $aFilter                          = [];
        $aFilter['catalogProductId']      = $iCatalogProductId;
        $aFilter['catalogProductColorId'] = $iCatalogProductColorId;
        $aFilter['catalogProductSizeId']  = $iCatalogProductSizeId;
        $aCheapestColorSizeRelations      = CatalogProductSizeColorRelationManager::getColorSizeRelationsByFilter($aFilter, 1, ['`cpcsr`.`extraPrice`' => 'ASC', '`cps`.`order`' => 'ASC', '`cps`.`order`' => 'ASC']);
        if (count($aCheapestColorSizeRelations) == 1) {
            $oCheapestColorSizeRelation        = $aCheapestColorSizeRelations[0];
            $oResObj->salePriceInclVAT         = decimal2valuta($oProduct->getSalePrice(true, $oCheapestColorSizeRelation->catalogProductColorId, $oCheapestColorSizeRelation->catalogProductSizeId));
            $oResObj->saleOriginalPriceInclVAT = decimal2valuta($oProduct->getSalePrice(true, $oCheapestColorSizeRelation->catalogProductColorId, $oCheapestColorSizeRelation->catalogProductSizeId, true, false));

            if ($oResObj->salePriceInclVAT == $oResObj->saleOriginalPriceInclVAT) {
                $oResObj->saleOriginalPriceInclVAT = null;
            }
            $oResObj->salePriceExclVAT = decimal2valuta($oProduct->getSalePrice(false, $oCheapestColorSizeRelation->catalogProductColorId, $oCheapestColorSizeRelation->catalogProductSizeId));
        } else {
            MailManager::sendMail(DEFAULT_ERROR_EMAIL, 'Trying to get unknown combination size/color', _d($_POST, 1, 1));
            die;
        }

        $aColorSizeRelationsByColor      = [];
        $aFilter                         = [];
        $aFilter['catalogProductId']     = $iCatalogProductId;
        $aFilter['catalogProductSizeId'] = $iCatalogProductSizeId;
        $aColorSizeRelationsForSize      = CatalogProductSizeColorRelationManager::getColorSizeRelationsByFilter($aFilter, null, ['`cpcsr`.`extraPrice`' => 'ASC', '`cps`.`order`' => 'ASC', '`cps`.`order`' => 'ASC']);
        foreach ($aColorSizeRelationsForSize AS $oColorSizeRelationForSize) {
            $aColorSizeRelationsByColor[$iCatalogProductSizeId . '-' . $oColorSizeRelationForSize->catalogProductColorId] = $oColorSizeRelationForSize;
        }

        $aColorSizeRelationsBySize        = [];
        $aFilter                          = [];
        $aFilter['catalogProductId']      = $iCatalogProductId;
        $aFilter['catalogProductColorId'] = $iCatalogProductColorId;
        $aColorSizeRelationsForColor      = CatalogProductSizeColorRelationManager::getColorSizeRelationsByFilter($aFilter, null, ['`cpcsr`.`extraPrice`' => 'ASC', '`cps`.`order`' => 'ASC', '`cps`.`order`' => 'ASC']);
        foreach ($aColorSizeRelationsForColor AS $oColorSizeRelationForColor) {
            $aColorSizeRelationsBySize[$iCatalogProductColorId . '-' . $oColorSizeRelationForColor->catalogProductSizeId] = $oColorSizeRelationForColor;
        }

        if ($oProduct->getProductType()->withColors) {
            foreach ($oProduct->getColors() AS $oColor) {
                $aColor                          = [];
                $aColor['catalogProductColorId'] = $oColor->catalogProductColorId;
                $aColor['name']                  = $oColor->getTranslations()->name;
                $aColor['selected']              = $oColor->catalogProductColorId == http_post('catalogProductColorId');
                $sStockKey                       = $iCatalogProductSizeId . '-' . $oColor->catalogProductColorId;
                if (key_exists($sStockKey, $aColorSizeRelationsByColor)) {
                    $aColor['stock']      = $aColorSizeRelationsByColor[$sStockKey]->stock;
                    $aColor['extraPrice'] = $aColorSizeRelationsByColor[$sStockKey]->extraPrice;
                    $aColor['disabled']   = false;
                } else {
                    $aColor['stock']      = null;
                    $aColor['extraPrice'] = null;
                    $aColor['disabled']   = true;
                    $aColor['selected']   = false; // overwrite selected when no stock available
                }

                $oResObj->aColors[] = $aColor;
            }
        }

        if ($oProduct->getProductType()->withSizes) {
            foreach ($oProduct->getSizes() AS $oSize) {
                $aSize                         = [];
                $aSize['catalogProductSizeId'] = $oSize->catalogProductSizeId;
                $aSize['name']                 = $oSize->getTranslations()->name;
                $aSize['selected']             = $oSize->catalogProductSizeId == $oCheapestColorSizeRelation->catalogProductSizeId;
                $sStockKey                     = $iCatalogProductColorId . '-' . $oSize->catalogProductSizeId;
                if (key_exists($sStockKey, $aColorSizeRelationsBySize)) {
                    $aSize['stock']      = $aColorSizeRelationsBySize[$sStockKey]->stock;
                    $aSize['extraPrice'] = $aColorSizeRelationsBySize[$sStockKey]->extraPrice;
                    $aSize['disabled']   = false;
                } else {
                    $aSize['stock']      = null;
                    $aSize['extraPrice'] = null;
                    $aSize['disabled']   = false;
                    $aSize['selected']   = false; // overwrite selected when no stock available
                }

                $oResObj->aSizes[] = $aSize;
            }
        }
    }
    die(json_encode($oResObj));
} # overview
else {

    # get page by urlPath (/producten)
    $oPage = PageManager::getPageByUrlPath(getCurrentUrlPath());

    if (empty($oPage) || !$oPage->online) {
        showHttpError('404');
    }

    $oPageLayout->sWindowTitle     = $oPage->getWindowTitle();
    $oPageLayout->sMetaDescription = $oPage->getMetaDescription();
    $oPageLayout->sMetaKeywords    = $oPage->getMetaKeywords();
    $oPageLayout->bIndexable       = $oPage->isIndexable();
    $oPageLayout->generateCustomCrumblePath($oPage->getCrumbles());
    $oPageLayout->sOGType        = 'product.group';
    $oPageLayout->sOGTitle       = $oPage->getWindowTitle();
    $oPageLayout->sOGDescription = $oPage->getMetaDescription();
    $oPageLayout->sOGUrl         = getCurrentUrl();
    if (($oImage = $oPage->getImages('first-online')) && ($oImageFile = $oImage->getImageFileByReference('crop_small'))) {
        $oPageLayout->sOGImage = CLIENT_HTTP_URL . $oImageFile->link;
    }

    // define default filter
    $aDefaultProductFilter = [];

    // set default filter in session if not exists
    if (!isset($_SESSION['aProductFilter'])) {
        $_SESSION['aProductFilter'] = $aDefaultProductFilter; // set filter in session
    }

    // unset product filter from session
    if (http_get('resetProductFilter')) {
        unset($_SESSION['aProductFilter']);
        http_redirect(getBaseUrl() . getCurrentUrlPath());
    }

    if (http_get('productFilter')) {
        $aProductsFilter            = http_get('productFilter');
        $_SESSION['aProductFilter'] = $aProductsFilter; // set filter in session
    }

    // get filter from session
    $aProductsFilter = $_SESSION['aProductFilter'];

    # handle perPage
    if (http_post('setPerPage')) {
        $_SESSION['productsPerPage'] = http_post('perPage');
    }

    $iPerPage  = http_session('productsPerPage', 5);
    $iCurrPage = http_get('page', 1);
    $iStart    = (($iCurrPage - 1) * $iPerPage);
    if (!is_numeric($iCurrPage) || $iCurrPage <= 0) {
        http_redirect($oPage->getBaseUrlPath());
    }

    # get the objects
    $aProducts          = CatalogProductManager::getProductsByFilter($aProductsFilter, $iPerPage, $iStart, $iFoundRows, ['created' => 'DESC']);
    $aProductCategories = CatalogProductCategoryManager::getProductCategoriesByFilter(['level' => 1]);

    $iPageCount = !empty($iPerPage) ? (ceil($iFoundRows / $iPerPage)) : 0;

    // Pagination
    // page is greater than max page count, redirect to main page
    if ($iPageCount > 0 && $iCurrPage > $iPageCount) {
        http_redirect($oPage->getBaseUrlPath());
    }

    // pagecount is greater than 1 and not last page
    if ($iPageCount > 1 && $iCurrPage < $iPageCount) {
        $oPageLayout->sRelNext = $oPage->getBaseUrlPath() . '?page=' . ($iCurrPage + 1);
    }

    // pagecount is greater than 1 and not first page
    if ($iPageCount > 1 && $iCurrPage > 1) {
        $oPageLayout->sRelPrev = $oPage->getBaseUrlPath();
        // is second page, previous is url without page
        if (($iCurrPage - 1) > 1) {
            $oPageLayout->sRelPrev .= '?page=' . ($iCurrPage - 1);
        }
    }

    // is first page, set canonical in case ?page=1 is set
    if ($iCurrPage == 1) {
        $oPageLayout->sCanonical = $oPage->getBaseUrlPath();
    }

    $oPageLayout->sViewPath = getSiteView('catalogProducts_overview', 'catalog');
}

# Include the template
include_once getSiteView('layout');
?>