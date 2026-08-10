<?php

// check folders existance and writing rights
$aCheckRightFolders = [
];

// check dependencies
$aDependencyModules = [
    'core',
    'orders',
];

$aNeededAdminControllerRoutes = [
];

$aNeededClassRoutes = [
];

$aNeededSiteControllerRoutes = [
    /*
      'kassa' => array(
      'module' => 'paymentMollie',
      'controller' => 'payment'
      ),
     *
     */
];

$aNeededModulesForMenu = [
];

$aNeededTranslations = [
];

// add page
if (moduleExists('pages') && $oDb->tableExists('pages')) {
    if (!($oPagePayment = PageManager::getPageByName('payment', DEFAULT_LANGUAGE_ID))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing page `payment`';
        if ($bInstall) {
            $oPagePayment             = new Page();
            $oPagePayment->languageId = DEFAULT_LANGUAGE_ID;
            $oPagePayment->name       = 'payment';
            $oPagePayment->title      = 'Kassa';
            $oPagePayment->content    = '';
            $oPagePayment->shortTitle = 'Kassa';
            $oPagePayment->forceUrlPath('/kassa');
            $oPagePayment->setControllerPath('/modules/paymentMollie/site/controllers/payment.cont.php');
            $oPagePayment->setInMenu(0);
            $oPagePayment->setIndexable(0);
            $oPagePayment->setOnlineChangeable(0);
            $oPagePayment->setDeletable(0);
            $oPagePayment->setMayHaveSub(0);
            $oPagePayment->setLockUrlPath(1);
            $oPagePayment->setLockParent(1);
            $oPagePayment->setHideImageManagement(1);
            $oPagePayment->setHideFileManagement(1);
            $oPagePayment->setHideLinkManagement(1);
            $oPagePayment->setHideVideoLinkManagement(1);
            if ($oPagePayment->isValid()) {
                PageManager::savePage($oPagePayment);
            } else {
                _d($oPagePayment->getInvalidProps());
                die('Can\'t create page `payment`');
            }
        }
    }

    // add payment unknown page
    if (!empty($oPagePayment)) {
        if (!($oPagePaymentUnknown = PageManager::getPageByName('payment_unknown', DEFAULT_LANGUAGE_ID))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing page `payment_unknown`';
            if ($bInstall) {
                $oPagePaymentUnknown               = new Page();
                $oPagePaymentUnknown->languageId   = DEFAULT_LANGUAGE_ID;
                $oPagePaymentUnknown->parentPageId = $oPagePayment->pageId;
                $oPagePaymentUnknown->name         = 'payment_unknown';
                $oPagePaymentUnknown->title        = 'Betaling onbekend';
                $oPagePaymentUnknown->content      = '<p>De bestelling kan niet worden gevonden of is al betaald. Bij vragen kunt u altijd contact met ons opnemen.</p>';
                $oPagePaymentUnknown->shortTitle   = 'Betaling onbekend';
                $oPagePaymentUnknown->forceUrlPath($oPagePayment->getUrlPath() . '/betaling-onbekend');
                $oPagePaymentUnknown->setControllerPath('/modules/paymentMollie/site/controllers/payment.cont.php');
                $oPagePaymentUnknown->setInMenu(0);
                $oPagePaymentUnknown->setIndexable(0);
                $oPagePaymentUnknown->setOnlineChangeable(0);
                $oPagePaymentUnknown->setDeletable(0);
                $oPagePaymentUnknown->setMayHaveSub(0);
                $oPagePaymentUnknown->setLockUrlPath(1);
                $oPagePaymentUnknown->setLockParent(1);
                $oPagePaymentUnknown->setHideImageManagement(1);
                $oPagePaymentUnknown->setHideFileManagement(1);
                $oPagePaymentUnknown->setHideLinkManagement(1);
                $oPagePaymentUnknown->setHideVideoLinkManagement(1);
                if ($oPagePaymentUnknown->isValid()) {
                    PageManager::savePage($oPagePaymentUnknown);
                } else {
                    _d($oPagePaymentUnknown->getInvalidProps());
                    die('Can\'t create page `payment_unknown`');
                }
            }
        }
    }

    // add payment error page
    if (!empty($oPagePayment)) {
        if (!($oPagePaymentError = PageManager::getPageByName('payment_error', DEFAULT_LANGUAGE_ID))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing page `payment_error`';
            if ($bInstall) {
                $oPagePaymentError               = new Page();
                $oPagePaymentError->languageId   = DEFAULT_LANGUAGE_ID;
                $oPagePaymentError->parentPageId = $oPagePayment->pageId;
                $oPagePaymentError->name         = 'payment_error';
                $oPagePaymentError->title        = 'Betaling error';
                $oPagePaymentError->content      = '<p>Er is mis gegaan met de betaling. Probeer nogmaals de bestelling te <a href="/kassa">betalen</a> of neem <a href="/contact">contact</a> met ons op.</p>';
                $oPagePaymentError->shortTitle   = 'Betaling error';
                $oPagePaymentError->forceUrlPath($oPagePayment->getUrlPath() . '/betaling-error');
                $oPagePaymentError->setControllerPath('/modules/paymentMollie/site/controllers/payment.cont.php');
                $oPagePaymentError->setInMenu(0);
                $oPagePaymentError->setIndexable(0);
                $oPagePaymentError->setOnlineChangeable(0);
                $oPagePaymentError->setDeletable(0);
                $oPagePaymentError->setMayHaveSub(0);
                $oPagePaymentError->setLockUrlPath(1);
                $oPagePaymentError->setLockParent(1);
                $oPagePaymentError->setHideImageManagement(1);
                $oPagePaymentError->setHideFileManagement(1);
                $oPagePaymentError->setHideLinkManagement(1);
                $oPagePaymentError->setHideVideoLinkManagement(1);
                if ($oPagePaymentError->isValid()) {
                    PageManager::savePage($oPagePaymentError);
                } else {
                    _d($oPagePaymentError->getInvalidProps());
                    die('Can\'t create page `payment_error`');
                }
            }
        }
    }

    // add payment success page
    if (!empty($oPagePayment)) {
        if (!($oPagePaymentSuccess = PageManager::getPageByName('payment_success', DEFAULT_LANGUAGE_ID))) {
            $aLogs[$sModuleName]['successs'][] = 'Missing page `payment_success`';
            if ($bInstall) {
                $oPagePaymentSuccess               = new Page();
                $oPagePaymentSuccess->languageId   = DEFAULT_LANGUAGE_ID;
                $oPagePaymentSuccess->parentPageId = $oPagePayment->pageId;
                $oPagePaymentSuccess->name         = 'payment_success';
                $oPagePaymentSuccess->title        = 'Betaling succes';
                $oPagePaymentSuccess->content      = '<p>Uw betaling is succesvol ontvangen. Wij zullen uw bestelling zo spoedig mogelijk in behandeling nemen.</p>';
                $oPagePaymentSuccess->shortTitle   = 'Betaling succes';
                $oPagePaymentSuccess->forceUrlPath($oPagePayment->getUrlPath() . '/betaling-succes');
                $oPagePaymentSuccess->setControllerPath('/modules/paymentMollie/site/controllers/payment.cont.php');
                $oPagePaymentSuccess->setInMenu(0);
                $oPagePaymentSuccess->setIndexable(0);
                $oPagePaymentSuccess->setOnlineChangeable(0);
                $oPagePaymentSuccess->setDeletable(0);
                $oPagePaymentSuccess->setMayHaveSub(0);
                $oPagePaymentSuccess->setLockUrlPath(1);
                $oPagePaymentSuccess->setLockParent(1);
                $oPagePaymentSuccess->setHideImageManagement(1);
                $oPagePaymentSuccess->setHideFileManagement(1);
                $oPagePaymentSuccess->setHideLinkManagement(1);
                $oPagePaymentSuccess->setHideVideoLinkManagement(1);
                if ($oPagePaymentSuccess->isValid()) {
                    PageManager::savePage($oPagePaymentSuccess);
                } else {
                    _d($oPagePaymentSuccess->getInvalidProps());
                    die('Can\'t create page `payment_success`');
                }
            }
        }
    }

    foreach (LocaleManager::getLocalesByFilter(['showAll' => true, 'NOTlanguageId' => DEFAULT_LANGUAGE_ID]) as $oLocale) {
        if (!($oNewPagePayment = PageManager::getPageByName('payment', $oLocale->languageId))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing page `payment` for language `' . strtoupper($oLocale->getLanguage()->code) . '`';
            if ($bInstall) {
                # create checkout page
                $oNewPagePayment             = new Page();
                $oNewPagePayment->languageId = $oLocale->languageId;
                $oNewPagePayment->name       = 'payment';
                $oNewPagePayment->title      = 'Checkout';
                $oNewPagePayment->content    = '';
                $oNewPagePayment->shortTitle = 'Checkout';
                $oNewPagePayment->forceUrlPath('/checkout');
                $oNewPagePayment->setControllerPath('/modules/paymentMollie/site/controllers/payment.cont.php');
                $oNewPagePayment->setInMenu(0);
                $oNewPagePayment->setIndexable(0);
                $oNewPagePayment->setOnlineChangeable(0);
                $oNewPagePayment->setDeletable(0);
                $oNewPagePayment->setMayHaveSub(0);
                $oNewPagePayment->setLockUrlPath(1);
                $oNewPagePayment->setLockParent(1);
                $oNewPagePayment->setHideImageManagement(1);
                $oNewPagePayment->setHideFileManagement(1);
                $oNewPagePayment->setHideLinkManagement(1);
                $oNewPagePayment->setHideVideoLinkManagement(1);
                if ($oNewPagePayment->isValid()) {
                    PageManager::savePage($oNewPagePayment);
                } else {
                    _d($oNewPagePayment->getInvalidProps());
                    die('Can\'t create page `payment`');
                }
            }
        }

        if (!($oNewPagePaymentUnknown = PageManager::getPageByName('payment_unknown', $oLocale->languageId))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing page `payment_unknown` for language `' . strtoupper($oLocale->getLanguage()->code) . '`';
            if ($bInstall) {
                # create payment unkown page
                $oNewPagePaymentUnknown               = new Page();
                $oNewPagePaymentUnknown->languageId   = $oLocale->languageId;
                $oNewPagePaymentUnknown->parentPageId = $oNewPagePayment->pageId;
                $oNewPagePaymentUnknown->name         = 'payment_unknown';
                $oNewPagePaymentUnknown->title        = 'Payment unknown';
                $oNewPagePaymentUnknown->content      = '<p>The order cannot be found or has been paid. For questions you can always contact us.</p>';
                $oNewPagePaymentUnknown->shortTitle   = 'Payment unknown';
                $oNewPagePaymentUnknown->forceUrlPath('/checkout/payment-unknown');
                $oNewPagePaymentUnknown->setControllerPath('/modules/paymentMollie/site/controllers/payment.cont.php');
                $oNewPagePaymentUnknown->setInMenu(0);
                $oNewPagePaymentUnknown->setIndexable(0);
                $oNewPagePaymentUnknown->setOnlineChangeable(0);
                $oNewPagePaymentUnknown->setDeletable(0);
                $oNewPagePaymentUnknown->setMayHaveSub(0);
                $oNewPagePaymentUnknown->setLockUrlPath(1);
                $oNewPagePaymentUnknown->setLockParent(1);
                $oNewPagePaymentUnknown->setHideImageManagement(1);
                $oNewPagePaymentUnknown->setHideFileManagement(1);
                $oNewPagePaymentUnknown->setHideLinkManagement(1);
                $oNewPagePaymentUnknown->setHideVideoLinkManagement(1);
                if ($oNewPagePaymentUnknown->isValid()) {
                    PageManager::savePage($oNewPagePaymentUnknown);
                } else {
                    _d($oNewPagePaymentUnknown->getInvalidProps());
                    die('Can\'t create page `payment_unknown`');
                }
            }
        }

        if (!($oPagePaymentError = PageManager::getPageByName('payment_error', $oLocale->languageId))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing page `payment_error` for language `' . strtoupper($oLocale->getLanguage()->code) . '`';
            if ($bInstall) {
                # create payment error page
                $oPagePaymentError               = new Page();
                $oPagePaymentError->languageId   = $oLocale->languageId;
                $oPagePaymentError->parentPageId = $oNewPagePayment->pageId;
                $oPagePaymentError->name         = 'payment_error';
                $oPagePaymentError->title        = 'Payment error';
                $oPagePaymentError->content      = '<p>Something went wrong with the payment. Please <a href="/' . $oLocale->getURLPrefix() . '/checkout">try again</a> or <a href="/' . $oLocale->getURLPrefix(
                    ) . '/contact">contact</a> us.</p>';
                $oPagePaymentError->shortTitle   = 'Payment error';
                $oPagePaymentError->forceUrlPath('/checkout/payment-error');
                $oPagePaymentError->setControllerPath('/modules/paymentMollie/site/controllers/payment.cont.php');
                $oPagePaymentError->setInMenu(0);
                $oPagePaymentError->setIndexable(0);
                $oPagePaymentError->setOnlineChangeable(0);
                $oPagePaymentError->setDeletable(0);
                $oPagePaymentError->setMayHaveSub(0);
                $oPagePaymentError->setLockUrlPath(1);
                $oPagePaymentError->setLockParent(1);
                $oPagePaymentError->setHideImageManagement(1);
                $oPagePaymentError->setHideFileManagement(1);
                $oPagePaymentError->setHideLinkManagement(1);
                $oPagePaymentError->setHideVideoLinkManagement(1);
                if ($oPagePaymentError->isValid()) {
                    PageManager::savePage($oPagePaymentError);
                } else {
                    _d($oPagePaymentError->getInvalidProps());
                    die('Can\'t create page `payment_error`');
                }
            }
        }

        if (!($oPagePaymentSuccess = PageManager::getPageByName('payment_success', $oLocale->languageId))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing page `payment_success` for language `' . strtoupper($oLocale->getLanguage()->code) . '`';
            if ($bInstall) {
                # create payment success page
                $oPagePaymentSuccess               = new Page();
                $oPagePaymentSuccess->languageId   = $oLocale->languageId;
                $oPagePaymentSuccess->parentPageId = $oNewPagePayment->pageId;
                $oPagePaymentSuccess->name         = 'payment_success';
                $oPagePaymentSuccess->title        = 'Payment success';
                $oPagePaymentSuccess->content      = '<p>Your payment is successfully received. We will ship your order as soon as possible.</p>';
                $oPagePaymentSuccess->shortTitle   = 'Payment success';
                $oPagePaymentSuccess->forceUrlPath('/checkout/payment-success');
                $oPagePaymentSuccess->setControllerPath('/modules/paymentMollie/site/controllers/payment.cont.php');
                $oPagePaymentSuccess->setInMenu(0);
                $oPagePaymentSuccess->setIndexable(0);
                $oPagePaymentSuccess->setOnlineChangeable(0);
                $oPagePaymentSuccess->setDeletable(0);
                $oPagePaymentSuccess->setMayHaveSub(0);
                $oPagePaymentSuccess->setLockUrlPath(1);
                $oPagePaymentSuccess->setLockParent(1);
                $oPagePaymentSuccess->setHideImageManagement(1);
                $oPagePaymentSuccess->setHideFileManagement(1);
                $oPagePaymentSuccess->setHideLinkManagement(1);
                $oPagePaymentSuccess->setHideVideoLinkManagement(1);
                if ($oPagePaymentSuccess->isValid()) {
                    PageManager::savePage($oPagePaymentSuccess);
                } else {
                    _d($oPagePaymentSuccess->getInvalidProps());
                    die('Can\'t create page `payment_success`');
                }
            }
        }
    }
}

// check settings
if (moduleExists('core')) {
    if (!($oSetting1 = SettingManager::getSettingByName('mollieAPIKey'))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing setting `mollieAPIKey`';
        if ($bInstall) {
            $oSetting1        = new Setting();
            $oSetting1->name  = 'mollieAPIKey';
            $oSetting1->value = 'test_6qSDnbp4DveGtwzrHBMsKFBcW8xjBk';
            if ($oSetting1->isValid()) {
                SettingManager::saveSetting($oSetting1);
            } else {
                _d($oSetting1->getInvalidProps());
                die('Can\'t create setting `mollieAPIKey`');
            }
        }
    }
}
