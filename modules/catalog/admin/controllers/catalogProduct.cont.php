<?php

# check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

global $oPageLayout;

# reset crop settings
unset($_SESSION['aCropSettings']);
# set page layout properties
$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = sysTranslations::get('catalog_product');
$oPageLayout->sModuleName  = sysTranslations::get('catalog_product');

# get status update from session
$oPageLayout->sStatusUpdate = http_session("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once
# handle productFilter
$aProductFilter = http_session('productFilter');
if (http_post('filterForm')) {
    $aProductFilter            = http_post('productFilter');
    $aProductFilter['showAll'] = true;
    $_SESSION['productFilter'] = $aProductFilter;
}

if (http_post('resetFilter') || empty($aProductFilter)) {
    unset($_SESSION['productFilter']);
    $aProductFilter                             = [];
    $aProductFilter['q']                        = '';
    $aProductFilter['catalogBrandId']           = '';
    $aProductFilter['catalogProductTypeId']     = '';
    $aProductFilter['catalogProductCategoryId'] = '';
    $aProductFilter['showOnHome']               = false;
    $aProductFilter['emptyGoogleCategory']      = false;
    $aProductFilter['showAll']                  = true;
}

# handle perPage
if (http_post('setPerPage')) {
    $_SESSION['productsPerPage'] = http_post('perPage');
}

# add tax to price
if (http_get('ajax') == 'addTax') {
    $oResObj          = new stdClass();
    $oResObj->success = false;

    # return if the required fields are empty
    if (empty($_GET['salePrice']) || !is_numeric(http_get('salePrice')) || empty($_GET['taxPercentageId']) || !is_numeric(http_get('taxPercentageId'))) {
        die(json_encode($oResObj));
    }

    $oResObj->salePrice                    = http_get('salePrice');
    $oResObj->formattedSalePriceWithoutTax = number_format(http_get('salePrice'), 2, '.', '');
    $oResObj->salePriceWithTax             = TaxManager::addTax(http_get('salePrice'), TaxManager::getPercentageById(http_get('taxPercentageId')), $fTaxPrice);
    $oResObj->formattedSalePriceWithTax    = number_format($oResObj->salePriceWithTax, 2, '.', '');
    $oResObj->taxPrice                     = $fTaxPrice;
    $oResObj->success                      = true;

    # return with object
    die(json_encode($oResObj));
} # subtract tax from price
elseif (http_get('ajax') == 'subtractTax') {
    $oResObj          = new stdClass();
    $oResObj->success = false;

    # return if the required fields are empty
    if (empty($_GET['salePrice']) || !is_numeric(http_get('salePrice')) || empty($_GET['taxPercentageId']) || !is_numeric(http_get('taxPercentageId'))) {
        die(json_encode($oResObj));
    }

    $oResObj->salePriceWithTax             = http_get('salePrice');
    $oResObj->formattedSalePriceWithTax    = number_format(http_get('salePrice'), 2, '.', '');
    $oResObj->salePrice                    = TaxManager::subtractTax(http_get('salePrice'), TaxManager::getPercentageById(http_get('taxPercentageId')), $fTaxPrice);
    $oResObj->formattedSalePriceWithoutTax = number_format($oResObj->salePrice, 2, '.', '');
    $oResObj->taxPrice                     = $fTaxPrice;
    $oResObj->success                      = true;

    # return with object
    die(json_encode($oResObj));
}

# return google Categories from the website
if (http_get('ajax') == 'googleCategories') {

    $oLanguage = LanguageManager::getLanguageById(http_get('languageId'));

    if ($oLanguage) {
        $file = fopen("http://www.google.com/basepages/producttype/taxonomy." . $oLanguage->code . "-" . ($oLanguage->code == 'en' ? 'GB' : strtoupper($oLanguage->code)) . ".txt", "r") or die(json_encode($oResObj));

        //Output a line of the file until the end is reached
        while (!feof($file)) {
            $oResObj[] = trim(fgets($file));
        }

        fclose($file);
        # return with object
        die(json_encode($oResObj));
    }
}

# return the template for the related products
if (http_get('ajax') == 'relatedProducts') {
    $oProduct = CatalogProductManager::getProductById(http_get("param2"));

    $aRelatedProducts = $oProduct->getRelatedProducts();

    ob_start();
    require_once getAdminSnippet('catalogProductRelated', 'catalog');
    $oResObj->html = ob_get_contents();
    ob_end_clean();
    # return with object
    die(json_encode($oResObj));
}

# return the list with the possible related products
if (http_get('ajax') == 'related-products-autoComplete') {
    $oResObj = [];

    $sFilter               = [];
    $sFilter['notRelated'] = '1';
    $aProducts             = CatalogProductManager::getRelatedProductsByFilter(http_get("param2"), $sFilter);

    $key = 0;
    foreach ($aProducts as $oProduct) {
        $oResObj[$key]['originProductid'] = http_get("catalogProductId");
        $aProductImages                   = $oProduct->getImages();
        if (!empty($aProductImages)) {
            $oImage                = ImageManager::getImageById($aProductImages[0]->imageId);
            $oImageFile            = $oImage->getImageFileByReference('detail');
            $oResObj[$key]['link'] = $oImageFile->link;
        } else {
            $oResObj[$key]['link'] = getSiteImage('shop-no-image.jpg');
        }
        $oResObj[$key]['value']            = $oProduct->getTranslations('auto-admin')->name;
        $oResObj[$key]['relatedProductid'] = $oProduct->catalogProductId;
        $key++;
    }
    //  # return with object
    die(json_encode($oResObj));
}

# Save a related product
if (http_get('ajax') == 'related-product-save' && is_numeric(http_get('relatedProductId'))) {
    $oResObj          = new stdClass();
    $oResObj->success = true;

    $oProduct = CatalogProductManager::getProductById(http_get("param2"));
    $oProduct->saveRelatedProduct(http_get("relatedProductId"));

    # return
    die(json_encode($oResObj));
}

# Delete a related product
if (http_get('ajax') == 'related-product-delete' && is_numeric(http_get('relatedProductId'))) {

    $oResObj          = new stdClass();
    $oResObj->success = true;

    $oProduct = CatalogProductManager::getProductById(http_get("param2"));
    $oProduct->deleteRelatedProduct(http_get("relatedProductId"));

    # return
    die(json_encode($oResObj));
}

#handle add/edit
if (http_get("param1") == 'bewerken' || http_get("param1") == 'toevoegen') {
    # set crop referrer for this module
    $_SESSION['cropReferrer'] = ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . http_get("param2");

    if (http_get("param1") == 'bewerken' && is_numeric(http_get("param2"))) {
        $oProduct = CatalogProductManager::getProductById(http_get("param2"));
        if (empty($oProduct)) {
            http_redirect(ADMIN_FOLDER . "/");
        }
    } else {
        $oProduct = new CatalogProduct();
    }

    # action = save
    if (http_post("action") == 'save' && CSRFSynchronizerToken::validate()) {

        # load data in object
        $oProduct->_load($_POST);

        $oProduct->setReducedPrice(http_post("reducedPrice"));
        #if the reducedPrice is not set, I set it to null
        if ($oProduct->getReducedPrice(Settings::get('taxIncluded')) == '') {
            $oProduct->setReducedPrice(null);
        }

        $oProduct->setSalePrice(http_post("salePrice"));
        $oProduct->setPurchasePrice(http_post("purchasePrice"));

        # check for categories and set them into the product
        $aCatalogProductCategories = [];
        foreach (http_post('catalogProductCategoryIds', []) AS $iProductCategoryId) {
            if (!is_numeric($iProductCategoryId)) {
                continue;
            }
            $aCatalogProductCategories[] = new CatalogProductCategory(['catalogProductCategoryId' => $iProductCategoryId]);
        }
        $oProduct->setCategories($aCatalogProductCategories, 'all');

        # if object is valid, save
        if ($oProduct->isValid()) {
            CatalogProductManager::saveProduct($oProduct); //save object
            # save translations
            foreach (AdminLocales::getLanguages() as $oLanguage) {
                $oProductTranslation                   = new CatalogProductTranslation();
                $oProductTranslation->catalogProductId = $oProduct->catalogProductId;
                $oProductTranslation->languageId       = $oLanguage->languageId;
                $oProductTranslation->name             = $_POST['name'][$oLanguage->languageId];
                $oProductTranslation->description      = $_POST['description'][$oLanguage->languageId];
                $oProductTranslation->windowTitle      = $_POST['windowTitle'][$oLanguage->languageId];
                $oProductTranslation->metaKeywords     = $_POST['metaKeywords'][$oLanguage->languageId];
                $oProductTranslation->metaDescription  = $_POST['metaDescription'][$oLanguage->languageId];
                $oProductTranslation->googleCategory   = $_POST['googleCategory'][$oLanguage->languageId];

                if ($oCurrentUser->isSEO()) {
                    $oProductTranslation->setUrlPart($_POST['urlPart'][$oLanguage->languageId]);
                }

                if ($oProductTranslation->isValid()) {
                    CatalogProductTranslationManager::saveProductTranslation($oProductTranslation);
                } else {
                    Debug::logError("", "CatalogProduct module php validate error", __FILE__, __LINE__, "Tried to save CatalogProductTranslation with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
                }
            }

            $_SESSION['statusUpdate'] = sysTranslations::get('catalog_product_saved'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oProduct->catalogProductId);
        } else {
            Debug::logError("", "CatalogProduct module php validate error", __FILE__, __LINE__, "Tried to save CatalogProduct with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $_SESSION['statusUpdate'] = sysTranslations::get('catalog_product_not_saved');
        }
    }

    # action saveImage
    if (http_post("action") == 'saveImage' && CSRFSynchronizerToken::validate()) {

        $bCheckMime = true;

        # for upload from MFUpload
        if (http_post("MFUpload")) {
            $oResObj          = new stdClass();
            $oResObj->success = false;
            $bCheckMime       = true;
        }

        # upload file or return error
        $oUpload = new Upload($_FILES['image'], CatalogProduct::IMAGES_PATH . "/original/", (http_post('title') != '' ? http_post('title') : null), ['jpg', 'png', 'gif', 'jpeg'], $bCheckMime);

        # save image to database on success
        if ($oUpload->bSuccess === true) {

            // resize original to acceptable size
            if (!ImageManager::resizeImage(DOCUMENT_ROOT . $oUpload->sNewFilePath, DOCUMENT_ROOT . $oUpload->sNewFilePath, 1500, 1500, $sErrorMsg)) {
                $_SESSION['statusUpdate'] = sysTranslations::get('global_no_resize') . $sErrorMsg; //error resizing image
            }

            # make Image object and save
            $oImage = new Image();

            $oImageFile            = new ImageFile();
            $oImageFile->link      = $oUpload->sNewFilePath;
            $oImageFile->title     = http_post('title', '');
            $oImageFile->name      = $oUpload->sNewFileBaseName;
            $oImageFile->mimeType  = $oUpload->sMimeType;
            $oImageFile->size      = $oUpload->iSize;
            $oImageFile->reference = 'original';

            $aImageFiles[] = $oImageFile;

            # always make detail image (resized of original)
            if (ImageManager::autoCropAndResizeImage(DOCUMENT_ROOT . $oImageFile->getLinkWithoutQueryString(), DOCUMENT_ROOT . CatalogProduct::IMAGES_PATH . '/detail/' . $oImageFile->name, 800, 800, $sErrorMsg)) {
                $oImageFileThumb            = clone $oImageFile;
                $oImageFileThumb->link      = CatalogProduct::IMAGES_PATH . '/detail/' . $oImageFile->name;
                $oImageFileThumb->name      = pathinfo($oImageFileThumb->link, PATHINFO_BASENAME);
                $oImageFileThumb->size      = filesize(DOCUMENT_ROOT . $oImageFileThumb->getLinkWithoutQueryString());
                $oImageFileThumb->reference = 'detail';

                $aImageFiles[]            = clone $oImageFileThumb;
                $_SESSION['statusUpdate'] = sysTranslations::get('global_image_uploaded');
            } else {
                $_SESSION['statusUpdate'] = sysTranslations::get('global_no_image_detail') . $sErrorMsg; //error cropping image
            }

            # make crop_overview
            if (ImageManager::autoCropAndResizeImage(DOCUMENT_ROOT . $oImageFile->getLinkWithoutQueryString(), DOCUMENT_ROOT . CatalogProduct::IMAGES_PATH . '/crop_small/' . $oImageFile->name, 400, 400, $sErrorMsg, true)) {
                $oImageFileThumb            = clone $oImageFile;
                $oImageFileThumb->link      = CatalogProduct::IMAGES_PATH . '/crop_small/' . $oImageFile->name;
                $oImageFileThumb->name      = pathinfo($oImageFileThumb->link, PATHINFO_BASENAME);
                $oImageFileThumb->size      = filesize(DOCUMENT_ROOT . $oImageFileThumb->getLinkWithoutQueryString());
                $oImageFileThumb->reference = 'crop_small';

                $aImageFiles[]            = clone $oImageFileThumb;
                $_SESSION['statusUpdate'] = sysTranslations::get('global_automatic_cutout');
            } else {
                $_SESSION['statusUpdate'] = sysTranslations::get('global_no_automatic_cutout') . $sErrorMsg; //error cropping image
            }

            # make cms_thumb
            if (ImageManager::autoCropAndResizeImage(DOCUMENT_ROOT . $oImageFile->getLinkWithoutQueryString(), DOCUMENT_ROOT . CatalogProduct::IMAGES_PATH . '/cms_thumb/' . $oImageFile->name, 100, 75, $sErrorMsg, true)) {
                $oImageFileThumb            = clone $oImageFile;
                $oImageFileThumb->link      = CatalogProduct::IMAGES_PATH . '/cms_thumb/' . $oImageFile->name;
                $oImageFileThumb->name      = pathinfo($oImageFileThumb->link, PATHINFO_BASENAME);
                $oImageFileThumb->size      = filesize(DOCUMENT_ROOT . $oImageFileThumb->getLinkWithoutQueryString());
                $oImageFileThumb->reference = 'cms_thumb';

                $aImageFiles[]            = clone $oImageFileThumb;
                $_SESSION['statusUpdate'] = sysTranslations::get('global_automatic_cutout');
            } else {
                $_SESSION['statusUpdate'] = sysTranslations::get('global_no_automatic_cutout') . $sErrorMsg; //error cropping image
            }

            $oImage->setImageFiles($aImageFiles); //set imageFiles to Image object
            # save Image
            ImageManager::saveImage($oImage);

            # save object's Image relation
            CatalogProductManager::saveProductImageRelation($oProduct->catalogProductId, $oImage->imageId);

            # for MFUpload
            if (http_post("MFUpload")) {
                $oResObj->success         = true;
                $oImageFileThumb->imageId = $oImage->imageId; // set for use in MFUpload
                // add some extra values
                $oImageFileThumb->isOnlineChangeable  = $oImage->isOnlineChangeable();
                $oImageFileThumb->isCropable          = $oImage->isCropable();
                $oImageFileThumb->isEditable          = $oImage->isEditable();
                $oImageFileThumb->isDeletable         = $oImage->isDeletable();
                $oImageFileThumb->hasNeededImageFiles = $oImage->hasImageFiles(['cms_thumb', 'crop_small']);

                $oResObj->imageFile = $oImageFileThumb; // for adding image to list (last imageFile object)
                print json_encode($oResObj);
                die;
            }

            # back to edit
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oProduct->catalogProductId);
        } else {
            # for MFUpload
            if (http_post("MFUpload")) {
                $oResObj->errorMsg = $oUpload->getErrorMessage();
                print json_encode($oResObj);
                die;
            }

            $_SESSION['statusUpdate'] = sysTranslations::get('global_image_not_uploaded') . $oUpload->getErrorMessage(); //error uploading file
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oProduct->catalogProductId);
        }
    }

    # save type specific property values
    if (http_post('action') == 'saveProductPropertyValues' && CSRFSynchronizerToken::validate()) {

        //_d($_POST);
        # set the CatalogProductPropertyValue objects
        $aPropertyValues = [];
        foreach (http_post('propertyTypes', []) AS $iPropertyTypeId => $aPropertyType) {
            //_d($iPropertyTypeId);
            foreach ($aPropertyType AS $iPossibleValueIdValue) {
                foreach (AdminLocales::getLanguages() as $oLanguage) {

                    if (!empty($iPossibleValueIdValue) && !is_array($iPossibleValueIdValue)) {
                        $oPossibleValue = CatalogProductPropertyTypePossibleValueManager::getProductPropertyTypePossibleValueById($iPossibleValueIdValue);
                    }

                    $oPropertyValue = new CatalogProductPropertyValue(
                        [
                            'catalogProductId'             => $oProduct->catalogProductId,
                            'catalogProductPropertyTypeId' => $iPropertyTypeId,
                            'languageId'                   => $oLanguage->languageId,
                            'value'                        => (isset($oPossibleValue) ? $oPossibleValue->getTranslations($oLanguage->languageId)->value : null),
                        ]
                    );

                    if ($oPropertyValue->isValid()) {
                        $aPropertyValues[] = $oPropertyValue;
                    }
                }
            }
        }

        # open fields
        foreach (http_post('openFields', []) as $iPropertyTypeId => $aPropertyType) {

            if (isset($aPropertyType['same'])) {
                // same value
                foreach (AdminLocales::getLanguages() as $oLanguage) {
                    $oPropertyValue = new CatalogProductPropertyValue(
                        [
                            'catalogProductId'             => $oProduct->catalogProductId,
                            'catalogProductPropertyTypeId' => $iPropertyTypeId,
                            'languageId'                   => $oLanguage->languageId,
                            'value'                        => $aPropertyType['same'],
                        ]
                    );

                    if ($oPropertyValue->isValid()) {
                        $aPropertyValues[] = $oPropertyValue;
                    }
                }
            } else {

                foreach ($aPropertyType as $iLanguageId => $mValue) {

                    $oPropertyValue = new CatalogProductPropertyValue(
                        [
                            'catalogProductId'             => $oProduct->catalogProductId,
                            'catalogProductPropertyTypeId' => $iPropertyTypeId,
                            'languageId'                   => $iLanguageId,
                            'value'                        => $mValue,
                        ]
                    );

                    if ($oPropertyValue->isValid()) {
                        $aPropertyValues[] = $oPropertyValue;
                    }
                }
            }
        }

        $oProduct->setPropertyValues($aPropertyValues);

        # if object is valid, save
        if ($oProduct->isValid()) {
            CatalogProductManager::saveProduct($oProduct); //die; //save object
            $_SESSION['statusUpdate'] = sysTranslations::get('catalog_product_saved'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oProduct->catalogProductId);
        } else {
            Debug::logError("", "CatalogProduct module php validate error", __FILE__, __LINE__, "Tried to save CatalogProduct with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $_SESSION['statusUpdate'] = sysTranslations::get('catalog_product_not_saved');
        }

        CatalogProductPropertyValueManager::saveProductPropertyValues($oProduct);
        $_SESSION['statusUpdate'] = sysTranslations::get('catalog_product_properties_saved'); //save status update into session
        http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oProduct->catalogProductId);
    }

    // add Image to a color
    if (http_post('action') == 'addProductImageRelation') {
        $oCatalogProductImageRelation = new CatalogProductImageRelation();
        $oCatalogProductImageRelation->_load($_POST);
        $oCatalogProductImageRelation->catalogProductId = $oProduct->catalogProductId;

        if ($oCatalogProductImageRelation->isValid()) {
            CatalogProductImageRelationManager::saveCatalogProductImageRelation($oCatalogProductImageRelation);
            $_SESSION['statusUpdate'] = sysTranslations::get('catalog_stock_added'); //save status update into session
        } else {
            $_SESSION['statusUpdate'] = sysTranslations::get('catalog_stock_not_added'); //save status update into session
        }

        http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oProduct->catalogProductId);
    }

    // add stock record
    if (http_post('action') == 'addSizeColorRelation' && CSRFSynchronizerToken::validate()) {
        $oCatalogProductSizeColorRelation = new CatalogProductSizeColorRelation();
        $oCatalogProductSizeColorRelation->_load($_POST);
        $oCatalogProductSizeColorRelation->catalogProductId = $oProduct->catalogProductId;

        $oCatalogProductSizeColorRelationDuplicate = CatalogProductSizeColorRelationManager::getCatalogProductSizeColorRelation(
            $oCatalogProductSizeColorRelation->catalogProductId,
            $oCatalogProductSizeColorRelation->catalogProductSizeId,
            $oCatalogProductSizeColorRelation->catalogProductColorId
        );
        if ($oCatalogProductSizeColorRelationDuplicate) {
            $_SESSION['statusUpdate'] = sysTranslations::get('catalog_stock_not_added'); //save status update into session
        } else {

            if ($oCatalogProductSizeColorRelation->isValid()) {
                CatalogProductSizeColorRelationManager::saveCatalogProductSizeColorRelation($oCatalogProductSizeColorRelation);
                $_SESSION['statusUpdate'] = sysTranslations::get('catalog_stock_added'); //save status update into session
            } else {
                $_SESSION['statusUpdate'] = sysTranslations::get('catalog_stock_not_added'); //save status update into session
            }
        }

        http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oProduct->catalogProductId);
    }

    // get products for prev and next buttons
    if (!empty($oProduct->catalogProductId)) {
        $oProductNext = $oProduct->getNextProduct($aProductFilter, ['`cp`.`catalogProductId`' => 'ASC']);
        $oProductPrev = $oProduct->getPrevProduct($aProductFilter, ['`cp`.`catalogProductId`' => 'DESC']);
    }

    # set settings for image management
    $oImageManagerHTML                                       = new ImageManagerHTML();
    $oImageManagerHTML->aImages                              = $oProduct->getImages('all');
    $oImageManagerHTML->cropLink                             = ADMIN_FOLDER . '/' . http_get('controller') . '/crop-image';
    $oImageManagerHTML->sUploadUrl                           = ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oProduct->catalogProductId;
    $oImageManagerHTML->aNeededImageFileReferences           = ['cms_thumb', 'crop_small'];
    $oImageManagerHTML->bShowCropAfterUploadOption           = false;
    $oImageManagerHTML->sExtraUploadLine                     = sysTranslations::get('global_image_size_960_700');
    $oImageManagerHTML->bMultipleFileUpload                  = true;
    $oImageManagerHTML->aMultipleFileUploadAllowedExtensions = ['png', 'jpg', 'gif', 'jpeg'];

    $oPageLayout->sViewPath = getAdminView('catalogProducts/catalogProduct_form', 'catalog');
} # crop Image
elseif (http_get("param1") == 'crop-image' && is_numeric(http_get("imageId"))) {
    $oImage = ImageManager::getImageById(http_get("imageId"));

    if (!$oImage || !$oImage->getImageFileByReference("original")) {
        $_SESSION['statusUpdate'] = sysTranslations::get('global_image_not_available'); //error getting image
        http_redirect(http_session('cropReferrer'));
    }

    # set crop settings
    $oCropSettings                 = new CropSettings();
    $oCropSettings->iImageId       = $oImage->imageId;
    $oCropSettings->sReferenceName = 'original';

    $oCropSettings->setAspectRatio(400, 400);

    $oCropSettings->bShowPreview     = true;
    $oCropSettings->iMaxPreviewWidth = 400;

    $oCropSettings->sReferrer      = http_session('cropReferrer');
    $oCropSettings->sReferrerTekst = 'product bewerken';

    # create crop and show on crop form page
    $oCropSettings->addCrop(400, 400, CatalogProduct::IMAGES_PATH . '/crop_small/' . $oImage->getImageFileByReference('original')->name, 'crop_small', true, true);

    # create crop and show on crop form page
    $oCropSettings->addCrop(800, 800, CatalogProduct::IMAGES_PATH . '/detail/' . $oImage->getImageFileByReference('original')->name, 'detail', true, true);

    # create crop but do not show on crop form page
    $oCropSettings->addCrop(100, 75, $oImage->getImageFileByReference('cms_thumb')->link, 'cms_thumb', false, true);

    # add setting to session in an array
    $_SESSION['aCropSettings'][] = clone $oCropSettings;

    http_redirect(ADMIN_FOLDER . '/crop');
} # set object online/offline
elseif (http_get("param1") == 'ajax-setOnline') {
    if(!CSRFSynchronizerToken::validate()){
        die(json_encode(['status'=>false]));
    }
    $bOnline    = http_get("online", 0); //no value, set offline by default
    $bAjax      = http_get("ajax", false); //controller requested by ajax
    $iProductId = http_get("param2");
    $oResObj    = new stdClass(); //standard class for json feedback
    # update online for object
    if (is_numeric($iProductId)) {
        $oResObj->success          = CatalogProductManager::updateOnlineByProductId($bOnline, $iProductId);
        $oResObj->catalogProductId = $iProductId;
        $oResObj->online           = $bOnline;
    }

    # redirect to overview page if this isn't AJAX
    if (!$bAjax) {
        http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '');
    }

    die(json_encode($oResObj));
} # Select image to match to a color
elseif (http_get("param1") == 'bewerken-afbeelding-kleur-relatie' && is_numeric(http_get("catalogProductId"))) {
    $oProduct = CatalogProductManager::getProductById(http_get("catalogProductId"));

    $aImages = CatalogProductManager::getImagesByFilter($oProduct->catalogProductId);

    // I take the color
    $oColor     = CatalogProductColorManager::getProductColorById(http_get("colorId"));
    $aImageFile = [];
    foreach ($aImages AS $oImage) {
        // I only show the images wich don't have the selected color assigned
        $oProductImageRelation = CatalogProductImageRelationManager::getCatalogProductImageRelationById(http_get("catalogProductId"), $oImage->imageId);
        if (($oProductImageRelation->catalogProductColorId != $oColor->catalogProductColorId) && is_null($oProductImageRelation->catalogProductColorId)) {
            $oImageFile   = $oImage->getImageFileByReference('detail');
            $aImageFile[] = $oImageFile;
        }
    }
    include getAdminView('catalogProducts/catalogProductImageColorRelation_form', 'catalog');
    die;
} # Reset(Set to null) a color in a Product-image relation
elseif (http_get("param1") == 'verwijder-afbeeldingen-kleur-relatie' && is_numeric(http_get("catalogProductId")) && is_numeric(http_get("catalogProductImageId"))) {
    if(CSRFSynchronizerToken::validate()){
        if (is_numeric(http_get("catalogProductId")) && is_numeric(http_get("catalogProductImageId"))) {
            $oProductImageRelation = CatalogProductImageRelationManager::getCatalogProductImageRelationById(http_get("catalogProductId"), http_get("catalogProductImageId"));
        }
        print_r($oProductImageRelation);
        CatalogProductImageRelationManager::resetCatalogProductImageColorRelation($oProductImageRelation);
    }
    http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . http_get("catalogProductId"));
    die;
} # delete object
elseif (http_get("param1") == 'verwijderen' && is_numeric(http_get("param2"))) {
    if(CSRFSynchronizerToken::validate()) {
        if (is_numeric(http_get("param2"))) {
            $oProduct = CatalogProductManager::getProductById(http_get("param2"));
        }

        if (!empty($oProduct) && CatalogProductManager::deleteProduct($oProduct)) {
            $_SESSION['statusUpdate'] = sysTranslations::get('catalog_product_deleted'); //save status update into session
        } else {
            $_SESSION['statusUpdate'] = sysTranslations::get('catalog_product_not_deleted'); //save status update into session
        }
    }
    http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
} # delete size color relation
elseif (http_get("param1") == 'verwijder-maat-kleur-relatie' && is_numeric(http_get("catalogProductId")) && is_numeric(http_get("catalogProductSizeId")) && is_numeric(http_get("catalogProductColorId"))) {
    if(CSRFSynchronizerToken::validate()) {
        $oCatalogProductSizeColorRelation = CatalogProductSizeColorRelationManager::getCatalogProductSizeColorRelation(http_get("catalogProductId"), http_get("catalogProductSizeId"), http_get("catalogProductColorId"));
        if (!empty($oCatalogProductSizeColorRelation) && CatalogProductSizeColorRelationManager::deleteCatalogProductSizeColorRelation($oCatalogProductSizeColorRelation)) {
            $_SESSION['statusUpdate'] = sysTranslations::get('catalog_stock_deleted'); //save status update into session
        } else {
            $_SESSION['statusUpdate'] = sysTranslations::get('catalog_stock_not_deleted'); //save status update into session
        }
    }
    http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . http_get("catalogProductId"));
} # edit size color relation
elseif (http_get("param1") == 'bewerken-maat-kleur-relatie' && is_numeric(http_get("catalogProductId")) && is_numeric(http_get("catalogProductSizeId")) && is_numeric(http_get("catalogProductColorId"))) {
    if(CSRFSynchronizerToken::validate()) {
        $oCatalogProductSizeColorRelation = CatalogProductSizeColorRelationManager::getCatalogProductSizeColorRelation(http_get("catalogProductId"), http_get("catalogProductSizeId"), http_get("catalogProductColorId"));
        if (!empty($oCatalogProductSizeColorRelation)) {
            if (http_post('action') == 'saveSizeColorRelation') {
                $oCatalogProductSizeColorRelation->_load($_POST);
                if ($oCatalogProductSizeColorRelation->isValid()) {
                    CatalogProductSizeColorRelationManager::saveCatalogProductSizeColorRelation($oCatalogProductSizeColorRelation);
                    $_SESSION['statusUpdate'] = sysTranslations::get('catalog_stock_updated'); //save status update into session
                } else {
                    $_SESSION['statusUpdate'] = sysTranslations::get('catalog_stock_not_updated'); //save status update into session
                }
                http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . http_get("catalogProductId"));
            }
            include getAdminView('catalogProducts/catalogProductSizeColorRelation_form', 'catalog');
        }
    }
    die;
} elseif (http_get('param1') == 'image-list') {
    $oProduct = CatalogProductManager::getProductById(http_get('param2'));

    if (!$oProduct) {
        die;
    }

    $aImages     = $oProduct->getImages('all');
    $aImageFiles = [];
    $aLinks      = [];
    foreach ($aImages AS $oImage) {
        $oImageFile = $oImage->getImageFileByReference('detail');
        if (!$oImageFile) {
            continue;
        }
        $oLink        = new stdClass();
        $oLink->title = $oImageFile->title ? $oImageFile->title : $oImageFile->name;
        $oLink->value = CLIENT_HTTP_URL . $oImageFile->link;
        $aLinks[]     = $oLink;
    }

    die(json_encode($aLinks));
} # display overview
else {
    $iNrOfRecords = DBConnection::count('catalog_products');
    $iPerPage     = http_session('productsPerPage', 10);
    $iCurrPage    = http_get('page', 1);
    if (http_post('setPerPage')) {
        // reset iCurrPage on setPerPage change
        $iCurrPage = 1;
    }
    if ($iCurrPage > ($iNrOfRecords / $iPerPage) + 1) {
        // prevent non existing iCurrpage
        $iCurrPage = (round($iNrOfRecords / $iPerPage) + 1);
    }
    $iStart = (($iCurrPage - 1) * $iPerPage);
    if (!is_numeric($iCurrPage) || $iCurrPage <= 0) {
        http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
    }

    $aProducts  = CatalogProductManager::getProductsByFilter($aProductFilter, $iPerPage, $iStart, $iFoundRows);
    $iPageCount = !empty($iPerPage) ? (ceil($iFoundRows / $iPerPage)) : 0;

    $oPageLayout->sViewPath = getAdminView('catalogProducts/catalogProducts_overview', 'catalog');
}

# include template
include_once getAdminView('layout');
?>