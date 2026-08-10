<?php

# check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

global $oPageLayout;

# set page layout properties
$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = sysTranslations::get('global_brand');
$oPageLayout->sModuleName  = sysTranslations::get('global_brand');

# get status update from session
$oPageLayout->sStatusUpdate = http_session("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once
# handle add/edit
if (http_get("param1") == 'bewerken' || http_get("param1") == 'toevoegen') {
    # set crop referrer for this module
    $_SESSION['cropReferrer'] = ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . http_get("param2");

    if (http_get("param1") == 'bewerken' && is_numeric(http_get("param2"))) {
        $oBrand = CatalogBrandManager::getBrandById(http_get("param2"));
        if (empty($oBrand)) {
            http_redirect(ADMIN_FOLDER . "/");
        }
    } else {
        $oBrand = new CatalogBrand();
    }

    # action = save
    if (http_post("action") == 'save' && CSRFSynchronizerToken::validate()) {
        # load data in object
        $oBrand->_load($_POST);

        # if object is valid, save
        if ($oBrand->isValid()) {
            CatalogBrandManager::saveBrand($oBrand); //save object
            # save translations
            if (http_post('multilingual') == 1) {
                foreach (AdminLocales::getLanguages() as $oLanguage) {
                    $oNewBrandTranslation                 = new CatalogBrandTranslation();
                    $oNewBrandTranslation->catalogBrandId = $oBrand->catalogBrandId;
                    $oNewBrandTranslation->languageId     = $oLanguage->languageId;
                    $oNewBrandTranslation->name           = $_POST['name'][$oLanguage->languageId];

                    if ($oNewBrandTranslation->isValid()) {
                        CatalogBrandTranslationManager::saveProductBrandTranslation($oNewBrandTranslation);
                    } else {
                        Debug::logError("", "CatalogBrand module php validate error", __FILE__, __LINE__, "Tried to save CatalogBrandTranslation with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
                    }
                }
            } else {
                foreach (AdminLocales::getLanguages() as $oLanguage) {
                    $oNewBrandTranslation                 = new CatalogBrandTranslation();
                    $oNewBrandTranslation->catalogBrandId = $oBrand->catalogBrandId;
                    $oNewBrandTranslation->languageId     = $oLanguage->languageId;
                    $oNewBrandTranslation->name           = http_post('name_same');

                    if ($oNewBrandTranslation->isValid()) {
                        CatalogBrandTranslationManager::saveProductBrandTranslation($oNewBrandTranslation);
                    } else {
                        Debug::logError("", "CatalogBrand module php validate error", __FILE__, __LINE__, "Tried to save CatalogBrandTranslation with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
                    }
                }
            }

            $_SESSION['statusUpdate'] = sysTranslations::get('catalog_brand_saved'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oBrand->catalogBrandId);
        } else {
            Debug::logError("", "CatalogBrand module php validate error", __FILE__, __LINE__, "Tried to save CatalogBrand with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $_SESSION['statusUpdate'] = sysTranslations::get('catalog_brand_not_saved');
        }
    }

    $oPageLayout->sViewPath = getAdminView('catalogBrands/catalogBrand_form', 'catalog');
} # set object online/offline
elseif (http_get("param1") == 'ajax-setOnline') {
    if(!CSRFSynchronizerToken::validate()){
        die(json_encode(['status'=>false]));
    }
    $bOnline  = http_get("online", 0); //no value, set offline by default
    $bAjax    = http_get("ajax", false); //controller requested by ajax
    $iBrandId = http_get("param2");
    $oResObj  = new stdClass(); //standard class for json feedback
    # update online for object
    if (is_numeric($iBrandId)) {
        $oResObj->success        = CatalogBrandManager::updateOnlineByBrandId($bOnline, $iBrandId);
        $oResObj->catalogBrandId = $iBrandId;
        $oResObj->online         = $bOnline;
    }

    # redirect to overview page if this isn't AJAX
    if (!$bAjax) {
        http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '');
    }

    die(json_encode($oResObj));
} elseif (http_get('param1') == 'volgorde-wijzigen') {
    if (http_post('action') == 'saveOrder' && CSRFSynchronizerToken::validate()) {
        if (http_post('order')) {
            $aBrandIds = explode('|', http_post('order'));
            $iC        = 1;
            foreach ($aBrandIds AS $iBrandId) {
                $oBrand        = CatalogBrandManager::getBrandById($iBrandId);
                $oBrand->order = $iC;
                if ($oBrand->isValid()) {
                    CatalogBrandManager::saveBrand($oBrand);
                }
                $iC++;
            }
        }
        $_SESSION['statusUpdate'] = sysTranslations::get('global_sequence_saved'); //save status update into session
        http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
    }

    // get all items for order changing
    $aAllBrands             = CatalogBrandManager::getAllBrands();
    $oPageLayout->sViewPath = getAdminView('catalogBrands/catalogBrands_change_order', 'catalog');
} # delete object
elseif (http_get("param1") == 'verwijderen' && is_numeric(http_get("param2"))) {
    if(CSRFSynchronizerToken::validate()) {
        if (is_numeric(http_get("param2"))) {
            $oBrand = CatalogBrandManager::getBrandById(http_get("param2"));
        }

        if (!empty($oBrand) && CatalogBrandManager::deleteBrand($oBrand)) {
            $_SESSION['statusUpdate'] = sysTranslations::get('catalog_brand_deleted'); //save status update into session
        } else {
            $_SESSION['statusUpdate'] = sysTranslations::get('catalog_brand_not_deleted'); //save status update into session
        }
    }
    http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
} # display overview
else {
    $aAllBrands             = CatalogBrandManager::getAllBrands();
    $oPageLayout->sViewPath = getAdminView('catalogBrands/catalogBrands_overview', 'catalog');
}

# include template
include_once getAdminView('layout');
?>