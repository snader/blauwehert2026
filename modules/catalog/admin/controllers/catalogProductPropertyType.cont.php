<?php

# check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

global $oPageLayout;

# set page layout properties
$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = sysTranslations::get('catalog_product_type_properties');
$oPageLayout->sModuleName  = sysTranslations::get('catalog_product_type_properties');

# get status update from session
$oPageLayout->sStatusUpdate = http_session("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once
# handle productPropertyTypeFilter
$aProductPropertyTypeFilter = http_session('productPropertyTypeFilter');
if (http_post('filterForm')) {
    $aProductPropertyTypeFilter            = http_post('productPropertyTypeFilter');
    $_SESSION['productPropertyTypeFilter'] = $aProductPropertyTypeFilter;
}

if (http_post('resetFilter') || empty($aProductPropertyTypeFilter)) {
    unset($_SESSION['productPropertyTypeFilter']);
    $aProductPropertyTypeFilter                                      = [];
    $aProductPropertyTypeFilter['catalogProductTypeId']              = -1; // take a non existing catalogProductTypeId to force choosing one before any results are shown
    $aProductPropertyTypeFilter['catalogProductPropertyTypeGroupId'] = -1; // take a non existing catalogProductTypeGroupId to force choosing one before any results are shown
}

# handle perPage
if (http_post('setPerPage')) {
    $_SESSION['productPropertyTypesPerPage'] = http_post('perPage');
}

# handle add/edit
if (http_get("param1") == 'bewerken' || http_get("param1") == 'toevoegen') {
    # set crop referrer for this module
    $_SESSION['cropReferrer'] = ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . http_get("param2");

    if (http_get("param1") == 'bewerken' && is_numeric(http_get("param2"))) {
        $oProductPropertyType = CatalogProductPropertyTypeManager::getProductPropertyTypeById(http_get("param2"));
        if (empty($oProductPropertyType)) {
            http_redirect(ADMIN_FOLDER . "/");
        }
    } else {
        $oProductPropertyType = new CatalogProductPropertyType();
    }

    # action = save
    if (http_post("action") == 'save' && CSRFSynchronizerToken::validate()) {
        # load data in object
        $oProductPropertyType->_load($_POST);

        # if object is valid, save
        if ($oProductPropertyType->isValid()) {
            CatalogProductPropertyTypeManager::saveProductPropertyType($oProductPropertyType); //save object
            # save translations
            foreach (AdminLocales::getLanguages() as $oLanguage) {
                $oPropertyTypeTranslation                               = new CatalogProductPropertyTypeTranslation();
                $oPropertyTypeTranslation->catalogProductPropertyTypeId = $oProductPropertyType->catalogProductPropertyTypeId;
                $oPropertyTypeTranslation->languageId                   = $oLanguage->languageId;
                $oPropertyTypeTranslation->title                        = $_POST['title'][$oLanguage->languageId];

                if ($oPropertyTypeTranslation->isValid()) {
                    CatalogProductPropertyTypeTranslationManager::saveProductPropertyTypeTranslation($oPropertyTypeTranslation);
                } else {
                    Debug::logError(
                        "",
                        "CatalogProductPropertyType module php validate error",
                        __FILE__,
                        __LINE__,
                        "Tried to save CatalogProductPropertyTypeTranslation with wrong values despite javascript check.<br />" . _d($_POST, 1, 1),
                        Debug::LOG_IN_EMAIL
                    );
                }
            }

            $_SESSION['statusUpdate'] = sysTranslations::get('catalog_product_properties_saved'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oProductPropertyType->catalogProductPropertyTypeId);
        } else {
            Debug::logError(
                "",
                "CatalogProductPropertyType module php validate error",
                __FILE__,
                __LINE__,
                "Tried to save CatalogProductPropertyType with wrong values despite javascript check.<br />" . _d($_POST, 1, 1),
                Debug::LOG_IN_EMAIL
            );
            $_SESSION['statusUpdate'] = sysTranslations::get('catalog_product_properties_not_saved');
        }
    }

    # save possible value
    if (http_post("action") == 'savePossibleValue' && CSRFSynchronizerToken::validate()) {

        $oProductPropertyTypePossibleValue = new CatalogProductPropertyTypePossibleValue();

        # load data in object
        $oProductPropertyTypePossibleValue->_load($_POST);

        if ($oProductPropertyTypePossibleValue->isValid()) {
            CatalogProductPropertyTypePossibleValueManager::saveProductPropertyTypePossibleValue($oProductPropertyTypePossibleValue);

            # save translations
            if (http_post('multilingual') == 1) {
                foreach (AdminLocales::getLanguages() as $oLanguage) {
                    $oPropertyTypePossibleValueTranslation                                            = new CatalogProductPropertyTypePossibleValueTranslation();
                    $oPropertyTypePossibleValueTranslation->catalogProductPropertyTypePossibleValueId = $oProductPropertyTypePossibleValue->catalogProductPropertyTypePossibleValueId;
                    $oPropertyTypePossibleValueTranslation->languageId                                = $oLanguage->languageId;
                    $oPropertyTypePossibleValueTranslation->value                                     = $_POST['value'][$oLanguage->languageId];

                    if ($oPropertyTypePossibleValueTranslation->isValid()) {
                        CatalogProductPropertyTypePossibleValueTranslationManager::saveProductPropertyTypePossibleValueTranslation($oPropertyTypePossibleValueTranslation);
                    } else {
                        Debug::logError(
                            "",
                            "CatalogProductPropertyType module php validate error",
                            __FILE__,
                            __LINE__,
                            "Tried to save CatalogProductPropertyTypePossibleValueTranslation with wrong values despite javascript check.<br />" . _d($_POST, 1, 1),
                            Debug::LOG_IN_EMAIL
                        );
                    }
                }
            } else {
                foreach (AdminLocales::getLanguages() as $oLanguage) {
                    $oPropertyTypePossibleValueTranslation                                            = new CatalogProductPropertyTypePossibleValueTranslation();
                    $oPropertyTypePossibleValueTranslation->catalogProductPropertyTypePossibleValueId = $oProductPropertyTypePossibleValue->catalogProductPropertyTypePossibleValueId;
                    $oPropertyTypePossibleValueTranslation->languageId                                = $oLanguage->languageId;
                    $oPropertyTypePossibleValueTranslation->value                                     = http_post('value_same');

                    if ($oPropertyTypePossibleValueTranslation->isValid()) {
                        CatalogProductPropertyTypePossibleValueTranslationManager::saveProductPropertyTypePossibleValueTranslation($oPropertyTypePossibleValueTranslation);
                    } else {
                        Debug::logError(
                            "",
                            "CatalogProductPropertyType module php validate error",
                            __FILE__,
                            __LINE__,
                            "Tried to save CatalogProductPropertyTypePossibleValueTranslation with wrong values despite javascript check.<br />" . _d($_POST, 1, 1),
                            Debug::LOG_IN_EMAIL
                        );
                    }
                }
            }

            $_SESSION['statusUpdate'] = sysTranslations::get('catalog_property_value_saved'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oProductPropertyType->catalogProductPropertyTypeId);
        } else {
            Debug::logError(
                "",
                "CatalogProductPropertyTypePossibleValue module php validate error",
                __FILE__,
                __LINE__,
                "Tried to save CatalogProductPropertyTypePossibleValue with wrong values despite javascript check.<br />" . _d($_POST, 1, 1),
                Debug::LOG_IN_EMAIL
            );
            $_SESSION['statusUpdate'] = sysTranslations::get('catalog_property_value_not_saved');
        }
    }

    $iProductTypeId = $oProductPropertyType->getPropertyTypeGroup() ? $oProductPropertyType->getPropertyTypeGroup()->catalogProductTypeId : null;
    $aProductTypes  = CatalogProductTypeManager::getAllProductTypes();

    $oPageLayout->sViewPath = getAdminView('productPropertyTypes/catalogProductPropertyType_form', 'catalog');
} elseif (http_get('param1') == 'volgorde-wijzigen') {
    if (http_post('action') == 'saveOrder' && CSRFSynchronizerToken::validate()) {
        if (http_post('order')) {
            $aProductPropertyTypeIds = explode('|', http_post('order'));
            $iC                      = 1;
            foreach ($aProductPropertyTypeIds AS $iProductPropertyTypeId) {
                $oProductPropertyType        = CatalogProductPropertyTypeManager::getProductPropertyTypeById($iProductPropertyTypeId);
                $oProductPropertyType->order = $iC;
                if ($oProductPropertyType->isValid()) {
                    CatalogProductPropertyTypeManager::saveProductPropertyType($oProductPropertyType);
                }
                $iC++;
            }
        }
        $_SESSION['statusUpdate'] = sysTranslations::get('global_sequence_saved'); //save status update into session
        http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
    }

    // get all items for order changing
    $aProductPropertyTypes = CatalogProductPropertyTypeManager::getProductPropertyTypesByFilter($aProductPropertyTypeFilter);

    $oPageLayout->sViewPath = getAdminView('productPropertyTypes/catalogProductPropertyTypes_change_order', 'catalog');
} # delete object
elseif (http_get("param1") == 'verwijderen' && is_numeric(http_get("param2"))) {
    if(CSRFSynchronizerToken::validate()) {
        if (is_numeric(http_get("param2"))) {
            $oProductPropertyType = CatalogProductPropertyTypeManager::getProductPropertyTypeById(http_get("param2"));
        }

        if (!empty($oProductPropertyType) && CatalogProductPropertyTypeManager::deleteProductPropertyType($oProductPropertyType)) {
            $_SESSION['statusUpdate'] = sysTranslations::get('catalog_product_property_deleted'); //save status update into session
        } else {
            $_SESSION['statusUpdate'] = sysTranslations::get('catalog_product_property_not_deleted'); //save status update into session
        }
    }
    http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
} # delete a CatalogProductPropertyTypePossibleValue
elseif (http_get("param1") == 'ajax-deletePossibleValue') {
    if(!CSRFSynchronizerToken::validate()){
        die(json_encode(['status'=>false]));
    }
    $oResObj          = new stdClass();
    $oResObj->success = false;

    $iProductPropertyTypePossibleValueId = http_post('catalogProductPropertyTypePossibleValueId');

    # check for the required POST values
    if (is_numeric($iProductPropertyTypePossibleValueId)) {
        $oProductPropertyTypePossibleValue = CatalogProductPropertyTypePossibleValueManager::getProductPropertyTypePossibleValueById($iProductPropertyTypePossibleValueId);
        if ($oProductPropertyTypePossibleValue) {
            if (CatalogProductPropertyTypePossibleValueManager::deleteProductPropertyTypePossibleValue($oProductPropertyTypePossibleValue)) {
                $oResObj->catalogProductPropertyTypePossibleValueId = $iProductPropertyTypePossibleValueId;
                $oResObj->success                                   = true;
            }
        }
    }

    die(json_encode($oResObj));
} # edit a CatalogOpeningHours
elseif (http_get("param1") == 'ajax-editPossibleValue') {
    if(!CSRFSynchronizerToken::validate()){
        die(json_encode(['status'=>false]));
    }
    $oResObj          = new stdClass();
    $oResObj->success = false;

    //!empty($sValue) &&
    if (is_numeric(http_post('catalogProductPropertyTypePossibleValueId'))) {
        $oProductPropertyTypePossibleValue = CatalogProductPropertyTypePossibleValueManager::getProductPropertyTypePossibleValueById(http_post('catalogProductPropertyTypePossibleValueId'));

        $sStartTag = 'value_';

        foreach (AdminLocales::getLanguages() as $oLanguage) {

            $oNewPossibleValueTranslation                                            = new CatalogProductPropertyTypePossibleValueTranslation();
            $oNewPossibleValueTranslation->catalogProductPropertyTypePossibleValueId = http_post('catalogProductPropertyTypePossibleValueId');
            $oNewPossibleValueTranslation->languageId                                = $oLanguage->languageId;
            $oNewPossibleValueTranslation->value                                     = http_post('value_' . $oLanguage->languageId);
            CatalogProductPropertyTypePossibleValueTranslationManager::saveProductPropertyTypePossibleValueTranslation($oNewPossibleValueTranslation);

            $sTag             = $sStartTag . $oLanguage->languageId;
            $oResObj->{$sTag} = http_post('value_' . $oLanguage->languageId);
        }

        $oResObj->catalogProductPropertyTypePossibleValueId = http_post('catalogProductPropertyTypePossibleValueId');
        $oResObj->success                                   = true;
    }

    die(json_encode($oResObj));
} # re-order the CatalogOpeningHours objects
elseif (http_get("param1") == 'ajax-savePossibleValueOrder') {
    if(!CSRFSynchronizerToken::validate()){
        die(json_encode(['status'=>false]));
    }
    $oResObj          = new stdClass();
    $oResObj->success = false;

    $sProductPropertyTypePossibleValueIds = http_post("catalogProductPropertyTypePossibleValueIds");
    if (!empty($sProductPropertyTypePossibleValueIds)) {
        $aProductPropertyTypePossibleValueIds = explode(",", $sProductPropertyTypePossibleValueIds); // explode ids to array
        foreach ($aProductPropertyTypePossibleValueIds as $iC => $iProductPropertyTypePossibleValueId) {
            # get each object, update and save
            $oProductPropertyTypePossibleValue        = CatalogProductPropertyTypePossibleValueManager::getProductPropertyTypePossibleValueById($iProductPropertyTypePossibleValueId);
            $oProductPropertyTypePossibleValue->order = $iC;
            CatalogProductPropertyTypePossibleValueManager::saveProductPropertyTypePossibleValue($oProductPropertyTypePossibleValue);
        }
        $oResObj->success = true;
    }

    die(json_encode($oResObj));
} # display overview
else {
    $iNrOfRecords = DBConnection::count('catalog_product_property_types');
    $iPerPage     = http_session('productPropertyTypesPerPage', 10);
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

    $aProductTypes         = CatalogProductTypeManager::getAllProductTypes();
    $aProductPropertyTypes = CatalogProductPropertyTypeManager::getProductPropertyTypesByFilter($aProductPropertyTypeFilter, $iPerPage, $iStart, $iFoundRows);
    $iPageCount            = !empty($iPerPage) ? (ceil($iFoundRows / $iPerPage)) : 0;

    $oPageLayout->sViewPath = getAdminView('productPropertyTypes/catalogProductPropertyTypes_overview', 'catalog');
}

# include stylesheet
$oPageLayout->addStylesheet('<link rel="stylesheet" href="' . getAdminCss('catalogProductPropertyType_style') . '" />');

# include template
include_once getAdminView('layout');
?>