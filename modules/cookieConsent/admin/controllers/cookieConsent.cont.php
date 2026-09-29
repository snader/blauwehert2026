<?php

// check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

// reset crop settings
unset($_SESSION['aCropSettings']);

global $oPageLayout;

// set page layout properties
$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = sysTranslations::get('cc_menu');
$oPageLayout->sModuleName  = sysTranslations::get('cc_menu');

if(Request::postVar('action') == "save" && CSRFSynchronizerToken::validate()){
    foreach (http_post('settings', []) AS $sName => $sValue) {
        // handle url fields
        if (in_array($sName, $aUrlFields)) {
            $sValue = addHttp($sValue);
        }

        // handle admin config fields
        if (in_array($sName, $aAdminFields)) {
            if ($oCurrentUser->isAdmin()) {
                $oSetting = SettingManager::getSettingByName($sName);
                if ($oSetting) {
                    $oSetting->value = $sValue;
                    SettingManager::saveSetting($oSetting);
                }
            }
            continue;
        }

        // get setting and save
        $oSetting = SettingManager::getSettingByName($sName);
        if ($oSetting) {
            $oSetting->value = $sValue;
            SettingManager::saveSetting($oSetting);
        }
    }

    Router::redirect(getCurrentUrl());
} else {
    if(moduleExists("pages")){
        # Get cookie policy pages
        $aLocales = LocaleManager::getLocalesByFilter();
        $aConsentPages = [];
        $aMissingPages = [];

        foreach($aLocales as $oLocale){
            if($oPage = PageManager::getPageByName('cookiepolicy', $oLocale->languageId)){
                $aConsentPages[] = $oPage;
            } else {
                $aMissingPages[] = $oLocale->getLanguage()->nativeName;
            }
        }
    }

    # Display CookieConsent module settings view
    $oPageLayout->sViewPath = getAdminView('cookieConsent_settings', 'cookieConsent');
}

# inlcude template
include_once getAdminView('layout');
?>