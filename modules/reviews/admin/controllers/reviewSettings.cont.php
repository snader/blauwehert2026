<?php

# check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

global $oPageLayout;

$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = sysTranslations::get('review_settings');
$oPageLayout->sModuleName  = sysTranslations::get('review_settings');

# get status update from session
$oPageLayout->sStatusUpdate = Session::get("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once
$oPageLayout->sViewPath = getAdminView('reviewSettings_form', 'reviews');

// set supported fields and some options
$aSupportedFields = [
    'reviewsDefaultEmail' => ['validation' => 'email required'],
    'reviewsRatingMin'    => ['validation' => 'digits required'],
    'reviewsRatingMax'    => ['validation' => 'digits required'],
];

if (Request::postVar('action') == 'save' && CSRFSynchronizerToken::validate()) {
    foreach (Request::postVar('settings') AS $sName => $sValue) {

        // check if field is supported
        if (!array_key_exists($sName, $aSupportedFields)) {
            continue;
        }

        // get setting and save
        $oSetting = SettingManager::getSettingByName($sName);
        if ($oSetting) {
            $oSetting->value = $sValue;
            SettingManager::saveSetting($oSetting);
        }
    }
    $_SESSION['statusUpdate'] = 'global_settings_saved';
    Router::redirect(getCurrentUrl());
}

# include default template
include_once getAdminView('layout');
?>
