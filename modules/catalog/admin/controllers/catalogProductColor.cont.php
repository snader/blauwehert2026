<?php

# check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

global $oPageLayout;

# set page layout properties
$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = sysTranslations::get('global_color');
$oPageLayout->sModuleName  = sysTranslations::get('global_color');

# get status update from session
$oPageLayout->sStatusUpdate = http_session("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once
# handle add/edit
if (http_get("param1") == 'bewerken' || http_get("param1") == 'toevoegen') {
    # set crop referrer for this module
    $_SESSION['cropReferrer'] = ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . http_get("param2");

    if (http_get("param1") == 'bewerken' && is_numeric(http_get("param2"))) {
        $oProductColor = CatalogProductColorManager::getProductColorById(http_get("param2"));
        if (empty($oProductColor)) {
            http_redirect(ADMIN_FOLDER . "/");
        }
    } else {
        $oProductColor = new CatalogProductColor();
    }

    # action = save
    if (http_post("action") == 'save' && CSRFSynchronizerToken::validate()) {
        # load data in object
        $oProductColor->_load($_POST);

        # if object is valid, save
        if ($oProductColor->isValid()) {
            CatalogProductColorManager::saveProductColor($oProductColor); //save object
            # save translations
            foreach (AdminLocales::getLanguages() as $oLanguage) {
                $oProductColorTranslation                        = new CatalogProductColorTranslation();
                $oProductColorTranslation->catalogProductColorId = $oProductColor->catalogProductColorId;
                $oProductColorTranslation->languageId            = $oLanguage->languageId;
                $oProductColorTranslation->name                  = $_POST['name'][$oLanguage->languageId];

                if ($oProductColorTranslation->isValid()) {
                    CatalogProductColorTranslationManager::saveProductColorTranslation($oProductColorTranslation);
                } else {
                    Debug::logError(
                        "",
                        "CatalogProductColor module php validate error",
                        __FILE__,
                        __LINE__,
                        "Tried to save CatalogProductColorTranslation with wrong values despite javascript check.<br />" . _d($_POST, 1, 1),
                        Debug::LOG_IN_EMAIL
                    );
                }
            }

            $_SESSION['statusUpdate'] = sysTranslations::get('catalog_color_saved'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oProductColor->catalogProductColorId);
        } else {
            Debug::logError("", "CatalogProductColor module php validate error", __FILE__, __LINE__, "Tried to save CatalogProductColor with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $_SESSION['statusUpdate'] = sysTranslations::get('catalog_color_not_saved');
        }
    }

    $oPageLayout->sViewPath = getAdminView('productColors/catalogProductColor_form', 'catalog');
} elseif (http_get('param1') == 'volgorde-wijzigen') {
    if (http_post('action') == 'saveOrder' && CSRFSynchronizerToken::validate()) {
        if (http_post('order')) {
            $aProductColorIds = explode('|', http_post('order'));
            $iC               = 1;
            foreach ($aProductColorIds AS $iProductColorId) {
                $oProductColor        = CatalogProductColorManager::getProductColorById($iProductColorId);
                $oProductColor->order = $iC;
                if ($oProductColor->isValid()) {
                    CatalogProductColorManager::saveProductColor($oProductColor);
                }
                $iC++;
            }
        }
        $_SESSION['statusUpdate'] = sysTranslations::get('global_sequence_saved'); //save status update into session
        http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
    }

    // get all items for order changing
    $aProductColors         = CatalogProductColorManager::getProductColorsByFilter();
    $oPageLayout->sViewPath = getAdminView('productColors/catalogProductColors_change_order', 'catalog');
} # delete object
elseif (http_get("param1") == 'verwijderen' && is_numeric(http_get("param2"))) {
    if(CSRFSynchronizerToken::validate()) {
        if (is_numeric(http_get("param2"))) {
            $oProductColor = CatalogProductColorManager::getProductColorById(http_get("param2"));
        }

        if (!empty($oProductColor) && CatalogProductColorManager::deleteProductColor($oProductColor)) {
            $_SESSION['statusUpdate'] = sysTranslations::get('catalog_color_deleted'); //save status update into session
        } else {
            $_SESSION['statusUpdate'] = sysTranslations::get('catalog_color_not_deleted'); //save status update into session
        }
    }
    http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
} # display overview
else {
    $aProductColors         = CatalogProductColorManager::getProductColorsByFilter();
    $oPageLayout->sViewPath = getAdminView('productColors/catalogProductColors_overview', 'catalog');
}

# include template
include_once getAdminView('layout');
?>