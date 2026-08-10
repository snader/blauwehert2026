<?php

# check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

global $oPageLayout;

# set page layout properties
$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = sysTranslations::get('global_size');
$oPageLayout->sModuleName  = sysTranslations::get('global_size');

# get status update from session
$oPageLayout->sStatusUpdate = http_session("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once
# handle add/edit
if (http_get("param1") == 'bewerken' || http_get("param1") == 'toevoegen') {
    # set crop referrer for this module
    $_SESSION['cropReferrer'] = ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . http_get("param2");

    if (http_get("param1") == 'bewerken' && is_numeric(http_get("param2"))) {
        $oProductSize = CatalogProductSizeManager::getProductSizeById(http_get("param2"));
        if (empty($oProductSize)) {
            http_redirect(ADMIN_FOLDER . "/");
        }
    } else {
        $oProductSize = new CatalogProductSize();
    }

    # action = save
    if (http_post("action") == 'save' && CSRFSynchronizerToken::validate()) {

        # load data in object
        $oProductSize->_load($_POST);

        # if object is valid, save
        if ($oProductSize->isValid()) {
            CatalogProductSizeManager::saveProductSize($oProductSize); //save object

            # save translations
            if (http_post('multilingual') == 1) {
                foreach (AdminLocales::getLanguages() as $oLanguage) {
                    $oProductSizeTranslation                       = new CatalogProductSizeTranslation();
                    $oProductSizeTranslation->catalogProductSizeId = $oProductSize->catalogProductSizeId;
                    $oProductSizeTranslation->languageId           = $oLanguage->languageId;
                    $oProductSizeTranslation->name                 = $_POST['name'][$oLanguage->languageId];

                    if ($oProductSizeTranslation->isValid()) {
                        CatalogProductSizeTranslationManager::saveProductSizeTranslation($oProductSizeTranslation);
                    } else {
                        Debug::logError(
                            "",
                            "CatalogProductSize module php validate error",
                            __FILE__,
                            __LINE__,
                            "Tried to save CatalogProductSizeTranslation with wrong values despite javascript check.<br />" . _d($_POST, 1, 1),
                            Debug::LOG_IN_EMAIL
                        );
                    }
                }
            } else {
                foreach (AdminLocales::getLanguages() as $oLanguage) {
                    $oProductSizeTranslation                       = new CatalogProductSizeTranslation();
                    $oProductSizeTranslation->catalogProductSizeId = $oProductSize->catalogProductSizeId;
                    $oProductSizeTranslation->languageId           = $oLanguage->languageId;
                    $oProductSizeTranslation->name                 = http_post('name_same');

                    if ($oProductSizeTranslation->isValid()) {
                        CatalogProductSizeTranslationManager::saveProductSizeTranslation($oProductSizeTranslation);
                    } else {
                        Debug::logError(
                            "",
                            "CatalogProductSize module php validate error",
                            __FILE__,
                            __LINE__,
                            "Tried to save CatalogProductSizeTranslation with wrong values despite javascript check.<br />" . _d($_POST, 1, 1),
                            Debug::LOG_IN_EMAIL
                        );
                    }
                }
            }

            $_SESSION['statusUpdate'] = sysTranslations::get('catalog_size_saved'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oProductSize->catalogProductSizeId);
        } else {
            Debug::logError("", "CatalogProductSize module php validate error", __FILE__, __LINE__, "Tried to save CatalogProductSize with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $_SESSION['statusUpdate'] = sysTranslations::get('catalog_size_not_saved');
        }
    }

    $oPageLayout->sViewPath = getAdminView('productSizes/catalogProductSize_form', 'catalog');
} elseif (http_get('param1') == 'volgorde-wijzigen') {
    if (http_post('action') == 'saveOrder' && CSRFSynchronizerToken::validate()) {
        if (http_post('order')) {
            $aProductSizeIds = explode('|', http_post('order'));
            $iC              = 1;

            foreach ($aProductSizeIds AS $iProductSizeId) {
                $oProductSize        = CatalogProductSizeManager::getProductSizeById($iProductSizeId);
                $oProductSize->order = $iC;
                if ($oProductSize->isValid()) {
                    CatalogProductSizeManager::saveProductSize($oProductSize);
                }
                $iC++;
            }
        }
        $_SESSION['statusUpdate'] = sysTranslations::get('global_sequence_saved'); //save status update into session
        http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
    }

    // get all items for order changing
    $aProductSizes          = CatalogProductSizeManager::getProductSizesByFilter();
    $oPageLayout->sViewPath = getAdminView('productSizes/catalogProductSizes_change_order', 'catalog');
} # delete object
elseif (http_get("param1") == 'verwijderen' && is_numeric(http_get("param2"))) {
    if(CSRFSynchronizerToken::validate()) {
        if (is_numeric(http_get("param2"))) {
            $oProductSize = CatalogProductSizeManager::getProductSizeById(http_get("param2"));
        }

        if (!empty($oProductSize) && CatalogProductSizeManager::deleteProductSize($oProductSize)) {
            $_SESSION['statusUpdate'] = sysTranslations::get('catalog_size_deleted'); //save status update into session
        } else {
            $_SESSION['statusUpdate'] = sysTranslations::get('catalog_size_not_deleted'); //save status update into session
        }
    }
    http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
} # display overview
else {
    $aProductSizes          = CatalogProductSizeManager::getProductSizesByFilter();
    $oPageLayout->sViewPath = getAdminView('productSizes/catalogProductSizes_overview', 'catalog');
}

# include template
include_once getAdminView('layout');
?>