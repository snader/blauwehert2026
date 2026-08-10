<?php

// check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

// reset crop settings
unset($_SESSION['aCropSettings']);

// set page layout properties
$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = "Dynamic Content";
$oPageLayout->sModuleName  = "dynamicContent";

// get status update from session
$oPageLayout->sStatusUpdate = http_session("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once

// handle add/edit
if (http_get("param1") == 'bewerken' || http_get("param1") == 'toevoegen') {

    if (http_get("param1") == 'bewerken' && is_numeric(http_get("param2"))) {
        $oDynamicContent = DynamicContentManager::getDynamicContentById(http_get("param2"));
        if (empty($oDynamicContent)) {
            http_redirect(ADMIN_FOLDER . "/");
        }
    } else {
        $oDynamicContent             = new DynamicContent();
        $oDynamicContent->languageId = AdminLocales::language();
    }

    if ($oDynamicContent->adminOnly && !$oCurrentUser->isAdmin()) {
        http_redirect(ADMIN_FOLDER . "/");
    }

    // action = save
    if (http_post("action") == 'save' && CSRFSynchronizerToken::validate()) {
        // load data in object
        $oDynamicContent->_load($_POST);

        $oDynamicContent->name = prettyUrlPart(http_post("name"));
        if ($oDynamicContent->type == "html" || $oDynamicContent->type == "code") {
            $oDynamicContent->content = convertAbsToRelLinks(http_post('content'));

        }

        // if object is valid, save
        if ($oDynamicContent->isValid()) {
            DynamicContentManager::saveDynamicContent($oDynamicContent); //save object
            $_SESSION['statusUpdate'] = sysTranslations::get('dynamiccontent_saved'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oDynamicContent->dynamicContentId);
        } else {
            Debug::logError("", "DynamicContent module php validate error", __FILE__, __LINE__, "Tried to save DynamicContent with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $_SESSION['statusUpdate'] = sysTranslations::get('dynamiccontent_not_saved');
        }
    }

    $oPageLayout->sViewPath = getAdminView('dynamicContent_form', 'dynamicContent');
} // set object online/offline
elseif (http_get("param1") == 'ajax-setOnline') {
    if(!CSRFSynchronizerToken::validate()){
        die(json_encode(['status'=>false]));
    }
    $bOnline           = http_get("online", 0); //no value, set offline by default
    $bAjax             = http_get("ajax", false); //controller requested by ajax
    $iDynamicContentId = http_get("param2");
    $oResObj           = new stdClass(); //standard class for json feedback
    // update online for object
    if (is_numeric($iDynamicContentId)) {
        $oResObj->success          = DynamicContentManager::updateOnlineByDynamicContentId($bOnline, $iDynamicContentId);
        $oResObj->dynamicContentId = $iDynamicContentId;
        $oResObj->online           = $bOnline;
    }

    // redirect to overview page if this isn't AJAX
    if (!$bAjax) {
        http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '');
    }

    die(json_encode($oResObj));
} // delete object
elseif (http_get("param1") == 'verwijderen' && is_numeric(http_get("param2")) && $oCurrentUser->isAdmin()) {
    if(CSRFSynchronizerToken::validate()) {
        if (is_numeric(http_get("param2"))) {
            $oDynamicContent = DynamicContentManager::getDynamicContentById(http_get("param2"));
        }

        if (!empty($oDynamicContent) && DynamicContentManager::deleteDynamicContent($oDynamicContent)) {
            $_SESSION['statusUpdate'] = sysTranslations::get('dynamiccontent_deleted'); //save status update into session
        } else {
            $_SESSION['statusUpdate'] = sysTranslations::get('dynamiccontent_not_deleted'); //save status update into session
        }
    }
    http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
} // display overview
else {
    $aAllDynamicContents    = DynamicContentManager::getDynamicContentByFilter(['showAll' => true, 'languageId' => AdminLocales::language()]);
    $oPageLayout->sViewPath = getAdminView('dynamicContent_overview', 'dynamicContent');
}

// include template
include_once getAdminView('layout');
