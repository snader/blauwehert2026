<?php

# check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

global $oPageLayout;

# set page layout properties
$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = sysTranslations::get('news_categories');
$oPageLayout->sModuleName  = sysTranslations::get('news_categories');

# get status update from session
$oPageLayout->sStatusUpdate = http_session("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once

# handle add/edit
if (http_get("param1") == 'bewerken' || http_get("param1") == 'toevoegen') {
    if (http_get("param1") == 'bewerken' && is_numeric(http_get("param2"))) {
        $oNewsItemCategory = NewsItemCategoryManager::getNewsItemCategoryById(http_get("param2"));
        if (empty($oNewsItemCategory)) {
            http_redirect(ADMIN_FOLDER . "/");
        }
    } else {
        $oNewsItemCategory = new NewsItemCategory();

        # Set languageId
        $oNewsItemCategory->languageId = AdminLocales::language();
    }

    # action = save
    if (http_post("action") == 'save' && CSRFSynchronizerToken::validate()) {
        # load data in object
        $oNewsItemCategory->_load($_POST);

        if ($oCurrentUser->isSEO()) {
            $oNewsItemCategory->setUrlPartText(http_post('urlPartText'));
        }

        # if object is valid, save
        if ($oNewsItemCategory->isValid()) {
            NewsItemCategoryManager::saveNewsItemCategory($oNewsItemCategory); //save object
            $_SESSION['statusUpdate'] = sysTranslations::get('news_category_saved'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oNewsItemCategory->newsItemCategoryId);
        } else {
            Debug::logError("", "NewsItemCategory module php validate error", __FILE__, __LINE__, "Tried to save NewsItemCategory with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $_SESSION['statusUpdate'] = sysTranslations::get('news_category_not_saved');
        }
    }

    $oPageLayout->sViewPath = getAdminView('newsItemCategories/newsItemCategory_form', 'newsItems');
} # set object online/offline
elseif (http_get("param1") == 'ajax-setOnline') {
    if(!CSRFSynchronizerToken::validate()){
        die(json_encode(['status'=>false]));
    }
    $bOnline             = http_get("online", 0); //no value, set offline by default
    $bAjax               = http_get("ajax", false); //controller requested by ajax
    $iNewsItemCategoryId = http_get("param2");
    $oResObj             = new stdClass(); //standard class for json feedback
    # update online for object
    if (is_numeric($iNewsItemCategoryId)) {
        $oResObj->success            = NewsItemCategoryManager::updateOnlineByNewsItemCategoryId($bOnline, $iNewsItemCategoryId);
        $oResObj->newsItemCategoryId = $iNewsItemCategoryId;
        $oResObj->online             = $bOnline;
    }

    # redirect to overview page if this isn't AJAX
    if (!$bAjax) {
        http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '');
    }

    die(json_encode($oResObj));
} # delete object
elseif (http_get("param1") == 'verwijderen' && is_numeric(http_get("param2"))) {
    if(CSRFSynchronizerToken::validate()) {
        if (is_numeric(http_get("param2"))) {
            $oNewsItemCategory = NewsItemCategoryManager::getNewsItemCategoryById(http_get("param2"));
        }
        if (!empty($oNewsItemCategory) && NewsItemCategoryManager::deleteNewsItemCategory($oNewsItemCategory)) {
            $_SESSION['statusUpdate'] = sysTranslations::get('news_category_deleted'); //save status update into session
        } else {
            $_SESSION['statusUpdate'] = sysTranslations::get('news_category_not_deleted'); //save status update into session
        }
    }
    http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
} elseif (http_get('param1') == 'volgorde-wijzigen') {

    if (http_post('action') == 'saveOrder' && CSRFSynchronizerToken::validate()) {
        if (http_post('order')) {
            $aNewsItemCategoryIds = explode('|', http_post('order'));
            $iC                   = 1;
            foreach ($aNewsItemCategoryIds AS $iNewsItemCategoryId) {
                $oNewsItemCategory        = NewsItemCategoryManager::getNewsItemCategoryById($iNewsItemCategoryId);
                $oNewsItemCategory->order = $iC;
                if ($oNewsItemCategory->isValid()) {
                    NewsItemCategoryManager::saveNewsItemCategory($oNewsItemCategory);
                }
                $iC++;
            }
        }
        $_SESSION['statusUpdate'] = sysTranslations::get('global_sequence_saved'); //save status update into session
        http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
    }

    // get all items for order changing
    $aNewsItemCategories    = NewsItemCategoryManager::getNewsItemCategoriesByFilter(['showAll' => true, 'languageId' => AdminLocales::language()]);
    $oPageLayout->sViewPath = getAdminView('newsItemCategories/newsItemCategories_change_order', 'newsItems');
} # display overview
else {
    $aNewsItemCategories    = NewsItemCategoryManager::getNewsItemCategoriesByFilter(['showAll' => true, 'languageId' => AdminLocales::language()]);
    $oPageLayout->sViewPath = getAdminView('newsItemCategories/newsItemCategories_overview', 'newsItems');
}

# include template
include_once getAdminView('layout');
?>