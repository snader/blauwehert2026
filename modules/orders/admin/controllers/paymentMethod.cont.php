<?php

# check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

global $oPageLayout;

# set page layout properties
$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = sysTranslations::get('order_payment_method');
$oPageLayout->sModuleName  = sysTranslations::get('order_payment_method');

# get status update from session
$oPageLayout->sStatusUpdate = http_session("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once
# handle add/edit
if (http_get("param1") == 'bewerken' || http_get("param1") == 'toevoegen') {
    # set crop referrer for this module
    $_SESSION['cropReferrer'] = ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . http_get("param2");

    if (http_get("param1") == 'bewerken' && is_numeric(http_get("param2"))) {
        $oPaymentMethod = PaymentMethodManager::getPaymentMethodById(http_get("param2"));
        if (empty($oPaymentMethod) || !$oPaymentMethod->isEditable()) {
            http_redirect(ADMIN_FOLDER . "/" . http_get('param1'));
        }
    } else {
        $oPaymentMethod = new PaymentMethod();
    }

    # action = save
    if (http_post("action") == 'save' && CSRFSynchronizerToken::validate()) {
        # load data in object
        $oPaymentMethod->_load($_POST);

        # if object is valid, save
        if ($oPaymentMethod->isValid()) {
            PaymentMethodManager::savePaymentMethod($oPaymentMethod); //save object

            # save translations
            foreach (AdminLocales::getLanguages() as $oLanguage) {
                $oPaymentMethodTranslation                  = new PaymentMethodTranslation();
                $oPaymentMethodTranslation->paymentMethodId = $oPaymentMethod->paymentMethodId;
                $oPaymentMethodTranslation->languageId      = $oLanguage->languageId;
                $oPaymentMethodTranslation->name            = $_POST['name'][$oLanguage->languageId];
                $oPaymentMethodTranslation->redirectPage    = $_POST['redirectPage'][$oLanguage->languageId];

                if ($oPaymentMethodTranslation->isValid()) {
                    PaymentMethodTranslationManager::savePaymentMethodTranslation($oPaymentMethodTranslation);
                } else {
                    Debug::logError("", "PaymentMethod module php validate error", __FILE__, __LINE__, "Tried to save PaymentMethodTranslation with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
                }
            }

            $_SESSION['statusUpdate'] = sysTranslations::get('order_payment_method_saved'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oPaymentMethod->paymentMethodId);
        } else {
            Debug::logError("", "PaymentMethod module php validate error", __FILE__, __LINE__, "Tried to save PaymentMethod with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $_SESSION['statusUpdate'] = sysTranslations::get('order_payment_method_not_saved');
        }
    }

    $oPageLayout->sViewPath = getAdminView('paymentMethods/paymentMethod_form', 'orders');
} elseif (http_get('param1') == 'volgorde-wijzigen') {
    if (http_post('action') == 'saveOrder' && CSRFSynchronizerToken::validate()) {
        if (http_post('order')) {
            $aPaymentMethodIds = explode('|', http_post('order'));
            $iC                = 1;
            foreach ($aPaymentMethodIds AS $iPaymentMethodId) {
                $oPaymentMethod        = PaymentMethodManager::getPaymentMethodById($iPaymentMethodId);
                $oPaymentMethod->order = $iC;
                if ($oPaymentMethod->isValid()) {
                    PaymentMethodManager::savePaymentMethod($oPaymentMethod);
                }
                $iC++;
            }
        }
        $_SESSION['statusUpdate'] = sysTranslations::get('global_sequence_saved'); //save status update into session
        http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
    }

    // get all items for order changing
    $aPaymentMethods        = PaymentMethodManager::getPaymentMethodsByFilter();
    $oPageLayout->sViewPath = getAdminView('paymentMethods/paymentMethods_change_order', 'orders');
} # check system name
elseif (http_get('param1') == 'ajax-checkName') {
    if(!CSRFSynchronizerToken::validate()){
        die(json_encode(['status'=>false]));
    }
    # check if name exists
    $oPaymentMethod = PaymentMethodManager::getPaymentMethodByName(http_get('name'));
    if ($oPaymentMethod && $oPaymentMethod->paymentMethodId != http_get('paymentMethodId')) {
        echo 'false';
    } else {
        echo 'true';
    }
    die;
} # delete object
elseif (http_get("param1") == 'verwijderen' && is_numeric(http_get("param2"))) {
    if(CSRFSynchronizerToken::validate()) {
        if (is_numeric(http_get("param2"))) {
            $oPaymentMethod = PaymentMethodManager::getPaymentMethodById(http_get("param2"));
        }

        if (!empty($oPaymentMethod) && PaymentMethodManager::deletePaymentMethod($oPaymentMethod)) {
            $_SESSION['statusUpdate'] = sysTranslations::get('order_payment_method_deleted'); //save status update into session
        } else {
            $_SESSION['statusUpdate'] = sysTranslations::get('order_payment_method_not_deleted'); //save status update into session
        }
    }
    http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
} # display overview
else {
    $aPaymentMethods        = PaymentMethodManager::getPaymentMethodsByFilter();
    $oPageLayout->sViewPath = getAdminView('paymentMethods/paymentMethods_overview', 'orders');
}

# include template
include_once getAdminView('layout');
?>