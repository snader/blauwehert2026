<?php

# check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

global $oPageLayout;

# set page layout properties
$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = sysTranslations::get('order_delivery_method');
$oPageLayout->sModuleName  = sysTranslations::get('order_delivery_method');

# get status update from session
$oPageLayout->sStatusUpdate = http_session("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once
# handle add/edit
if (http_get("param1") == 'bewerken' || http_get("param1") == 'toevoegen') {
    # set crop referrer for this module
    $_SESSION['cropReferrer'] = ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . http_get("param2");

    if (http_get("param1") == 'bewerken' && is_numeric(http_get("param2"))) {
        $oDeliveryMethod = DeliveryMethodManager::getDeliveryMethodById(http_get("param2"));
        if (empty($oDeliveryMethod) || !$oDeliveryMethod->isEditable()) {
            http_redirect(ADMIN_FOLDER . "/" . http_get('param1'));
        }
    } else {
        $oDeliveryMethod = new DeliveryMethod();
    }

    # action = save
    if (http_post("action") == 'save' && CSRFSynchronizerToken::validate()) {
        # load data in object
        $oDeliveryMethod->_load($_POST);

        $aPaymentMethods = [];
        foreach (http_post('paymentMethodIds', []) AS $iPaymentMethodId) {
            $aPaymentMethods[] = new PaymentMethod(['paymentMethodId' => $iPaymentMethodId]);
        }
        $oDeliveryMethod->setPaymentMethods($aPaymentMethods);

        # if object is valid, save
        if ($oDeliveryMethod->isValid()) {
            DeliveryMethodManager::saveDeliveryMethod($oDeliveryMethod); //save object

            # save translations
            foreach (AdminLocales::getLanguages() as $oLanguage) {
                $oDeliveryMethodTranslation                   = new DeliveryMethodTranslation();
                $oDeliveryMethodTranslation->deliveryMethodId = $oDeliveryMethod->deliveryMethodId;
                $oDeliveryMethodTranslation->languageId       = $oLanguage->languageId;
                $oDeliveryMethodTranslation->name             = $_POST['name'][$oLanguage->languageId];

                if ($oDeliveryMethodTranslation->isValid()) {
                    DeliveryMethodTranslationManager::saveDeliveryMethodTranslation($oDeliveryMethodTranslation);
                } else {
                    Debug::logError("", "DeliveryMethod module php validate error", __FILE__, __LINE__, "Tried to save DeliveryMethodTranslation with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
                }
            }

            $_SESSION['statusUpdate'] = sysTranslations::get('order_delivery_method_saved'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oDeliveryMethod->deliveryMethodId);
        } else {
            Debug::logError("", "DeliveryMethod module php validate error", __FILE__, __LINE__, "Tried to save DeliveryMethod with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $_SESSION['statusUpdate'] = sysTranslations::get('order_delivery_method_not_saved');
        }
    }

    $oPageLayout->sViewPath = getAdminView('deliveryMethods/deliveryMethod_form', 'orders');
} elseif (http_get('param1') == 'volgorde-wijzigen') {
    if (http_post('action') == 'saveOrder' && CSRFSynchronizerToken::validate()) {
        if (http_post('order')) {
            $aDeliveryMethodIds = explode('|', http_post('order'));
            $iC                 = 1;
            foreach ($aDeliveryMethodIds AS $iDeliveryMethodId) {
                $oDeliveryMethod        = DeliveryMethodManager::getDeliveryMethodById($iDeliveryMethodId);
                $oDeliveryMethod->order = $iC;
                if ($oDeliveryMethod->isValid()) {
                    DeliveryMethodManager::saveDeliveryMethod($oDeliveryMethod);
                }
                $iC++;
            }
        }
        $_SESSION['statusUpdate'] = sysTranslations::get('global_sequence_saved'); //save status update into session
        http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
    }

    // get all items for order changing
    $aDeliveryMethods       = DeliveryMethodManager::getAllDeliveryMethods();
    $oPageLayout->sViewPath = getAdminView('deliveryMethods/deliveryMethods_change_order', 'orders');
} # check system name
elseif (http_get('param1') == 'ajax-checkName') {
    if(!CSRFSynchronizerToken::validate()){
        die(json_encode(['status'=>false]));
    }
    # check if name exists
    $oDeliveryMethod = DeliveryMethodManager::getDeliveryMethodByName(http_get('name'));
    if ($oDeliveryMethod && $oDeliveryMethod->deliveryMethodId != http_get('deliveryMethodId')) {
        echo 'false';
    } else {
        echo 'true';
    }
    die;
} # delete object
elseif (http_get("param1") == 'verwijderen' && is_numeric(http_get("param2"))) {
    if(CSRFSynchronizerToken::validate()) {
        if (is_numeric(http_get("param2"))) {
            $oDeliveryMethod = DeliveryMethodManager::getDeliveryMethodById(http_get("param2"));
        }

        if (!empty($oDeliveryMethod) && DeliveryMethodManager::deleteDeliveryMethod($oDeliveryMethod)) {
            $_SESSION['statusUpdate'] = sysTranslations::get('order_delivery_method_deleted'); //save status update into session
        } else {
            $_SESSION['statusUpdate'] = sysTranslations::get('order_delivery_method_not_deleted'); //save status update into session
        }
    }
    http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
} # display overview
else {
    $aDeliveryMethods       = DeliveryMethodManager::getAllDeliveryMethods();
    $oPageLayout->sViewPath = getAdminView('deliveryMethods/deliveryMethods_overview', 'orders');
}

# include template
include_once getAdminView('layout');
?>