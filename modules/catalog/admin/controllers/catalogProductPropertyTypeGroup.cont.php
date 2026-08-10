<?php

# check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

global $oPageLayout;

# set page layout properties
$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = sysTranslations::get('catalog_product_type_property_group');
$oPageLayout->sModuleName  = sysTranslations::get('catalog_product_type_property_group');

# get status update from session
$oPageLayout->sStatusUpdate = http_session("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once
# handle productPropertyTypeGroupFilter
$aProductPropertyTypeGroupFilter = http_session('productPropertyTypeGroupFilter');
if (http_post('filterForm')) {
    $aProductPropertyTypeGroupFilter            = http_post('productPropertyTypeGroupFilter');
    $_SESSION['productPropertyTypeGroupFilter'] = $aProductPropertyTypeGroupFilter;
}

if (http_post('resetFilter') || empty($aProductPropertyTypeGroupFilter)) {
    unset($_SESSION['productPropertyTypeGroupFilter']);
    $aProductPropertyTypeGroupFilter                         = [];
    $aProductPropertyTypeGroupFilter['catalogProductTypeId'] = -1; // take a non existing catalogProductTypeId to force choosing one before any results are shown
}

# handle perPage
if (http_post('setPerPage')) {
    $_SESSION['productPropertyTypeGroupsPerPage'] = http_post('perPage');
}

# handle add/edit
if (http_get("param1") == 'bewerken' || http_get("param1") == 'toevoegen') {
    # set crop referrer for this module
    $_SESSION['cropReferrer'] = ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . http_get("param2");

    if (http_get("param1") == 'bewerken' && is_numeric(http_get("param2"))) {
        $oProductPropertyTypeGroup = CatalogProductPropertyTypeGroupManager::getProductPropertyTypeGroupById(http_get("param2"));
        if (empty($oProductPropertyTypeGroup)) {
            http_redirect(ADMIN_FOLDER . "/");
        }
    } else {
        $oProductPropertyTypeGroup = new CatalogProductPropertyTypeGroup();
    }

    # action = save
    if (http_post("action") == 'save' && CSRFSynchronizerToken::validate()) {

        # load data in object
        $oProductPropertyTypeGroup->_load($_POST);

        # if object is valid, save
        if ($oProductPropertyTypeGroup->isValid()) {
            CatalogProductPropertyTypeGroupManager::saveProductPropertyTypeGroup($oProductPropertyTypeGroup); //save object

            # save translations
            foreach (AdminLocales::getLanguages() as $oLanguage) {
                $oPropertyTypeGroupTranslation                                    = new CatalogProductPropertyTypeGroupTranslation();
                $oPropertyTypeGroupTranslation->catalogProductPropertyTypeGroupId = $oProductPropertyTypeGroup->catalogProductPropertyTypeGroupId;
                $oPropertyTypeGroupTranslation->languageId                        = $oLanguage->languageId;
                $oPropertyTypeGroupTranslation->title                             = $_POST['title'][$oLanguage->languageId];

                if ($oPropertyTypeGroupTranslation->isValid()) {
                    CatalogProductPropertyTypeGroupTranslationManager::saveProductPropertyTypeGroupTranslation($oPropertyTypeGroupTranslation);
                } else {
                    Debug::logError(
                        "",
                        "CatalogProductPropertyTypeGroup module php validate error",
                        __FILE__,
                        __LINE__,
                        "Tried to save CatalogProductPropertyTypeGroupTranslation with wrong values despite javascript check.<br />" . _d($_POST, 1, 1),
                        Debug::LOG_IN_EMAIL
                    );
                }
            }

            $_SESSION['statusUpdate'] = sysTranslations::get('catalog_property_group_saved'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oProductPropertyTypeGroup->catalogProductPropertyTypeGroupId);
        } else {
            Debug::logError(
                "",
                "CatalogProductPropertyTypeGroup module php validate error",
                __FILE__,
                __LINE__,
                "Tried to save CatalogProductPropertyTypeGroup with wrong values despite javascript check.<br />" . _d($_POST, 1, 1),
                Debug::LOG_IN_EMAIL
            );
            $_SESSION['statusUpdate'] = sysTranslations::get('catalog_property_group_not_saved');
        }
    }

    $oPageLayout->sViewPath = getAdminView('productPropertyTypeGroups/catalogProductPropertyTypeGroup_form', 'catalog');
} elseif (http_get('param1') == 'volgorde-wijzigen') {
    if (http_post('action') == 'saveOrder' && CSRFSynchronizerToken::validate()) {
        if (http_post('order')) {
            $aProductPropertyTypeGroupIds = explode('|', http_post('order'));
            $iC                           = 1;
            foreach ($aProductPropertyTypeGroupIds AS $iProductPropertyTypeGroupId) {
                $oProductPropertyTypeGroup        = CatalogProductPropertyTypeGroupManager::getProductPropertyTypeGroupById($iProductPropertyTypeGroupId);
                $oProductPropertyTypeGroup->order = $iC;
                if ($oProductPropertyTypeGroup->isValid()) {
                    CatalogProductPropertyTypeGroupManager::saveProductPropertyTypeGroup($oProductPropertyTypeGroup);
                }
                $iC++;
            }
        }
        $_SESSION['statusUpdate'] = sysTranslations::get('global_sequence_saved'); //save status update into session
        http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
    }

    // get all items for order changing
    $aProductPropertyTypeGroups = CatalogProductPropertyTypeGroupManager::getProductPropertyTypeGroupsByFilter($aProductPropertyTypeGroupFilter);

    $oPageLayout->sViewPath = getAdminView('productPropertyTypeGroups/catalogProductPropertyTypeGroups_change_order', 'catalog');
} # delete object
elseif (http_get("param1") == 'verwijderen' && is_numeric(http_get("param2"))) {
    if(CSRFSynchronizerToken::validate()) {
        if (is_numeric(http_get("param2"))) {
            $oProductPropertyTypeGroup = CatalogProductPropertyTypeGroupManager::getProductPropertyTypeGroupById(http_get("param2"));
        }

        if (!empty($oProductPropertyTypeGroup) && CatalogProductPropertyTypeGroupManager::deleteProductPropertyTypeGroup($oProductPropertyTypeGroup)) {
            $_SESSION['statusUpdate'] = sysTranslations::get('catalog_property_group_deleted'); //save status update into session
        } else {
            $_SESSION['statusUpdate'] = sysTranslations::get('catalog_property_group_not_deleted'); //save status update into session
        }
    }
    http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
} # display overview
else {
    $iNrOfRecords = DBConnection::count('catalog_product_property_type_groups');
    $iPerPage     = http_session('productPropertyTypeGroupsPerPage', 10);
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

    $aProductPropertyTypeGroups = CatalogProductPropertyTypeGroupManager::getProductPropertyTypeGroupsByFilter($aProductPropertyTypeGroupFilter, $iPerPage, $iStart, $iFoundRows);
    $iPageCount                 = !empty($iPerPage) ? (ceil($iFoundRows / $iPerPage)) : 0;

    $oPageLayout->sViewPath = getAdminView('productPropertyTypeGroups/catalogProductPropertyTypeGroups_overview', 'catalog');
}

# include template
include_once getAdminView('layout');
?>