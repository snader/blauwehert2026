<?php

// check folders existance and writing rights
if (moduleExists('DynamicContent')) {
    $aCheckRightFolders = [];

}

// check dependencies
$aDependencyModules = [
    'core',
];

$aNeededAdminControllerRoutes = [
    'dynamic-content' => [
        'module'     => 'dynamicContent',
        'controller' => 'dynamicContent',
    ],
];

$aNeededClassRoutes = [
    'DynamicContent'        => [
        'module' => 'dynamicContent',
    ],
    'DynamicContentManager' => [
        'module' => 'dynamicContent',
    ],
];

$aNeededSiteControllerRoutes = [
];

$aNeededModulesForMenu = [
    [
        'name'             => 'dynamic-content',
        'icon'             => 'fa-exchange',
        'linkName'         => 'dynamiccontent_menu',
        'parentModuleName' => 'instellingen',
        'moduleActions'    => [
            ['displayName' => 'Volledig', 'name' => 'dynamiccontent_full'],
        ],
    ],
];

$aNeededTranslations = [
    'nl' => [
        ['label' => 'dynamiccontent_crop_large', 'text' => 'Lang'],
        ['label' => 'dynamiccontent_crop_small', 'text' => 'Klein'],
        ['label' => 'dynamiccontent_menu', 'text' => 'Dynamic content'],
        ['label' => 'dynamiccontent_images_warning', 'text' => 'U moet het item eerst opslaan voor u een afbeelding kunt uploaden'],
        ['label' => 'dynamiccontent_type', 'text' => 'U moet een type content selecteren'],
        ['label' => 'dynamiccontent_no_name', 'text' => 'Vul een naam in'],
        ['label' => 'dynamiccontent_online_offline_tooltip', 'text' => 'Zet het item online/offline'],
        ['label' => 'dynamiccontent', 'text' => 'Dynamic content'],
        ['label' => 'dynamiccontent_back_overview', 'text' => 'Terug naar overzicht'],
        ['label' => 'dynamiccontent_status_not_changed', 'text' => 'Dynamic content status niet gewijzigd'],
        ['label' => 'dynamiccontent_offline', 'text' => 'Dynamic content offline gezet'],
        ['label' => 'dynamiccontent_online', 'text' => 'Dynamic content online gezet'],
        ['label' => 'dynamiccontent_no_items', 'text' => 'Geen Dynamic content gevonden'],
        ['label' => 'dynamiccontent_delete', 'text' => 'Dynamic content verwijderen'],
        ['label' => 'dynamiccontent_edit', 'text' => 'Dynamic content bewerken'],
        ['label' => 'dynamiccontent_set_online', 'text' => 'Dynamic content online zetten'],
        ['label' => 'dynamiccontent_set_offline', 'text' => 'Dynamic content offline zetten'],
        ['label' => 'dynamiccontent_add', 'text' => 'Dynamic content toevoegen'],
        ['label' => 'dynamiccontent_all_items', 'text' => 'Alle records'],
        ['label' => 'dynamiccontent_not_deleted', 'text' => 'Dynamic content niet verwijderd'],
        ['label' => 'dynamiccontent_deleted', 'text' => 'Dynamic content verwijderd'],
        ['label' => 'dynamiccontent_not_saved', 'text' => 'Dynamic content niet opgeslagen'],
        ['label' => 'dynamiccontent_saved', 'text' => 'Dynamic content opgeslagen'],
        ['label' => 'global_admin_only', 'text' => 'Admin only'],
        ['label' => 'dynamice_content_name_in_use', 'text' => 'Naam reeds in gebruik'],
        ['label' => 'dynamiccontent_admin_only_tooltip', 'text' => 'Alleen administrators kunnen dit veld wijzigen; Staat Admin only aan, dan is het record alleen door een administrator te bewerken.'],

    ],
];

// Database checks

if (!$oDb->tableExists('dynamic_content')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `dynamic_content`';
    if ($bInstall) {

        // add table
        $sQuery = '
        CREATE TABLE IF NOT EXISTS `dynamic_content` (
          `dynamicContentId` int(11) NOT NULL AUTO_INCREMENT,
          `languageId` int(11) NOT NULL DEFAULT \'-1\',
          `name` varchar(75) CHARACTER SET utf8 DEFAULT NULL,
          `content` text CHARACTER SET utf8,
          `online` tinyint(4) NOT NULL DEFAULT \'1\',
          `type` enum(\'text\',\'code\',\'html\') CHARACTER SET utf8 NOT NULL DEFAULT \'text\',
          `adminOnly` tinyint(4) NOT NULL DEFAULT \'0\',
          `created` timestamp NULL DEFAULT NULL,
          `modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`dynamicContentId`),
          KEY (`languageId`)
        ) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if ($oDb->tableExists('dynamic_content')) {

    if ($oDb->tableExists('languages')) {
        // check languages constraint
        if (!$oDb->constraintExists('dynamic_content', 'languageId', 'languages', 'languageId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `dynamic_content`.`languageId` => `languages`.`languageId`';
            if ($bInstall) {
                $oDb->addConstraint('dynamic_content', 'languageId', 'languages', 'languageId', 'RESTRICT', 'CASCADE');
            }
        }
    }

}