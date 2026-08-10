<?php

// check folders existance and writing rights
if (moduleExists('reviews')) {
    $aCheckRightFolders = [
        Review::IMAGES_PATH => true,
    ];

    // get settings for module and template and create all images folders
    $aImageSettings = TemplateSettings::get('reviews', 'images');
    if (!empty($aImageSettings['imagesPath'])) {
        // set main images folder
        $aCheckRightFolders[$aImageSettings['imagesPath']] = true;
        if (!empty($aImageSettings['sizes'])) {
            foreach ($aImageSettings['sizes'] AS $sReference => $aSizeData) {
                // set image file folders
                $aCheckRightFolders[$aImageSettings['imagesPath'] . '/' . $sReference] = true;
            }
        }
    }
}

// check dependencies
$aDependencyModules = [
    'core',
];

$aNeededAdminControllerRoutes = [
    'review'              => [
        'module'     => 'reviews',
        'controller' => 'review',
    ],
    'review-instellingen' => [
        'module'     => 'reviews',
        'controller' => 'reviewSettings',
    ],
];

$aNeededClassRoutes = [
    'Review'        => [
        'module' => 'reviews',
    ],
    'ReviewManager' => [
        'module' => 'reviews',
    ],
];

$aNeededSiteControllerRoutes = [
];

$aNeededModulesForMenu = [
    [
        'name'          => 'review',
        'icon'          => 'fa-thumbs-o-up',
        'linkName'      => 'review_menu',
        'moduleActions' => [
            ['displayName' => 'Volledig', 'name' => 'reviews_full'],
        ],
    ],
    [
        'name'             => 'review-instellingen',
        'icon'          => 'fa-cog',
        'linkName'         => 'review_settings_menu',
        'parentModuleName' => 'review',
        'moduleActions'    => [
            ['displayName' => 'Volledig', 'name' => 'reviewSettings_full'],
        ],
    ],
];

$aNeededTranslations = [
    'nl' => [
        ['label' => 'review_drag', 'text' => 'Sleep de titels om de volgorde te wijzigen'],
        ['label' => 'review_change_order', 'text' => 'Volgorde wijzigen'],
        ['label' => 'review_not_deleted', 'text' => 'Review niet verwijderd'],
        ['label' => 'review_deleted', 'text' => 'Review verwijderd'],
        ['label' => 'review_not_saved', 'text' => 'Review niet opgeslagen'],
        ['label' => 'review_saved', 'text' => 'Review opgeslagen'],
        ['label' => 'review_menu', 'text' => 'Reviews'],
        ['label' => 'review_review', 'text' => 'Review omschrijving'],
        ['label' => 'review_link_tooltip', 'text' => 'U kunt aan de review een link meegeven'],
        ['label' => 'review_stars', 'text' => 'sterren'],
        ['label' => 'review_star', 'text' => 'ster'],
        ['label' => 'review_choose_rating', 'text' => 'Kies een waardering'],
        ['label' => 'review_rating', 'text' => 'Waardering'],
        ['label' => 'review_author', 'text' => 'Auteur'],
        ['label' => 'review_no_title', 'text' => 'Vul de titel van de review in'],
        ['label' => 'review_online_offline_tooltip', 'text' => 'Zet de review online/offline'],
        ['label' => 'review', 'text' => 'Review'],
        ['label' => 'review_back_overview', 'text' => 'Terug naar overzicht'],
        ['label' => 'review_status_not_changed', 'text' => 'Reviewstatus niet aangepast'],
        ['label' => 'review_offline', 'text' => 'Review offline gezet'],
        ['label' => 'review_online', 'text' => 'Review online gezet'],
        ['label' => 'review_no_reviews', 'text' => 'Geen reviews gevonden'],
        ['label' => 'review_delete', 'text' => 'Review verwijderen'],
        ['label' => 'review_edit', 'text' => 'Review bewerken'],
        ['label' => 'review_set_online', 'text' => 'Review online zetten'],
        ['label' => 'review_set_offline', 'text' => 'Review offline zetten'],
        ['label' => 'review_add', 'text' => 'Review toevoegen'],
        ['label' => 'all_reviews', 'text' => 'Alle reviews'],
        ['label' => 'review_localeId', 'text' => 'Locatie / Taal'],
        ['label' => 'review_settings_menu', 'text' => 'Review instellingen'],
        ['label' => 'review_reviewsDefaultEmail', 'text' => 'E-mail voor reviews'],
        ['label' => 'review_reviewsRatingMin', 'text' => 'Waardering minimum'],
        ['label' => 'review_reviewsRatingMax', 'text' => 'Waardering maximum'],
        ['label' => 'review_reference', 'text' => 'Referentie'],
        ['label' => 'review_settings_menu', 'text' => 'Review instellingen'],
        ['label' => 'review_edition', 'text' => 'Review bewerken'],
        ['label' => 'review_images_warning', 'text' => 'Afbeeldingen kunnen pas worden geüpload nadat de review is opgeslagen'],
        ['label' => 'review_settings', 'text' => 'Review instellingen'],
        ['label' => 'review_localeId_title', 'text' => 'Review titel'],
    ],
];

$aNeededSiteTranslations = [
    ['label' => 'site_reviews', 'text' => 'Reviews', 'editable' => 1],

];


// Database checks

if (!$oDb->tableExists('reviews')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `reviews`';
    if ($bInstall) {

        // add table
        $sQuery = '
        CREATE TABLE `reviews` (
          `reviewId` int(11) NOT NULL AUTO_INCREMENT,
          `localeId` int(11) NOT NULL DEFAULT "-1",
          `importId` int(11) DEFAULT NULL,
          `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `author` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `review` text COLLATE utf8_unicode_ci,
          `link` text COLLATE utf8_unicode_ci,
          `reference` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `rating` int(1) DEFAULT NULL,
          `online` int(1) NOT NULL DEFAULT \'1\',
          `order` int(11) DEFAULT NULL,
          `created` timestamp NULL DEFAULT NULL,
          `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`reviewId`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

// check reviews constraints
if ($oDb->tableExists('reviews')) {
    if ($oDb->tableExists('locales')) {
        // check review constraint
        if (!$oDb->constraintExists('reviews', 'localeId', 'locales', 'localeId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `reviews`.`localeId` => `locales`.`localeId`';
            if ($bInstall) {
                $oDb->addConstraint('reviews', 'localeId', 'locales', 'localeId', 'RESTRICT', 'CASCADE');
            }
        }
    }
}

// start image relations
if (!$oDb->tableExists('reviews_images')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `reviews_images`';
    if ($bInstall) {

        // add table
        $sQuery = '
                CREATE TABLE `reviews_images` (
                  `reviewId` int(11) NOT NULL,
                  `imageId` int(11) NOT NULL,
                  PRIMARY KEY (`reviewId`,`imageId`),
                  KEY `imageId` (`imageId`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
            ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}


if ($oDb->tableExists('reviews_images')) {
    // check reviews constraint
    if (!$oDb->constraintExists('reviews_images', 'reviewId', 'reviews', 'reviewId')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `reviews_images`.`reviewId` => `reviews`.`reviewId`';
        if ($bInstall) {
            $oDb->addConstraint('reviews_images', 'reviewId', 'reviews', 'reviewId', 'RESTRICT', 'CASCADE');
        }
    }

    // check images constraint
    if (!$oDb->constraintExists('reviews_images', 'imageId', 'images', 'imageId')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `reviews_images`.`imageId` => `images`.`imageId`';
        if ($bInstall) {
            $oDb->addConstraint('reviews_images', 'imageId', 'images', 'imageId', 'CASCADE', 'CASCADE');
        }
    }
}

// add pages
if (moduleExists('pages') && $oDb->tableExists('pages')) {
    // add review page
    if (!($oPageReviewForm = PageManager::getPageByName('review_form', DEFAULT_LANGUAGE_ID))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing page `review_form`';
        if ($bInstall) {
            $oPageReviewForm             = new Page();
            $oPageReviewForm->languageId = DEFAULT_LANGUAGE_ID;
            $oPageReviewForm->name       = 'review_form';
            $oPageReviewForm->title      = 'Review schrijven';
            $oPageReviewForm->content    = '<p>Schrijf hier uw review</p>';
            $oPageReviewForm->forceUrlPath('/review-schrijven');
            $oPageReviewForm->setControllerPath('/modules/reviews/site/controllers/review.cont.php');
            $oPageReviewForm->setOnlineChangeable(0);
            $oPageReviewForm->setInMenu(0);
            $oPageReviewForm->setIndexable(0);
            $oPageReviewForm->setDeletable(0);
            $oPageReviewForm->setMayHaveSub(0);
            $oPageReviewForm->setLockUrlPath(0);
            $oPageReviewForm->setLockParent(1);
            $oPageReviewForm->setHideImageManagement(1);
            $oPageReviewForm->setHideFileManagement(1);
            $oPageReviewForm->setHideLinkManagement(1);
            $oPageReviewForm->setHideVideoLinkManagement(1);
            if ($oPageReviewForm->isValid()) {
                PageManager::savePage($oPageReviewForm);
            } else {
                _d($oPageReviewForm->getInvalidProps());
                die('Can\'t create page `review_form`');
            }
        }
    }

    // add review thanks page
    if (!empty($oPageReviewForm)) {
        if (!($oPageReviewThanks = PageManager::getPageByName('review_thanks', DEFAULT_LANGUAGE_ID))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing page `review_thanks`';
            if ($bInstall) {
                $oPageReviewThanks               = new Page();
                $oPageReviewThanks->languageId   = DEFAULT_LANGUAGE_ID;
                $oPageReviewThanks->parentPageId = $oPageReviewForm->pageId;
                $oPageReviewThanks->name         = 'review_thanks';
                $oPageReviewThanks->title        = 'Bedankt voor uw review';
                $oPageReviewThanks->content      = '<p>Bedankt voor het schrijven van een review. Het kan even duren voordat u de review op de site ziet verschijnen.</p>';
                $oPageReviewThanks->forceUrlPath($oPageReviewForm->getUrlPath() . '/bedankt');
                $oPageReviewThanks->setControllerPath('/modules/reviews/site/controllers/review.cont.php');
                $oPageReviewThanks->setOnlineChangeable(0);
                $oPageReviewThanks->setInMenu(0);
                $oPageReviewThanks->setIndexable(0);
                $oPageReviewThanks->setDeletable(0);
                $oPageReviewThanks->setMayHaveSub(0);
                $oPageReviewThanks->setLockUrlPath(0);
                $oPageReviewThanks->setLockParent(1);
                $oPageReviewThanks->setHideImageManagement(1);
                $oPageReviewThanks->setHideFileManagement(1);
                $oPageReviewThanks->setHideLinkManagement(1);
                $oPageReviewThanks->setHideVideoLinkManagement(1);
                if ($oPageReviewThanks->isValid()) {
                    PageManager::savePage($oPageReviewThanks);
                } else {
                    _d($oPageReviewThanks->getInvalidProps());
                    die('Can\'t create page `review_thanks`');
                }
            }
        }
    }
}

// check settings
if (class_exists('SettingManager')) {
    if (!($oSettingReviewEmail = SettingManager::getSettingByName('reviewsDefaultEmail'))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing setting `reviewsDefaultEmail`';
        if ($bInstall) {
            $oSettingReviewEmail        = new Setting();
            $oSettingReviewEmail->name  = 'reviewsDefaultEmail';
            $oSettingReviewEmail->value = 'name@domain.ext';
            if ($oSettingReviewEmail->isValid()) {
                SettingManager::saveSetting($oSettingReviewEmail);
            } else {
                _d($oSettingReviewEmail->getInvalidProps());
                die('Can\'t create setting `reviewsDefaultEmail`');
            }
        }
    }
    if (!($oSettingReviewsRatingMin = SettingManager::getSettingByName('reviewsRatingMin'))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing setting `reviewsRatingMin`';
        if ($bInstall) {
            $oSettingReviewsRatingMin        = new Setting();
            $oSettingReviewsRatingMin->name  = 'reviewsRatingMin';
            $oSettingReviewsRatingMin->value = 1;
            if ($oSettingReviewsRatingMin->isValid()) {
                SettingManager::saveSetting($oSettingReviewsRatingMin);
            } else {
                _d($oSettingReviewsRatingMin->getInvalidProps());
                die('Can\'t create setting `reviewsRatingMin`');
            }
        }
    }
    if (!($oSettingReviewsRatingMax = SettingManager::getSettingByName('reviewsRatingMax'))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing setting `reviewsRatingMax`';
        if ($bInstall) {
            $oSettingReviewsRatingMax        = new Setting();
            $oSettingReviewsRatingMax->name  = 'reviewsRatingMax';
            $oSettingReviewsRatingMax->value = 10;
            if ($oSettingReviewsRatingMax->isValid()) {
                SettingManager::saveSetting($oSettingReviewsRatingMax);
            } else {
                _d($oSettingReviewsRatingMax->getInvalidProps());
                die('Can\'t create setting `reviewsRatingMax`');
            }
        }
    }
}