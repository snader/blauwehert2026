<?php

# check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

global $oPageLayout;

# set page layout properties
$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = sysTranslations::get('faq_categories');
$oPageLayout->sModuleName  = sysTranslations::get('faq_categories');

# get status update from session
$oPageLayout->sStatusUpdate = http_session("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once

# handle add/edit
if (http_get("param1") == 'bewerken' || http_get("param1") == 'toevoegen') {
    if (http_get("param1") == 'bewerken' && is_numeric(http_get("param2"))) {
        $oFAQItemCategory = FAQItemCategoryManager::getFAQItemCategoryById(http_get("param2"));
        if (empty($oFAQItemCategory)) {
            http_redirect(ADMIN_FOLDER . "/");
        }
    } else {
        $oFAQItemCategory = new FAQItemCategory();

        # Set languageId
        $oFAQItemCategory->languageId = AdminLocales::language();
    }

    # action = save
    if (http_post("action") == 'save' && CSRFSynchronizerToken::validate()) {
        # load data in object
        $oFAQItemCategory->_load($_POST);

        # if object is valid, save
        if ($oFAQItemCategory->isValid()) {
            FAQItemCategoryManager::saveFAQItemCategory($oFAQItemCategory); //save object
            $_SESSION['statusUpdate'] = sysTranslations::get('faq_category_saved'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oFAQItemCategory->faqItemCategoryId);
        } else {
            Debug::logError("", "FAQItemCategory module php validate error", __FILE__, __LINE__, "Tried to save FAQItemCategory with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $_SESSION['statusUpdate'] = sysTranslations::get('faq_category_not_saved');
        }
    }

    $oPageLayout->sViewPath = getAdminView('faqItemCategories/faqItemCategory_form', 'faq');
} # set object online/offline
elseif (http_get('param1') == 'faq-volgorde-bewerken') {
    if (http_post("action") == 'saveOrder' && CSRFSynchronizerToken::validate()) {

        if (http_post('order')) {
            $aFaqItemIds      = explode('|', http_post('order'));
            $oFAQItemCategory = FAQItemCategoryManager::getFAQItemCategoryById(http_get("param2"));
            if (!empty($oFAQItemCategory)) {
                $iFaqItemOrder = 1;
                foreach ($aFaqItemIds AS $iFaqItemId) {
                    if (is_numeric($iFaqItemId)) {
                        $oFAQItem = FAQItemManager::getFAQItemById($iFaqItemId);
                        if (!empty($oFAQItem)) {
                            if ($oFAQItem->isValid()) {
                                FAQItemCategoryManager::saveFaqItemCategoryOrder($iFaqItemId, $oFAQItemCategory->faqItemCategoryId, $iFaqItemOrder);
                                $iFaqItemOrder++;
                            }
                        }
                    }
                }
            }
        }
        $_SESSION['statusUpdate'] = sysTranslations::get('faq_items_order_saved'); //save status update into session
        http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
    }

    if (!is_numeric(http_get("param2"))) {
        http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
    }

    if (is_numeric(http_get("param2"))) {
        $oFAQItemCategory = FAQItemCategoryManager::getFAQItemCategoryById(http_get("param2"));
        if (!$oFAQItemCategory) {
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
        }
        $aFAQItems        = $oFAQItemCategory->getFAQItems('all');
    }


    $oPageLayout->sViewPath = getAdminView('faqItemCategories/faqItems_change_order', 'faq');
}
elseif (http_get("param1") == 'ajax-setOnline') {
    if(!CSRFSynchronizerToken::validate()){
        die(json_encode(['status'=>false]));
    }
    $bOnline            = http_get("online", 0); //no value, set offline by default
    $bAjax              = http_get("ajax", false); //controller requested by ajax
    $ifaqItemCategoryId = http_get("param2");
    $oResObj            = new stdClass(); //standard class for json feedback
    # update online for object
    if (is_numeric($ifaqItemCategoryId)) {
        $oResObj->success           = FAQItemCategoryManager::updateOnlineByfaqItemCategoryId($bOnline, $ifaqItemCategoryId);
        $oResObj->faqItemCategoryId = $ifaqItemCategoryId;
        $oResObj->online            = $bOnline;
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
            $oFAQItemCategory = FAQItemCategoryManager::getFAQItemCategoryById(http_get("param2"));
        }
        if (!empty($oFAQItemCategory) && FAQItemCategoryManager::deleteFAQItemCategory($oFAQItemCategory)) {
            $_SESSION['statusUpdate'] = sysTranslations::get('faq_category_deleted'); //save status update into session
        } else {
            $_SESSION['statusUpdate'] = sysTranslations::get('faq_category_not_deleted'); //save status update into session
        }
    }
    http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
} elseif (http_get('param1') == 'volgorde-wijzigen') {
    if (http_post('action') == 'saveOrder' && CSRFSynchronizerToken::validate()) {
        if (http_post('order')) {
            $afaqItemCategoryIds = explode('|', http_post('order'));
            $iC                  = 1;
            foreach ($afaqItemCategoryIds AS $ifaqItemCategoryId) {
                $oFAQItemCategory        = FAQItemCategoryManager::getFAQItemCategoryById($ifaqItemCategoryId);
                $oFAQItemCategory->order = $iC;
                if ($oFAQItemCategory->isValid()) {
                    FAQItemCategoryManager::saveFAQItemCategory($oFAQItemCategory);
                }
                $iC++;
            }
        }
        $_SESSION['statusUpdate'] = sysTranslations::get('global_sequence_saved'); //save status update into session
        http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
    }

    // get all items for order changing
    $aFAQItemCategories     = FAQItemCategoryManager::getFAQItemCategoriesByFilter(['showAll' => true, 'languageId' => AdminLocales::language()]);
    $oPageLayout->sViewPath = getAdminView('faqItemCategories/faqItemCategories_change_order', 'faq');
} # display overview
else {
    $aFAQItemCategories     = FAQItemCategoryManager::getFAQItemCategoriesByFilter(['showAll' => true, 'languageId' => AdminLocales::language()]);
    $oPageLayout->sViewPath = getAdminView('faqItemCategories/faqItemCategories_overview', 'faq');
}

# include template
include_once getAdminView('layout');
?>