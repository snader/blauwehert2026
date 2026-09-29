<?php

// check folders existance and writing rights
if (moduleExists('brandboxItems')) {
    $aCheckRightFolders = [];

    // get settings for module and template and create all images folders
    $aImageSettings = TemplateSettings::get('brandboxItems', 'images');
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
    'brandbox' => [
        'module'     => 'brandboxItems',
        'controller' => 'brandboxItem',
    ],
];

$aNeededClassRoutes = [
    'BrandboxItem'        => [
        'module' => 'brandboxItems',
    ],
    'BrandboxItemManager' => [
        'module' => 'brandboxItems',
    ],
];

$aNeededSiteControllerRoutes = [
];

$aNeededModulesForMenu = [
    [
        'name'             => 'brandbox',
        'icon'             => 'fa-image',
        'linkName'         => 'brandbox_menu',
        'parentModuleName' => 'paginas',
        'moduleActions'    => [
            ['displayName' => 'Volledig', 'name' => 'brandboxItems_full'],
        ],
    ],
];

$aNeededTranslations = [
    'nl' => [
        ['label' => 'brandbox_video_info', 'text' => 'Let op!: U kunt het beste een video kiezen met een ratio van 16/9. <br/>Als u een video wilt gebruiken dan moet u ook een afbeelding uploaden.'],
        ['label' => 'brandbox_video_warning', 'text' => 'Videolinks kunnen worden toegevoegd nadat de brandbox eerst is opgeslagen'],
        ['label' => 'brandbox_all_items', 'text' => 'Alle brandboxitems'],
        ['label' => 'brandbox_add', 'text' => 'Brandbox toevoegen'],
        ['label' => 'brandbox_set_offline', 'text' => 'Brandbox offline zetten'],
        ['label' => 'brandbox_set_online', 'text' => 'Brandbox online zetten'],
        ['label' => 'brandbox_edit', 'text' => 'Bewerk brandbox'],
        ['label' => 'brandbox_no_items', 'text' => 'Er zijn geen brandboxes om weer te geven'],
        ['label' => 'brandbox_online', 'text' => 'Brandbox online gezet'],
        ['label' => 'brandbox_offline', 'text' => 'Brandbox offline gezet'],
        ['label' => 'brandbox_status_not_changed', 'text' => 'Brandbox niet gewijzigd'],
        ['label' => 'brandbox_change_order', 'text' => 'Brandbox volgorde wijzigen'],
        ['label' => 'brandbox_drag', 'text' => 'Sleep de titels om de volgorde te veranderen'],
        ['label' => 'brandbox_back_overview', 'text' => 'Terug naar het brandbox overzicht'],
        ['label' => 'brandbox_item', 'text' => 'Brandbox'],
        ['label' => 'brandbox_online_offline_tooltip', 'text' => 'Zet de brandbox online OF offline'],
        [
            'label' => 'brandbox_name_tooltip',
            'text'  => 'Deze titel word alleen gebruikt in het CMS.\r\nDeze wordt nergens op de website vertoond.\r\n De titel is alleen bedoelt om de verschillende items van elkaar te onderscheiden.',
        ],
        ['label' => 'brandbox_not_show', 'text' => 'wordt niet getoond'],
        ['label' => 'brandbox_link_page', 'text' => 'Linkpagina'],
        ['label' => 'brandbox_link_page_tooltip', 'text' => 'U kunt de brandbox koppelen aan een pagina of/een nieuwsbericht of een externe link.'],
        ['label' => 'brandbox_link_news', 'text' => 'Link nieuwsbericht'],
        ['label' => 'brandbox_link_news_tooltip', 'text' => 'U kunt de brandbox koppelen aan een pagina of/een nieuwsbericht of een externe link.'],
        ['label' => 'brandbox_link_tooltip', 'text' => 'U kunt de brandbox koppelen aan een pagina of/een nieuwsbericht of een externe link.'],
        ['label' => 'brandbox_type_link', 'text' => 'Typ hier een link'],
        ['label' => 'brandbox_rule', 'text' => 'Regel'],
        ['label' => 'brandbox_images_warning', 'text' => 'Afbeeldingen kunnen pas worden geüpload nadat de brandbox is opgeslagen'],
        ['label' => 'brandbox_item_saved', 'text' => 'Brandbox is opgeslagen'],
        ['label' => 'brandbox_item_not_saved', 'text' => 'Brandbox is niet opgeslagen, niet alle velden zijn (juist) ingevuld'],
        ['label' => 'brandbox_item_deleted', 'text' => 'Brandbox is verwijderd'],
        ['label' => 'brandbox_item_not_deleted', 'text' => 'Brandbox kan niet worden verwijderd'],
        ['label' => 'brandbox_item_edition', 'text' => 'brandbox bewerken'],
        ['label' => 'brandbox_menu', 'text' => 'Brandbox'],
        ['label' => 'brandbox_delete', 'text' => 'Verwijder de brandbox'],
        ['label' => 'brandbox_no_name', 'text' => 'Vul de naam in'],
        ['label' => 'brandbox_select', 'text' => 'Selecteer een brandbox',],
        ['label' => 'brandbox', 'text' => 'Brandbox'],
        ['label' => 'brandbox_add_tooltip', 'text' => 'Voeg toe'],
        ['label' => 'brandbox_vimeo_info', 'text' => 'Let op!: U kunt het beste een video kiezen met een ratio van 16/9. <br/>Als u een video wilt gebruiken dan moet u ook een afbeelding uploaden.'],
        ['label' => 'brandbox_vimeo_warning', 'text' => 'Vimeo videolinks kunnen worden toegevoegd nadat de brandbox eerst is opgeslagen'],
    ],
    'en' => [
        ['label' => 'brandbox_all_items', 'text' => 'All brandboxes'],
        ['label' => 'brandbox_add', 'text' => 'Add brandbox'],
        ['label' => 'brandbox_set_offline', 'text' => 'Brandbox set offline'],
        ['label' => 'brandbox_set_online', 'text' => 'Brandbox set online'],
        ['label' => 'brandbox_edit', 'text' => 'Edit brandbox'],
        ['label' => 'brandbox_no_items', 'text' => 'There are no brandboxes to display'],
        ['label' => 'brandbox_online', 'text' => 'Set brandbox online'],
        ['label' => 'brandbox_offline', 'text' => 'Set brandbox offline'],
        ['label' => 'brandbox_status_not_changed', 'text' => 'Brandbox status has not changed'],
        ['label' => 'brandbox_change_order', 'text' => 'Change brandbox order'],
        ['label' => 'brandbox_drag', 'text' => 'Drag the titles to change the order'],
        ['label' => 'brandbox_back_overview', 'text' => 'Back to the brandbox overview'],
        ['label' => 'brandbox_item', 'text' => 'Brandbox'],
        ['label' => 'brandbox_online_offline_tooltip', 'text' => 'Set the brandbox online or offline'],
        ['label' => 'brandbox_name_tooltip', 'text' => 'This title is only used in the CMS. \r\nIt\'s not shown on the website.\r\nThe title is only to keep the different items apart by using a good title.'],
        ['label' => 'brandbox_not_show', 'text' => 'Not shown'],
        ['label' => 'brandbox_link_page', 'text' => 'Link page'],
        ['label' => 'brandbox_link_page_tooltip', 'text' => 'You can link to a page or a brandbox, a news article or an external link.'],
        ['label' => 'brandbox_link_news', 'text' => 'Link news article'],
        ['label' => 'brandbox_link_news_tooltip', 'text' => 'You can link to a page or a brandbox, a news article or an external link.'],
        ['label' => 'brandbox_link_tooltip', 'text' => 'You can link to a page or a brandbox, a news article or an external link.'],
        ['label' => 'brandbox_type_link', 'text' => 'Type a link'],
        ['label' => 'brandbox_rule', 'text' => 'Rule'],
        ['label' => 'brandbox_images_warning', 'text' => 'Images can\'t be uploaded because the reference item has to be saved first'],
        ['label' => 'brandbox_item_saved', 'text' => 'Brandbox has been saved'],
        ['label' => 'brandbox_item_not_saved', 'text' => 'Brandbox has not been saved, not all fields are (correctly) filled in'],
        ['label' => 'brandbox_item_deleted', 'text' => 'Brandbox has been deleted'],
        ['label' => 'brandbox_item_not_deleted', 'text' => 'Brandbox can\'t be deleted'],
        ['label' => 'brandbox_item_edition', 'text' => 'Edit brandbox'],
        ['label' => 'brandbox_menu', 'text' => 'Brandbox'],
        ['label' => 'brandbox_delete', 'text' => 'Delete the brandbox'],
        ['label' => 'brandbox_no_name', 'text' => 'Fill in the name'],
        ['label' => 'brandbox_select', 'text' => 'Select a brandbox',],
        ['label' => 'brandbox', 'text' => 'Brandbox',],
        ['label' => 'brandbox_video_info', 'text' => 'Please note !: It is best to choose a video with a ratio of 16/9. <br/> If you want to use a video, you must also upload an image.'],
        ['label' => 'brandbox_video_warning', 'text' => 'Video links can be added after the brandbox has first been saved'],
        ['label' => 'brandbox_vimeo_info', 'text' => 'Please note !: It is best to choose a video with a ratio of 16/9. <br/> If you want to use a video, you must also upload an image.'],
        ['label' => 'brandbox_vimeo_warning', 'text' => 'Vimeo video links can be added after the brandbox has first been saved'],
    ],
    'es' => [
        ['label' => 'brandbox_all_items', 'text' => 'Todos los elementos'],
        ['label' => 'brandbox_add', 'text' => 'Añadir elemento'],
        ['label' => 'brandbox_set_offline', 'text' => 'Desactivar elemento'],
        ['label' => 'brandbox_set_online', 'text' => 'Activar elemento'],
        ['label' => 'brandbox_edit', 'text' => 'Editar elemento'],
        ['label' => 'brandbox_no_items', 'text' => 'No hay elementos que mostrar'],
        ['label' => 'brandbox_online', 'text' => 'Activar elemento'],
        ['label' => 'brandbox_offline', 'text' => 'Desactivar elemento'],
        ['label' => 'brandbox_status_not_changed', 'text' => 'El elemento no pudo ser (des)activado'],
        ['label' => 'brandbox_change_order', 'text' => 'Reordenar elementos'],
        ['label' => 'brandbox_drag', 'text' => 'Arrastre los nombres para modificar el orden'],
        ['label' => 'brandbox_back_overview', 'text' => 'Volver al listado'],
        ['label' => 'brandbox_item', 'text' => 'Elemento'],
        ['label' => 'brandbox_online_offline_tooltip', 'text' => '(Des)activa el elemento'],
        ['label' => 'brandbox_name_tooltip', 'text' => 'Este título se usa sólo en el CMS.\r\nPuede mostrarse en cualquier lugar de la web\r\nSólo se usa para aportar una buenas descripción del elemento.'],
        ['label' => 'brandbox_not_show', 'text' => 'no se muestra'],
        ['label' => 'brandbox_link_page', 'text' => 'Enlace a la página'],
        ['label' => 'brandbox_link_page_tooltip', 'text' => 'Puede enlazar a una página, a una noticia en el historial del brandbox, o insertar un enlace externo.'],
        ['label' => 'brandbox_link_news', 'text' => 'Enlace a la noticia'],
        ['label' => 'brandbox_link_news_tooltip', 'text' => 'Puede enlazar a una página, a una noticia en el historial del brandbox, o insertar un enlace externo.'],
        ['label' => 'brandbox_link_tooltip', 'text' => 'Puede enlazar a una página, a una noticia en el historial del brandbox, o insertar un enlace externo.'],
        ['label' => 'brandbox_type_link', 'text' => 'Introduzca un enlace'],
        ['label' => 'brandbox_rule', 'text' => 'Texto'],
        ['label' => 'brandbox_images_warning', 'text' => 'No se pueden subir imágenes hasta que se guarde el elemento al que van a estar asociadas'],
        ['label' => 'brandbox_item_saved', 'text' => 'Elemento guardado satisfactoriamente'],
        ['label' => 'brandbox_item_not_saved', 'text' => 'No se pudo guardar el elemento, no no todos los campos (co'],
        ['label' => 'brandbox_item_deleted', 'text' => 'El elemento ha sido eliminado satisfactoriamente'],
        ['label' => 'brandbox_item_not_deleted', 'text' => 'No se pudo eliminar el elemento'],
        ['label' => 'brandbox_item_edition', 'text' => 'Editar elemento'],
        ['label' => 'brandbox_menu', 'text' => 'Brandbox'],
        ['label' => 'brandbox_delete', 'text' => 'Eliminar elemento'],
        ['label' => 'brandbox_no_name', 'text' => 'Complete el nombre'],
        ['label' => 'brandbox_select', 'text' => 'Selectar uno brandbox elemento',],
        ['label' => 'brandbox', 'text' => 'Brandbox elemento',],
    ],
];

$aNeededSiteTranslations = [
    'nl' => [
        ['label' => 'brandbox_read_more', 'text' => 'Lees meer', 'editable' => 0],
    ],
    'en' => [
        ['label' => 'brandbox_read_more', 'text' => 'Read more', 'editable' => 0],
    ],
];

// Database checks

if (!$oDb->tableExists('brandbox_items')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `brandbox_items`';
    if ($bInstall) {
        // add table
        $sQuery = '
        CREATE TABLE `brandbox_items` (
          `brandboxItemId` int(11) NOT NULL AUTO_INCREMENT,
          `languageId` int(11) NOT NULL DEFAULT \'-1\',
          `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `link` text COLLATE utf8_unicode_ci,
          `pageId` int(11) DEFAULT NULL,
          `newsItemId` int(11) DEFAULT NULL,
          `line1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `line2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `online` int(1) NOT NULL DEFAULT \'1\',
          `order` int(11) DEFAULT NULL,
          `created` timestamp NULL DEFAULT NULL,
          `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
          `imageId` int(11) DEFAULT NULL,
          `mediaId` int(11) DEFAULT NULL,
          PRIMARY KEY (`brandboxItemId`),
          KEY (`languageId`),
          KEY `imageId` (`imageId`),
          KEY `mediaId` (`mediaId`),
          KEY `newsItemId` (`newsItemId`),
          KEY `pageId` (`pageId`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if ($oDb->tableExists('brandbox_items')) {
    if ($oDb->tableExists('languages')) {
        // check languages constraint
        if (!$oDb->constraintExists('brandbox_items', 'languageId', 'languages', 'languageId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `brandbox_items`.`languageId` => `languages`.`languageId`';
            if ($bInstall) {
                $oDb->addConstraint('brandbox_items', 'languageId', 'languages', 'languageId', 'RESTRICT', 'CASCADE');
            }
        }
    }

    if ($oDb->tableExists('pages')) {
        // check pages constraint
        if (!$oDb->constraintExists('brandbox_items', 'pageId', 'pages', 'pageId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `brandbox_items`.`pageId` => `pages`.`pageId`';
            if ($bInstall) {
                $oDb->addConstraint('brandbox_items', 'pageId', 'pages', 'pageId', 'SET NULL', 'CASCADE');
            }
        }
        $aErrors = InstallHelper::pivot('pages', 'pageId', 'brandbox_items', 'brandboxItemId', $bInstall);
        if (count($aErrors)) {
            if (!isset($aLogs[$sModuleName], $aLogs[$sModuleName]['errors'])) {
                $aLogs[$sModuleName]['errors'] = [];
            }

            $aLogs[$sModuleName]['errors'] = array_merge($aLogs[$sModuleName]['errors'], $aErrors);
        }
        unset($aErrors);
    }

    if ($oDb->tableExists('images')) {
        // check images constraint
        if (!$oDb->constraintExists('brandbox_items', 'imageId', 'images', 'imageId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `brandbox_items`.`imageId` => `images`.`imageId`';
            if ($bInstall) {
                $oDb->addConstraint('brandbox_items', 'imageId', 'images', 'imageId', 'SET NULL', 'CASCADE');
            }
        }
    }

    // check media constraint
    if ($oDb->tableExists('media')) {
        if (!$oDb->constraintExists('brandbox_items', 'mediaId', 'media', 'mediaId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `brandbox_items`.`mediaId` => `media`.`mediaId`';
            if ($bInstall) {
                $oDb->addConstraint('brandbox_items', 'mediaId', 'media', 'mediaId', 'SET NULL', 'CASCADE');
            }
        }
    }

    // if newsitems module is installed, check the following
    if ($oDb->tableExists('news_items')) {
        // check news items constraint
        if (!$oDb->constraintExists('brandbox_items', 'newsItemId', 'news_items', 'newsItemId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `brandbox_items`.`newsItemId` => `news_items`.`newsItemId`';
            if ($bInstall) {
                $oDb->addConstraint('brandbox_items', 'newsItemId', 'news_items', 'newsItemId', 'SET NULL', 'CASCADE');
            }
        }
    }
}
