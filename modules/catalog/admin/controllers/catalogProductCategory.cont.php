<?php

# check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

global $oPageLayout;

# set page layout properties
$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = sysTranslations::get('catalog_category');
$oPageLayout->sModuleName  = sysTranslations::get('catalog_category');

# max levels for page structure depth
$iMaxLevels = CatalogProductCategory::MAX_LEVELS;

# get status update from session
$oPageLayout->sStatusUpdate = http_session("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once
# handle add/edit
if (http_get("param1") == 'bewerken' || http_get("param1") == 'toevoegen') {
    # set crop referrer for this module
    $_SESSION['cropReferrer'] = ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . http_get("param2");

    if (http_get("param1") == 'bewerken' && is_numeric(http_get("param2"))) {
        $oProductCategory = CatalogProductCategoryManager::getProductCategoryById(http_get("param2"));
        if (empty($oProductCategory)) {
            http_redirect(ADMIN_FOLDER . "/");
        }
    } else {
        $oProductCategory = new CatalogProductCategory();
        if (http_get("parentCatalogProductCategoryId")) {
            $oParentProductCategory = CatalogProductCategoryManager::getProductCategoryById(http_get("parentCatalogProductCategoryId"));
            if (!$oParentProductCategory || $oParentProductCategory->level >= CatalogProductCategory::MAX_LEVELS) {
                http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
            }
            $oProductCategory->parentCatalogProductCategoryId = $oParentProductCategory->catalogProductCategoryId;
        }
    }

    # action = save
    if (http_post("action") == 'save' && CSRFSynchronizerToken::validate()) {
        # load data in object
        $oProductCategory->_load($_POST);

        # if object is valid, save
        if ($oProductCategory->isValid()) {
            CatalogProductCategoryManager::saveProductCategory($oProductCategory); //save object

            # save translations
            foreach (AdminLocales::getLanguages() as $oLanguage) {
                $oProductCategoryTranslation                           = new CatalogProductCategoryTranslation();
                $oProductCategoryTranslation->catalogProductCategoryId = $oProductCategory->catalogProductCategoryId;
                $oProductCategoryTranslation->languageId               = $oLanguage->languageId;
                $oProductCategoryTranslation->name                     = $_POST['name'][$oLanguage->languageId];
                $oProductCategoryTranslation->content                  = $_POST['content'][$oLanguage->languageId];
                $oProductCategoryTranslation->windowTitle              = $_POST['windowTitle'][$oLanguage->languageId];
                $oProductCategoryTranslation->metaKeywords             = $_POST['metaKeywords'][$oLanguage->languageId];
                $oProductCategoryTranslation->metaDescription          = $_POST['metaDescription'][$oLanguage->languageId];

                if ($oCurrentUser->isSEO()) {
                    $oProductCategoryTranslation->setUrlPart($_POST['urlPart'][$oLanguage->languageId]);
                }

                if ($oProductCategoryTranslation->isValid()) {
                    CatalogProductCategoryTranslationManager::saveProductCategoryTranslation($oProductCategoryTranslation);
                } else {
                    Debug::logError(
                        "",
                        "CatalogProductCategory module php validate error",
                        __FILE__,
                        __LINE__,
                        "Tried to save CatalogProductCategoryTranslation with wrong values despite javascript check.<br />" . _d($_POST, 1, 1),
                        Debug::LOG_IN_EMAIL
                    );
                }
            }

            $_SESSION['statusUpdate'] = sysTranslations::get('catalog_category_saved'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oProductCategory->catalogProductCategoryId);
        } else {
            Debug::logError("", "CatalogProductCategory module php validate error", __FILE__, __LINE__, "Tried to save CatalogProductCategory with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $_SESSION['statusUpdate'] = sysTranslations::get('catalog_category_not_saved');
        }
    }

    $oPageLayout->sViewPath = getAdminView('productCategories/catalogProductCategory_form', 'catalog');
} # set object online/offline
elseif (http_get("param1") == 'ajax-setOnline') {
    if(!CSRFSynchronizerToken::validate()) {
        die(json_encode(['status'=>false]));
    }
    $bOnline = http_get("online", 0); //no value, set offline by default
    $bAjax = http_get("ajax", false); //controller requested by ajax
    $iProductCategoryId = http_get("param2");
    $oResObj = new stdClass(); //standard class for json feedback
    # update online for object
    if (is_numeric($iProductCategoryId)) {
        $oResObj->success = CatalogProductCategoryManager::updateOnlineByProductCategoryId($bOnline, $iProductCategoryId);
        $oResObj->catalogProductCategoryId = $iProductCategoryId;
        $oResObj->online = $bOnline;
    }

    # redirect to overview page if this isn't AJAX
    if (!$bAjax) {
        http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '');
    }
    die(json_encode($oResObj));
} elseif (http_get('param1') == 'structuur-wijzigen') {
    if (http_post("action") == 'saveProductCategoryStructure' && CSRFSynchronizerToken::validate()) {
        #
        if (http_post("productCategoryStructure")) {
            parse_str(http_post("productCategoryStructure"), $aProductCategoryArray);
        }

        $iProductCategoryOrder = 0;
        # loop trough all categories and save new parentId
        foreach ($aProductCategoryArray['productCategory'] AS $iProductCategoryId => $mParentProductCategoryReference) {

            # get category and save
            $oProductCategory = CatalogProductCategoryManager::getProductCategoryById($iProductCategoryId);

            if (!$oProductCategory) {
                continue;
            }
            if ($mParentProductCategoryReference == 'root') {
                $oProductCategory->parentCatalogProductCategoryId = null;
            } elseif (is_numeric($mParentProductCategoryReference)) {
                $oProductCategory->parentCatalogProductCategoryId = $mParentProductCategoryReference;
            } else {
                continue;
            }

            $oProductCategory->order = $iProductCategoryOrder; // set the order to the category
            # save CatalogProductCategory
            if ($oProductCategory->isValid()) {
                CatalogProductCategoryManager::saveProductCategory($oProductCategory);
            }
            $iProductCategoryOrder++;
        }
        $_SESSION['statusUpdate'] = sysTranslations::get('catalog_category_order_saved'); //save status update into session
        http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
    }

    $aLevel1ProductCategories = CatalogProductCategoryManager::getProductCategoriesByFilter(['showAll' => true, 'level' => 1]);
    $oPageLayout->sViewPath   = getAdminView('productCategories/catalogProductCategories_change_structure', 'catalog');
} # delete object
elseif (http_get("param1") == 'verwijderen' && is_numeric(http_get("param2"))) {
    if(CSRFSynchronizerToken::validate()) {
        if (is_numeric(http_get("param2"))) {
            $oProductCategory = CatalogProductCategoryManager::getProductCategoryById(http_get("param2"));
        }

        if (!empty($oProductCategory) && CatalogProductCategoryManager::deleteProductCategory($oProductCategory)) {
            $_SESSION['statusUpdate'] = sysTranslations::get('catalog_category_deleted'); //save status update into session
        } else {
            $_SESSION['statusUpdate'] = sysTranslations::get('catalog_category_not_deleted'); //save status update into session
        }
    }
    http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
} # display overview
else {
    $aLevel1ProductCategories = CatalogProductCategoryManager::getProductCategoriesByFilter(['showAll' => true, 'level' => 1]);
    $oPageLayout->sViewPath   = getAdminView('productCategories/catalogProductCategories_overview', 'catalog');
}

# include template
include_once getAdminView('layout');
?>