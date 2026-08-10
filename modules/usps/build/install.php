<?php

// check folders existance and writing rights
if (class_exists('Usp')) {
    $aCheckRightFolders = [
        Usp::FILES_PATH => true,
    ];

    // get settings for module and template and create all images folders
    $aImageSettings = TemplateSettings::get('usps', 'images');
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
    'usps' => [
        'module'     => 'usps',
        'controller' => 'usp',
    ],
];

$aNeededClassRoutes = [
    'Usp'        => [
        'module' => 'usps',
    ],
    'UspManager' => [
        'module' => 'usps',
    ],
];

$aNeededSiteControllerRoutes = [];

$aNeededModulesForMenu = [
    [
        'name'          => 'usps',
        'icon'          => 'fal fa-puzzle-piece',
        'linkName'      => 'usp_menu',
        'moduleActions' => [
            ['displayName' => 'Volledig', 'name' => 'usps_full'],
        ],
    ],
];

$aNeededTranslations = [
    'nl' => [
        ['label' => 'usp_crop_large', 'text' => 'Lang'],
        ['label' => 'usp_crop_small', 'text' => 'Klein'],
        ['label' => 'usp_menu', 'text' => 'Usps'],
        ['label' => 'usp_images_warning', 'text' => 'U moet de usp eerst opslaan voor u een afbeelding kunt uploaden'],
        ['label' => 'usp_type_link', 'text' => 'Geef de usp een externe link mee'],
        ['label' => 'usp_no_name', 'text' => 'Vul een naam in'],
        ['label' => 'usp_online_offline_tooltip', 'text' => 'Zet de Usps online/offline'],
        ['label' => 'usp', 'text' => 'Usp'],
        ['label' => 'usp_back_overview', 'text' => 'Terug naar overzicht'],
        ['label' => 'usp_status_not_changed', 'text' => 'Usp status niet gewijzigd'],
        ['label' => 'usp_offline', 'text' => 'Usp offline gezet'],
        ['label' => 'usp_online', 'text' => 'Usp online gezet'],
        ['label' => 'usp_no_items', 'text' => 'Geen Usps gevonden'],
        ['label' => 'usp_delete', 'text' => 'Usp verwijderen'],
        ['label' => 'usp_edit', 'text' => 'Usp bewerken'],
        ['label' => 'usp_set_online', 'text' => 'Usp online zetten'],
        ['label' => 'usp_set_offline', 'text' => 'Usp offline zetten'],
        ['label' => 'usp_add', 'text' => 'Usp toevoegen'],
        ['label' => 'usp_all_items', 'text' => 'Alle Usps'],
        ['label' => 'usp_drag', 'text' => 'Sleep de titels om de volgorde te wijzigen'],
        ['label' => 'usp_change_order', 'text' => 'Volgorde wijzigen'],
        ['label' => 'usp_edition', 'text' => 'Usp bewerken'],
        ['label' => 'usp_not_deleted', 'text' => 'Usp niet verwijderd'],
        ['label' => 'usp_deleted', 'text' => 'Usp verwijderd'],
        ['label' => 'usp_not_saved', 'text' => 'Usp niet opgeslagen'],
        ['label' => 'usp_saved', 'text' => 'Usp opgeslagen'],
        ['label' => 'usp_our_usps', 'text' => 'Onze usps'],
        ['label' => 'add_svg_file', 'text' => 'U kunt hier een .SVG bestand uploaden. Indien deze gevuld is wordt deze afbeelding leidend'],
        ['label' => 'usp_page', 'text' => 'Link pagina'],
        ['label' => 'usp_page_tooltip', 'text' => 'U kunt de usp koppelen aan een pagina'],
        ['label' => 'usp_link_tooltip', 'text' => 'U kunt de usp een externe url meegeven'],
        ['label' => 'usp_textline_tooltip', 'text' => 'U kunt de usp een tekstregel meegeven'],
        ['label' => 'usp_type_textline', 'text' => 'Geef de usp een tekst link mee'],
        ['label' => 'usp_textline', 'text' => 'Tekst regel'],
        ['label' => 'usp_title', 'text' => 'Tekst regel'],
    ],
    'en' => [
        ['label' => 'usp_crop_large', 'text' => 'Large'],
        ['label' => 'usp_crop_small', 'text' => 'Small'],
        ['label' => 'usp_menu', 'text' => 'Usps'],
        ['label' => 'usp_images_warning', 'text' => 'You have to save the usp first, before you can upload an image'],
        ['label' => 'usp_type_link', 'text' => 'Give the usp an external link'],
        ['label' => 'usp_no_name', 'text' => 'Fill in a title'],
        ['label' => 'usp_online_offline_tooltip', 'text' => 'Set the Usp online/offline'],
        ['label' => 'usp', 'text' => 'Usp'],
        ['label' => 'usp_back_overview', 'text' => 'Back to overview'],
        ['label' => 'usp_status_not_changed', 'text' => 'Usp status not changed'],
        ['label' => 'usp_offline', 'text' => 'Usp set offline'],
        ['label' => 'usp_online', 'text' => 'Usp set online'],
        ['label' => 'usp_no_items', 'text' => 'No usps found'],
        ['label' => 'usp_delete', 'text' => 'Delete usp'],
        ['label' => 'usp_edit', 'text' => 'Edit usp'],
        ['label' => 'usp_set_online', 'text' => 'Set usp online'],
        ['label' => 'usp_set_offline', 'text' => 'Set usp offline'],
        ['label' => 'usp_add', 'text' => 'Add usp'],
        ['label' => 'usp_all_items', 'text' => 'All Usps'],
        ['label' => 'usp_drag', 'text' => 'Drag the titles to change the order'],
        ['label' => 'usp_change_order', 'text' => 'Change order'],
        ['label' => 'usp_edition', 'text' => 'Edit usp'],
        ['label' => 'usp_not_deleted', 'text' => 'Usp not deleted'],
        ['label' => 'usp_deleted', 'text' => 'Usp deleted'],
        ['label' => 'usp_not_saved', 'text' => 'Usp not saved'],
        ['label' => 'usp_saved', 'text' => 'Usp saved'],
        ['label' => 'usp_our_usps', 'text' => 'Our usps'],
        ['label' => 'add_svg_file', 'text' => 'You can upload an .SVG. When filled this image is leadingd'],
        ['label' => 'usp_page', 'text' => 'Link page'],
        ['label' => 'usp_page_tooltip', 'text' => 'You can connect the usp to a page'],
        ['label' => 'usp_link_tooltip', 'text' => 'You can give usp an external url'],
        ['label' => 'usp_textline_tooltip', 'text' => 'You can give usp an textline'],
        ['label' => 'usp_type_textline', 'text' => 'Give the usp a textline'],
        ['label' => 'usp_textline', 'text' => 'Text line'],
    ],
];

// site translations (front end)
$aNeededSiteTranslations = [
    'nl' => [
        ['label' => 'usp_textline', 'text' => 'Unique selling points', 'editable' => 1],
    ],
    'en' => [
        ['label' => 'usp_textline', 'text' => 'Unique selling points', 'editable' => 1],
    ],
];

// Database checks

if (!$oDb->tableExists('usps')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `usps`';
    if ($bInstall) {

        // add table
        $sQuery = '
        CREATE TABLE `usps` (
          `uspId` int(11) NOT NULL AUTO_INCREMENT,
          `languageId` int(11) NOT NULL DEFAULT \'-1\',
          `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `pageId` int(11) DEFAULT NULL,
          `textLine` varchar(255) COLLATE utf8_unicode_ci,
          `link` text COLLATE utf8_unicode_ci,
          `online` int(1) NOT NULL DEFAULT \'1\',
          `order` int(11) DEFAULT NULL,
          `created` timestamp NULL DEFAULT NULL,
          `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
          `fileId` int(11) DEFAULT NULL,
          `imageId` int(11) DEFAULT NULL,
          PRIMARY KEY (`uspId`),
          KEY `languageId` (`languageId`),
          KEY `pageId` (`pageId`),
          KEY `fileId` (`fileId`),
          KEY `imageId` (`imageId`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if ($oDb->tableExists('usps')) {
    if ($oDb->tableExists('languages')) {
        // check languages constraint
        if (!$oDb->constraintExists('usps', 'languageId', 'languages', 'languageId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `usps`.`languageId` => `languages`.`languageId`';
            if ($bInstall) {
                $oDb->addConstraint('usps', 'languageId', 'languages', 'languageId', 'RESTRICT', 'CASCADE');
            }
        }
    }

    if ($oDb->tableExists('files')) {
        // check files constraint
        if (!$oDb->constraintExists('usps', 'fileId', 'files', 'mediaId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `usps`.`fileId` => `files`.`mediaId`';
            if ($bInstall) {
                $oDb->addConstraint('usps', 'fileId', 'files', 'mediaId', 'SET NULL', 'SET NULL');
            }
        }
    }

    if ($oDb->tableExists('images')) {
        // check images constraint
        if (!$oDb->constraintExists('usps', 'imageId', 'images', 'imageId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `usps`.`imageId` => `images`.`imageId`';
            if ($bInstall) {
                $oDb->addConstraint('usps', 'imageId', 'images', 'imageId', 'SET NULL', 'SET NULL');
            }
        }
    }
}

if ($oDb->tableExists('usps')) {
    if (!$oDb->constraintExists('usps', 'pageId', 'pages', 'pageId')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `usps`.`pageId` => `pages`.`pageId`';
        if ($bInstall) {
            $oDb->addConstraint('usps', 'pageId', 'pages', 'pageId', 'SET NULL', 'CASCADE');
        }
    }
}
