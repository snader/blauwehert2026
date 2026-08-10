<?php

# check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

global $oPageLayout;

# set page layout properties
$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = sysTranslations::get('catalog_product_types');
$oPageLayout->sModuleName  = sysTranslations::get('catalog_product_types');

# get status update from session
$oPageLayout->sStatusUpdate = http_session("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once
# handle add/edit
if (http_get("param1") == 'bewerken' || http_get("param1") == 'toevoegen') {
    # set crop referrer for this module
    $_SESSION['cropReferrer'] = ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . http_get("param2");

    if (http_get("param1") == 'bewerken' && is_numeric(http_get("param2"))) {
        $oProductType = CatalogProductTypeManager::getProductTypeById(http_get("param2"));
        if (empty($oProductType)) {
            http_redirect(ADMIN_FOLDER . "/");
        }
    } else {
        $oProductType = new CatalogProductType();
    }

    # action = save
    if (http_post("action") == 'save' && CSRFSynchronizerToken::validate()) {
        # load data in object
        $oProductType->_load($_POST);

        # if object is valid, save
        if ($oProductType->isValid()) {
            CatalogProductTypeManager::saveProductType($oProductType); //save object

            # save translations
            foreach (AdminLocales::getLanguages() as $oLanguage) {
                $oProductTypeTranslation                       = new CatalogProductTypeTranslation();
                $oProductTypeTranslation->catalogProductTypeId = $oProductType->catalogProductTypeId;
                $oProductTypeTranslation->languageId           = $oLanguage->languageId;
                $oProductTypeTranslation->title                = $_POST['title'][$oLanguage->languageId];

                if ($oProductTypeTranslation->isValid()) {
                    CatalogProductTypeTranslationManager::saveProductTypeTranslation($oProductTypeTranslation);
                } else {
                    Debug::logError(
                        "",
                        "CatalogProductType module php validate error",
                        __FILE__,
                        __LINE__,
                        "Tried to save CatalogProductTypeTranslation with wrong values despite javascript check.<br />" . _d($_POST, 1, 1),
                        Debug::LOG_IN_EMAIL
                    );
                }
            }

            $_SESSION['statusUpdate'] = sysTranslations::get('catalog_product_type_saved'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oProductType->catalogProductTypeId);
        } else {
            Debug::logError("", "CatalogProductType module php validate error", __FILE__, __LINE__, "Tried to save CatalogProductType with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $_SESSION['statusUpdate'] = sysTranslations::get('catalog_product_type_not_saved');
        }
    }

    $oPageLayout->sViewPath = getAdminView('productTypes/catalogProductType_form', 'catalog');
} elseif (http_get('param1') == 'volgorde-wijzigen') {
    if (http_post('action') == 'saveOrder' && CSRFSynchronizerToken::validate()) {
        if (http_post('order')) {
            $aProductTypeIds = explode('|', http_post('order'));
            $iC              = 1;
            foreach ($aProductTypeIds AS $iProductTypeId) {
                $oProductType        = CatalogProductTypeManager::getProductTypeById($iProductTypeId);
                $oProductType->order = $iC;
                if ($oProductType->isValid()) {
                    CatalogProductTypeManager::saveProductType($oProductType);
                }
                $iC++;
            }
        }
        $_SESSION['statusUpdate'] = sysTranslations::get('global_sequence_saved'); //save status update into session
        http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
    }

    // get all items for order changing
    $aAllProductTypes       = CatalogProductTypeManager::getAllProductTypes();
    $oPageLayout->sViewPath = getAdminView('productTypes/catalogProductTypes_change_order', 'catalog');
} # delete object
elseif (http_get("param1") == 'verwijderen' && is_numeric(http_get("param2"))) {
    if(CSRFSynchronizerToken::validate()) {
        if (is_numeric(http_get("param2"))) {
            $oProductType = CatalogProductTypeManager::getProductTypeById(http_get("param2"));
        }

        if (!empty($oProductType) && CatalogProductTypeManager::deleteProductType($oProductType)) {
            $_SESSION['statusUpdate'] = sysTranslations::get('catalog_product_type_deleted'); //save status update into session
        } else {
            $_SESSION['statusUpdate'] = sysTranslations::get('catalog_product_type_not_deleted'); //save status update into session
        }
    }
    http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
} # display overview
else {
    $aAllProductTypes       = CatalogProductTypeManager::getAllProductTypes();
    $oPageLayout->sViewPath = getAdminView('productTypes/catalogProductTypes_overview', 'catalog');
}

# include template
include_once getAdminView('layout');
?>