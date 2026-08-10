<?php

// check folders existance and writing rights
$aCheckRightFolders = [
];

// check dependencies
$aDependencyModules = [
    'core',
];

$aNeededAdminControllerRoutes = [
];

$aNeededClassRoutes = [
];

$aNeededSiteControllerRoutes = [
];

if (moduleExists('core')) {
    if (!($oSetting = SettingManager::getSettingByName('reCaptchaWebsiteKey'))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing setting `reCaptchaWebsiteKey`';
        if ($bInstall) {
            $oSetting       = new Setting();
            $oSetting->name = 'reCaptchaWebsiteKey';
            if ($oSetting->isValid()) {
                SettingManager::saveSetting($oSetting);
            }
        }
    }
    if (!($oSetting = SettingManager::getSettingByName('reCaptchaSecretKey'))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing setting `reCaptchaSecretKey`';
        if ($bInstall) {
            $oSetting       = new Setting();
            $oSetting->name = 'reCaptchaSecretKey';
            if ($oSetting->isValid()) {
                SettingManager::saveSetting($oSetting);
            }
        }
    }

    if (($oSetting = SettingManager::getSettingByName('reCaptchaWebsiteKey')) && !$oSetting->value) {
        $aLogs[$sModuleName]['errors'][] = 'Please enter your recaptcha website key in the settings module';
    }

    if (($oSetting = SettingManager::getSettingByName('reCaptchaSecretKey')) && !$oSetting->value) {
        $aLogs[$sModuleName]['errors'][] = 'Please enter your recaptcha secret key in the settings module';
    }

    if (!($oSetting = SettingManager::getSettingByName('contact_email_to'))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing setting `contact_email_to`';
        if ($bInstall) {
            $oSetting       = new Setting();
            $oSetting->name = 'contact_email_to';
            if ($oSetting->isValid()) {
                SettingManager::saveSetting($oSetting);
            }
        }
    }

}

// add contact
if (moduleExists('pages') && $oDb->tableExists('pages')) {
    if (!($oPageContact = PageManager::getPageByName('contact', DEFAULT_LANGUAGE_ID))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing page `contact`';
        if ($bInstall) {
            $oPageContact             = new Page();
            $oPageContact->languageId = DEFAULT_LANGUAGE_ID;
            $oPageContact->name       = 'contact';
            $oPageContact->title      = 'Contact';
            $oPageContact->content    = '<p>Dit is de contactpagina. Hier staat het formulier om contact met de eigenaar van de website op te nemen</p>';
            $oPageContact->shortTitle = 'Contact';
            $oPageContact->forceUrlPath('/contact');
            $oPageContact->setControllerPath('/modules/contact/site/controllers/contact.cont.php');
            $oPageContact->setOnlineChangeable(0);
            $oPageContact->setDeletable(0);
            $oPageContact->setMayHaveSub(0);
            $oPageContact->setLockUrlPath(1);
            $oPageContact->setLockParent(1);
            $oPageContact->setHideImageManagement(1);
            $oPageContact->setHideFileManagement(1);
            $oPageContact->setHideLinkManagement(1);
            $oPageContact->setHideVideoLinkManagement(1);
            if ($oPageContact->isValid()) {
                PageManager::savePage($oPageContact);
            } else {
                _d($oPageContact->getInvalidProps());
                die('Can\'t create page `contact`');
            }
        }
    }

// add contact thanks page
    if (!empty($oPageContact)) {
        if (!($oPageContactThanks = PageManager::getPageByName('contact_thanks', DEFAULT_LANGUAGE_ID))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing page `contact_thanks`';
            if ($bInstall) {
                $oPageContactThanks               = new Page();
                $oPageContactThanks->languageId   = DEFAULT_LANGUAGE_ID;
                $oPageContactThanks->parentPageId = $oPageContact->pageId;
                $oPageContactThanks->name         = 'contact_thanks';
                $oPageContactThanks->title        = 'Bedankt voor uw reactie';
                $oPageContactThanks->content      = '<p>Wij nemen uw bericht in behandeling en indien nodig nemen wij zo spoedig mogelijk contact met u op.</p>';
                $oPageContactThanks->shortTitle   = 'Bedankt voor uw reactie';
                $oPageContactThanks->forceUrlPath($oPageContact->getUrlPath() . '/bedankt');
                $oPageContactThanks->setControllerPath('/modules/contact/site/controllers/contact.cont.php');
                $oPageContactThanks->setOnlineChangeable(0);
                $oPageContactThanks->setInMenu(0);
                $oPageContactThanks->setIndexable(0);
                $oPageContactThanks->setDeletable(0);
                $oPageContactThanks->setMayHaveSub(0);
                $oPageContactThanks->setLockUrlPath(1);
                $oPageContactThanks->setLockParent(1);
                $oPageContactThanks->setHideImageManagement(1);
                $oPageContactThanks->setHideFileManagement(1);
                $oPageContactThanks->setHideLinkManagement(1);
                $oPageContactThanks->setHideVideoLinkManagement(1);
                if ($oPageContactThanks->isValid()) {
                    PageManager::savePage($oPageContactThanks);
                } else {
                    _d($oPageContactThanks->getInvalidProps());
                    die('Can\'t create page `contact_thanks`');
                }
            }
        }
    }

    foreach (LocaleManager::getLocalesByFilter(['showAll' => true, 'NOTlanguageId' => DEFAULT_LANGUAGE_ID]) as $oLocale) {
        if (!($oNewPageContact = PageManager::getPageByName('contact', $oLocale->languageId))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing page `contact`';
            if ($bInstall) {
                # create contact page
                $oNewPageContact             = new Page();
                $oNewPageContact->languageId = $oLocale->languageId;
                $oNewPageContact->name       = 'contact';
                $oNewPageContact->title      = 'Contact';
                $oNewPageContact->content    = '<p>This is the contactpage. A form to contact the owner of the website is shown on this page</p>';
                $oNewPageContact->shortTitle = 'Contact';
                $oNewPageContact->forceUrlPath('/contact');
                $oNewPageContact->setControllerPath('/modules/contact/site/controllers/contact.cont.php');
                $oNewPageContact->setOnlineChangeable(0);
                $oNewPageContact->setDeletable(0);
                $oNewPageContact->setMayHaveSub(0);
                $oNewPageContact->setLockUrlPath(1);
                $oNewPageContact->setLockParent(1);
                $oNewPageContact->setHideImageManagement(1);
                $oNewPageContact->setHideFileManagement(1);
                $oNewPageContact->setHideLinkManagement(1);
                $oNewPageContact->setHideVideoLinkManagement(1);
                if ($oNewPageContact->isValid()) {
                    PageManager::savePage($oNewPageContact);
                } else {
                    _d($oNewPageContact->getInvalidProps());
                    die('Can\'t create page `contact`');
                }
            }
        }

        // add contact thanks page
        if (!($oNewPageContactThanks = PageManager::getPageByName('contact_thanks', $oLocale->languageId))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing page `contact_thanks`';
            if ($bInstall) {
                # create contact thanks page
                $oNewPageContactThanks               = new Page();
                $oNewPageContactThanks->languageId   = $oLocale->languageId;
                $oNewPageContactThanks->parentPageId = $oNewPageContact->pageId;
                $oNewPageContactThanks->name         = 'contact_thanks';
                $oNewPageContactThanks->title        = 'Thank you for getting in touch!';
                $oNewPageContactThanks->content      = '<p>Thanks for contacting us! If required, we will be in touch with you shortly.</p>';
                $oNewPageContactThanks->shortTitle   = 'Thank you for getting in touch!';
                $oNewPageContactThanks->forceUrlPath('/contact/thanks');
                $oNewPageContactThanks->setControllerPath('/modules/contact/site/controllers/contact.cont.php');
                $oNewPageContactThanks->setOnlineChangeable(0);
                $oNewPageContactThanks->setInMenu(0);
                $oNewPageContactThanks->setIndexable(0);
                $oNewPageContactThanks->setDeletable(0);
                $oNewPageContactThanks->setMayHaveSub(0);
                $oNewPageContactThanks->setLockUrlPath(1);
                $oNewPageContactThanks->setLockParent(1);
                $oNewPageContactThanks->setHideImageManagement(1);
                $oNewPageContactThanks->setHideFileManagement(1);
                $oNewPageContactThanks->setHideLinkManagement(1);
                $oNewPageContactThanks->setHideVideoLinkManagement(1);
                if ($oNewPageContactThanks->isValid()) {
                    PageManager::savePage($oNewPageContactThanks);
                } else {
                    _d($oNewPageContactThanks->getInvalidProps());
                    die('Can\'t create page `contact_thanks`');
                }
            }
        }
    }
}

// system translations (back end)
$aNeededTranslations = [
    'nl' => [
        ['label' => 'contact_email_to', 'text' => 'E-mail contactformulier'],
        ['label' => 'settings_contact_email_to_tooltip', 'text' => 'Ontvang de e-mails vanuit het contactformulier op dit e-mailadres ipv het default e-mailadres'],
    ],
];

// site translations (front end)
$aNeededSiteTranslations = [
    'nl' => [
        ['label' => 'site_name', 'text' => 'Naam', 'editable' => 1],
        ['label' => 'site_company', 'text' => 'Bedrijfsnaam', 'editable' => 1],
        ['label' => 'site_organization', 'text' => 'Organisatie', 'editable' => 1],
        ['label' => 'site_phone', 'text' => 'Telefoon', 'editable' => 1],
        ['label' => 'site_email', 'text' => 'E-mail', 'editable' => 1],
        ['label' => 'site_subject', 'text' => 'Onderwerp', 'editable' => 1],
        ['label' => 'site_message', 'text' => 'Bericht', 'editable' => 1],
        ['label' => 'site_send', 'text' => 'Verzenden', 'editable' => 1],
        ['label' => 'site_google_maps_loading', 'text' => 'Google maps laden...', 'editable' => 1],
        ['label' => 'site_spam_check', 'text' => 'Dit veld niet wijzigen (SPAM check)', 'editable' => 1],
        ['label' => 'site_fill_in_your_name', 'text' => 'U heeft uw naam niet ingevuld', 'editable' => 1],
        ['label' => 'site_fill_in_your_email', 'text' => 'U heeft geen e-mailadres ingevuld', 'editable' => 1],
        ['label' => 'site_email_not_valid', 'text' => 'Het ingevulde e-mailadres is niet geldig', 'editable' => 1],
        ['label' => 'site_fill_in_your_message', 'text' => 'U heeft geen bericht ingevuld', 'editable' => 1],
        ['label' => 'site_message_has_links', 'text' => 'Uw bericht bevat een of meer links, dit is niet toegestaan. Verwijder deze en probeer nog eens.', 'editable' => 1],
        ['label' => 'site_rumpelstiltskin_error', 'text' => 'Er is een fout opgetreden bij het versturen van de aanvraag. Probeer het nogmaals of neem op een andere manier contact met ons op.', 'editable' => 1],
        ['label' => 'site_directions', 'text' => 'Routebeschrijving', 'editable' => 1],
    ],
];
