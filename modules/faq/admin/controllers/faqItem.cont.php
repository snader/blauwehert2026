<?php

# check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

# reset crop settings
unset($_SESSION['aCropSettings']);

global $oPageLayout;

# set page layout properties
$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = sysTranslations::get('global_FAQ');
$oPageLayout->sModuleName  = sysTranslations::get('global_FAQ');

# get status update from session
$oPageLayout->sStatusUpdate = http_session("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once
// handle perPage
if (http_post('setPerPage')) {
    $_SESSION['FAQPerPage'] = http_post('perPage');
}

// handle filter
$aFAQItemFilter = http_session('FAQItemFilter');
if (http_post('filterFAQItems')) {
    $aFAQItemFilter            = http_post('FAQItemFilter');
    $aFAQItemFilter['showAll'] = true; // manually set showAll to true
    $_SESSION['FAQItemFilter'] = $aFAQItemFilter;
}

if (http_post('resetFilter') || empty($aFAQItemFilter)) {
    unset($_SESSION['FAQItemFilter']);
    $aFAQItemFilter                      = [];
    $aFAQItemFilter['q']                 = '';
    $aFAQItemFilter['showAll']           = true; // manually set showAll to true
    $aFAQItemFilter['faqItemCategoryId'] = '';
}

# handle add/edit
if (http_get("param1") == 'bewerken' || http_get("param1") == 'toevoegen') {

    # set crop referrer for pages module
    $_SESSION['cropReferrer'] = ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . http_get("param2");

    if (http_get("param1") == 'bewerken' && is_numeric(http_get("param2"))) {
        $oFAQItem = FAQItemManager::getFAQItemById(http_get("param2"));
        if (!$oFAQItem) {
            http_redirect(ADMIN_FOLDER . "/");
        }
        # is editable?
        if (!$oFAQItem->isEditable()) {
            $_SESSION['statusUpdate'] = sysTranslations::get('faq_item_not_edited'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
        }
    } else {
        $oFAQItem             = new FAQItem();
        $oFAQItem->languageId = AdminLocales::language();
    }

    # action = save
    if (http_post("action") == 'save' && CSRFSynchronizerToken::validate()) {

        # load data in object
        $oFAQItem->_load($_POST);

        # set some properties after load, _load strips tags for all inputs
        $oFAQItem->question = convertAbsToRelLinks(http_post('question'));
        $oFAQItem->answer = convertAbsToRelLinks(http_post('answer'));

        $aFAQItemCategories = [];
        foreach (http_post('faqItemCategoryIds', []) AS $ifaqItemCategoryId) {
            $oFAQItemCategory = FAQItemCategoryManager::getFAQItemCategoryById($ifaqItemCategoryId);
            if (!$oFAQItemCategory) {
                continue;
            }
            $aFAQItemCategories[] = $oFAQItemCategory;
        }
        $oFAQItem->setCategories($aFAQItemCategories, 'all'); // set in list all for saving properly
        # if object is valid, save
        if ($oFAQItem->isValid()) {
            FAQItemManager::saveFAQItem($oFAQItem); //save FAQ item
            $_SESSION['statusUpdate'] = sysTranslations::get('faq_item_saved'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oFAQItem->faqItemId);
        } else {
            Debug::logError("", "Nieuws module php validate error", __FILE__, __LINE__, "Tried to save NieuwsItem with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $oPageLayout->sStatusUpdate = sysTranslations::get('faq_item_not_saved');
        }
    }

    $oPageLayout->sViewPath = getAdminView('faqItems/faqItem_form', 'faq');
} elseif (http_get("param1") == 'verwijderen' && is_numeric(http_get("param2"))) {
    if(CSRFSynchronizerToken::validate()) {
        if (is_numeric(http_get("param2"))) {
            $oFAQItem = FAQItemManager::getFAQItemById(http_get("param2"));
        }

        if ($oFAQItem && FAQItemManager::deleteFAQItem($oFAQItem)) {
            $_SESSION['statusUpdate'] = sysTranslations::get('faq_item_deleted'); //save status update into session
        } else {
            $_SESSION['statusUpdate'] = sysTranslations::get('faq_item_not_deleted'); //save status update into session
        }
    }
    http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
} elseif (http_get("param1") == 'ajax-setOnline') {
    if(!CSRFSynchronizerToken::validate()){
        die(json_encode(['status'=>false]));
    }
    $bOnline    = http_get("online", 0); //no value, set offline by default
    $bAjax      = http_get("ajax", false); //controller requested by ajax
    $ifaqItemId = http_get("param2");
    $oResObj    = new stdClass(); //standard class for json feedback
    # update online for page
    if (is_numeric($ifaqItemId)) {
        $oResObj->success   = FAQItemManager::updateOnlineByfaqItemId($bOnline, $ifaqItemId);
        $oResObj->faqItemId = $ifaqItemId;
        $oResObj->online    = $bOnline;
    }

    if (!$bAjax) {
        http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
    }
    print json_encode($oResObj);
    die;
} else {
    $iNrOfRecords = DBConnection::count('faq_items');
    $iPerPage     = http_session('FAQPerPage', 10);
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

    # add language to filter
    $aFAQItemFilter['languageId'] = AdminLocales::language();

    #display FAQ overview
    $aFAQItems              = FAQItemManager::getFAQItemsByFilter($aFAQItemFilter, $iPerPage, $iStart, $iFoundRows);
    $iPageCount             = !empty($iPerPage) ? (ceil($iFoundRows / $iPerPage)) : 0;
    $oPageLayout->sViewPath = getAdminView('faqItems/faqItems_overview', 'faq');
}

# inlcude template
include_once getAdminView('layout');
?>
