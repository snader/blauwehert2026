<?php

// check folders existance and writing rights
if (moduleExists('newsItems')) {
    $aCheckRightFolders = [
        NewsItem::FILES_PATH => true,
    ];

    // get settings for module and template and create all images folders
    $aImageSettings = TemplateSettings::get('newsItems', 'images');
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
    'nieuws'             => [
        'module'     => 'newsItems',
        'controller' => 'newsItem',
    ],
    'nieuws-categorieen' => [
        'module'     => 'newsItems',
        'controller' => 'newsItemCategory',
    ],
];

$aNeededClassRoutes = [
    'NewsItem'                => [
        'module' => 'newsItems',
    ],
    'NewsItemManager'         => [
        'module' => 'newsItems',
    ],
    'NewsItemCategory'        => [
        'module' => 'newsItems',
    ],
    'NewsItemCategoryManager' => [
        'module' => 'newsItems',
    ],
];

$aNeededModulesForMenu = [
    [
        'name'          => 'nieuws',
        'icon'          => 'fa-newspaper-o',
        'linkName'      => 'newsItems_menu',
        'moduleActions' => [
            ['displayName' => 'Volledig', 'name' => 'newsItems_full'],
        ],
    ],
    [
        'name'             => 'nieuws-categorieen',
        'icon'            => 'fa-list',
        'linkName'         => 'newsItemCategories_menu',
        'parentModuleName' => 'nieuws',
        'moduleActions'    => [
            ['displayName' => 'Volledig', 'name' => 'newsItemCategories_full'],
        ],
    ],
];

$aNeededTranslations = [
    'nl' => [
        ['label' => 'newsItems_menu', 'text' => 'Nieuws'],
        ['label' => 'newsItemCategories_menu', 'text' => 'Categorieën'],
        ['label' => 'news_item_deleted', 'text' => 'Nieuwsbericht verwijderd'],
        ['label' => 'news_related_categories_tooltip', 'text' => 'Kies een gerelateerde categorie'],
        ['label' => 'news_category_not_deleted', 'text' => 'Nieuws categorie kan niet worden verwijderd'],
        ['label' => 'news_category_deleted', 'text' => 'Nieuws categorie is verwijderd'],
        ['label' => 'news_category_not_saved', 'text' => 'Nieuws categorie is niet opgeslagen, niet alle velden zijn (juist) ingevuld'],
        ['label' => 'news_category_saved', 'text' => 'Nieuws categorie is opgeslagen'],
        ['label' => 'news_item_not_deleted', 'text' => 'Nieuwsbericht kan niet worden verwijderd'],
        ['label' => 'news_ite_deleted', 'text' => 'Nieuwsbericht is verwijderd'],
        ['label' => 'news_item_not_saved', 'text' => 'Nieuwsbericht is niet opgeslagen, niet alle velden zijn (juist) ingevuld'],
        ['label' => 'news_item_saved', 'text' => 'Nieuwsbericht is opgeslagen'],
        ['label' => 'news_item_not_edited', 'text' => 'Nieuwsbericht kan niet worden bewerkt'],
        ['label' => 'news_categories_drag', 'text' => 'Sleep de titels om de volgorde te veranderen'],
        ['label' => 'news_categories_change_order', 'text' => 'Nieuwsberichten volgorde wijzigen'],
        ['label' => 'news_category_not_changed', 'text' => 'Nieuws categorie niet gewijzigd'],
        ['label' => 'news_category_online', 'text' => 'Nieuws categorie online gezet'],
        ['label' => 'news_category_offline', 'text' => 'Nieuws categorie offline gezet'],
        ['label' => 'news_no_categories', 'text' => 'Er zijn geen nieuws categorieën om weer te geven'],
        ['label' => 'news_category_not_deletable', 'text' => 'Aan deze categorie hangen nog nieuwsberichten'],
        ['label' => 'news_category_delete', 'text' => 'Verwijder nieuws categorie'],
        ['label' => 'news_category_edit', 'text' => 'Bewerk nieuws categorie'],
        ['label' => 'news_category_set_online_tooltip', 'text' => 'Nieuws categorie online zetten'],
        ['label' => 'news_category_set_offline_tooltip', 'text' => 'Nieuws categorie offline zetten'],
        ['label' => 'news_category_name', 'text' => 'Categorienaam'],
        ['label' => 'news_category_add', 'text' => 'Nieuws categorie toevoegen'],
        ['label' => 'news_all_categories', 'text' => 'Alle nieuws categorieën'],
        ['label' => 'news_category_set_online', 'text' => 'Zet de nieuws categorie online OF offline'],
        ['label' => 'news_category', 'text' => 'Nieuws categorie'],
        ['label' => 'news_news_item_2', 'text' => 'Nieuwsbericht'],
        ['label' => 'news_video_warning', 'text' => 'Videolinks kunnen worden toegevoegd nadat het nieuwsbericht eerst is opgeslagen'],
        ['label' => 'news_links_warning', 'text' => 'Links kunnen worden toegevoegd nadat het nieuwsbericht eerst is opgeslagen'],
        ['label' => 'news_files_warning', 'text' => 'Bestanden kunnen worden geüpload nadat het nieuwsbericht eerst is opgeslagen'],
        ['label' => 'news_images_warning', 'text' => 'Afbeeldingen kunnen worden geüpload nadat het nieuwsbericht eerst is opgeslagen'],
        ['label' => 'news_source', 'text' => 'Bron'],
        ['label' => 'news_news_item_2_tooltip', 'text' => 'Vul hier uw Nieuwsbericht in.'],
        [
            'label' => 'news_intro_tooltip',
            'text'  => 'Vul hier een korte introductie tekst in.
Deze tekst wordt getoond de overzichten en op de homepage',
        ],
        ['label' => 'news_intro', 'text' => 'Intro (korte intro tekst)'],
        ['label' => 'news_related_categories', 'text' => 'Gerelateerde categorieën'],
        [
            'label' => 'news_online_to_tooltip',
            'text'  => 'Geef aan tot wanneer uw artikel zichtbaar moet zijn op de website
- Leeg laten voor oneindig online',
        ],
        ['label' => 'news_date_tooltip', 'text' => 'De datum waarop dit artikel gearchiveerd wordt in de Nieuwsmodule'],
        ['label' => 'news_online_from_tooltip', 'text' => 'Geef aan vanaf wanneer uw artikel online mag staan'],
        ['label' => 'news_enter_title_tooltip', 'text' => 'Vul de titel in van het nieuwsbericht'],
        ['label' => 'news_title_tooltip', 'text' => 'De titel van uw artikel'],
        ['label' => 'news_set_online_tooltip', 'text' => 'Zet het nieuwsbericht online OF offline'],
        ['label' => 'news_news_item', 'text' => 'Nieuwsbericht'],
        ['label' => 'news_not_changed', 'text' => 'Nieuwsbericht niet gewijzigd'],
        ['label' => 'news_is_offline', 'text' => 'Nieuwsbericht offline gezet'],
        ['label' => 'news_is_online', 'text' => 'Nieuwsbericht online gezet'],
        ['label' => 'news_no_news', 'text' => 'Er zijn geen nieuwsberichten weer te geven'],
        ['label' => 'news_delete', 'text' => 'Verwijder nieuwsbericht'],
        ['label' => 'news_edit', 'text' => 'Bewerk nieuwsbericht'],
        ['label' => 'news_set_offline', 'text' => 'Nieuwsbericht offlinezetten'],
        ['label' => 'news_set_online', 'text' => 'Nieuwsbericht online zetten'],
        ['label' => 'news_online_to', 'text' => 'Online tot'],
        ['label' => 'news_online_from', 'text' => 'Online vanaf'],
        ['label' => 'news_add', 'text' => 'Nieuwsbericht toevoegen'],
        ['label' => 'news_add_tooltip', 'text' => 'Nieuw nieuwsbericht toevoegen'],
        ['label' => 'news_all', 'text' => 'Alle nieuwsberichten'],
        ['label' => 'news_filter', 'text' => 'Filter nieuws'],
        ['label' => 'news_categories', 'text' => 'Nieuws categorieën'],
        ['label' => 'news_add_categories', 'text' => 'Voeg eerst een categorie toe'],
        ['label' => 'news_item_save_first', 'text' => 'Sla eerst het nieuwsbericht op'],
        ['label' => 'news_item_preview', 'text' => 'Bekijk de preview wanneer de pagina nog offline is'],
    ],
    'en' => [
        ['label' => 'newsItems_menu', 'text' => 'News'],
        ['label' => 'newsItemCategories_menu', 'text' => 'Categories'],
        ['label' => 'news_category_not_deleted', 'text' => 'News category cannot be deleted'],
        ['label' => 'news_category_deleted', 'text' => 'News category has been removed'],
        ['label' => 'news_category_not_saved', 'text' => 'News category has not been saved, not all fields are (correctly) filled in'],
        ['label' => 'news_category_saved', 'text' => 'News category has been saved'],
        ['label' => 'news_item_not_deleted', 'text' => 'News item cannot be deleted'],
        ['label' => 'news_ite_deleted', 'text' => 'News item deleted'],
        ['label' => 'news_item_not_saved', 'text' => 'News item is not saved, not all fields are (correctly) filled in'],
        ['label' => 'news_item_saved', 'text' => 'News item is saved'],
        ['label' => 'news_item_not_edited', 'text' => 'News item cannot be edited'],
        ['label' => 'news_categories_drag', 'text' => 'Drag and drop the titles to change the order'],
        ['label' => 'news_categories_change_order', 'text' => 'Change news categories order'],
        ['label' => 'news_category_not_changed', 'text' => 'News category not changed'],
        ['label' => 'news_category_online', 'text' => 'News category placed online'],
        ['label' => 'news_category_offline', 'text' => 'News category placed offline'],
        ['label' => 'news_no_categories', 'text' => 'There are no news categories to display'],
        ['label' => 'news_category_not_deletable', 'text' => 'This category still has any news items asssociated'],
        ['label' => 'news_category_delete', 'text' => 'Delete news category'],
        ['label' => 'news_category_edit', 'text' => 'Edit news category'],
        ['label' => 'news_category_set_online_tooltip', 'text' => 'Set news category online'],
        ['label' => 'news_category_set_offline_tooltip', 'text' => 'News category offline'],
        ['label' => 'news_category_name', 'text' => 'Category Name'],
        ['label' => 'news_category_add', 'text' => 'Add news category'],
        ['label' => 'news_all_categories', 'text' => 'All news category:'],
        ['label' => 'news_category_set_online', 'text' => 'Set the blog category online or offline'],
        ['label' => 'news_category', 'text' => 'News category'],
        ['label' => 'news_news_item_2', 'text' => 'News Message'],
        ['label' => 'news_video_warning', 'text' => 'Videolinks can be added after the first news item is saved'],
        ['label' => 'news_links_warning', 'text' => 'Links can be added after the News item is saved'],
        ['label' => 'news_files_warning', 'text' => 'Files can be uploaded after the news item page is saved'],
        ['label' => 'news_images_warning', 'text' => 'Images can be uploaded after the news item is saved'],
        ['label' => 'news_source', 'text' => 'Source'],
        ['label' => 'news_news_item_2_tooltip', 'text' => 'Enter the news text.'],
        [
            'label' => 'news_intro_tooltip',
            'text'  => 'Fill in here a short introduction text.
 This text is shown on the homepage and reviews',
        ],
        ['label' => 'news_intro', 'text' => 'Intro (short intro text)'],
        ['label' => 'news_related_categories', 'text' => 'Related category'],
        ['label' => 'news_online_to_tooltip', 'text' => 'Enter the offline date < br/>-Leave blank for remaining the news item always online'],
        ['label' => 'news_date_tooltip', 'text' => 'The date when the article is saved'],
        ['label' => 'news_online_from_tooltip', 'text' => 'Specify the online date for the news item'],
        ['label' => 'news_enter_title_tooltip', 'text' => 'Fill in the title of the news item'],
        ['label' => 'news_title_tooltip', 'text' => 'The title of your article'],
        ['label' => 'news_set_online_tooltip', 'text' => 'Place the News item online or offline'],
        ['label' => 'news_news_item', 'text' => 'News item'],
        ['label' => 'news_not_changed', 'text' => 'Blog entry not changed'],
        ['label' => 'news_is_offline', 'text' => 'Blog entry placed offline'],
        ['label' => 'news_is_online', 'text' => 'Blog entry placed online'],
        ['label' => 'news_no_news', 'text' => 'There are no news items to display'],
        ['label' => 'news_delete', 'text' => 'Delete news item'],
        ['label' => 'news_edit', 'text' => 'Edit news item'],
        ['label' => 'news_set_offline', 'text' => 'Set blog entry offline'],
        ['label' => 'news_set_online', 'text' => 'Set blog entry online'],
        ['label' => 'news_online_to', 'text' => 'Online to'],
        ['label' => 'news_online_from', 'text' => 'Online from'],
        ['label' => 'news_add', 'text' => 'Add news item'],
        ['label' => 'news_add_tooltip', 'text' => 'Add a piece of news'],
        ['label' => 'news_all', 'text' => 'All pieces of news'],
        ['label' => 'news_filter', 'text' => 'Filter News'],
        ['label' => 'news_categories', 'text' => 'Categories news'],
        ['label' => 'news_add_categories', 'text' => 'Add a category first'],
        ['label' => 'news_item_save_first', 'text' => 'Save the news item first'],
        ['label' => 'news_item_preview', 'text' => 'Take a look at the news item if the page is still offline'],
    ],
    'es' => [
        ['label' => 'newsItems_menu', 'text' => 'News'],
        ['label' => 'newsItemCategories_menu', 'text' => 'Categories'],
        ['label' => 'news_category_not_deleted', 'text' => 'No se puede eliminar la categoría de noticias'],
        ['label' => 'news_category_deleted', 'text' => 'Se ha eliminado la categoría de noticias'],
        ['label' => 'news_category_not_saved', 'text' => 'La categoría de noticias no se ha guardado, no todos los campos están (correctamente) completados.'],
        ['label' => 'news_category_saved', 'text' => 'Se ha guardado la categoría noticias'],
        ['label' => 'news_item_not_deleted', 'text' => 'La noticia no se ha eliminada'],
        ['label' => 'news_ite_deleted', 'text' => 'Noticia eliminada'],
        ['label' => 'news_item_not_saved', 'text' => 'La noticia no se ha guardado, no todos los campos están (correctamente) almacenados'],
        ['label' => 'news_item_saved', 'text' => 'La noticia se ha guardado'],
        ['label' => 'news_item_not_edited', 'text' => 'No se puede editar la noticia'],
        ['label' => 'news_categories_drag', 'text' => 'Arrastre los textos para cambiar el orden de las categorias'],
        ['label' => 'news_categories_change_order', 'text' => 'Cambiar el orden de las categorías'],
        ['label' => 'news_category_not_changed', 'text' => 'La categoría de noticias no ha cambiado'],
        ['label' => 'news_category_online', 'text' => 'Categoría noticias activada'],
        ['label' => 'news_category_offline', 'text' => 'Categoría noticias desactivada'],
        ['label' => 'news_no_categories', 'text' => 'No hay ninguna categoría de noticias para mostrar'],
        ['label' => 'news_category_not_deletable', 'text' => 'Esta categoría tiene noticias asociadas.'],
        ['label' => 'news_category_delete', 'text' => 'Eliminar la categoría de noticias'],
        ['label' => 'news_category_edit', 'text' => 'Editar categoría de noticias'],
        ['label' => 'news_category_set_online_tooltip', 'text' => 'Activar categoría de noticias'],
        ['label' => 'news_category_set_offline_tooltip', 'text' => 'Desactivar categoría de noticias'],
        ['label' => 'news_category_name', 'text' => 'Nombre de la categoría'],
        ['label' => 'news_category_add', 'text' => 'Añadir categoría de noticias'],
        ['label' => 'news_all_categories', 'text' => 'Todas las categorías de noticias:'],
        ['label' => 'news_category_set_online', 'text' => 'Activar o desactivar la categoría de noticias'],
        ['label' => 'news_category', 'text' => 'Categoría de noticias'],
        ['label' => 'news_news_item_2', 'text' => 'Noticia'],
        ['label' => 'news_video_warning', 'text' => 'Pueden añadirse enlaces a videos de Video una vez se haya guardado la noticiapor primera vez'],
        ['label' => 'news_links_warning', 'text' => 'Pueden añadirse enlaces una vez se haya guardado la noticia'],
        ['label' => 'news_files_warning', 'text' => 'Pueden cargarse archivos una vez se haya guardado la noticia'],
        ['label' => 'news_images_warning', 'text' => 'Pueden cargarse imágenes una vez se haya guardado la noticia'],
        ['label' => 'news_source', 'text' => 'Origen'],
        ['label' => 'news_news_item_2_tooltip', 'text' => 'Introduzca la noticia'],
        [
            'label' => 'news_intro_tooltip',
            'text'  => 'Introduzca aquí una breve introducción.
 Este texto aparece en la presentación y comentarios',
        ],
        ['label' => 'news_intro', 'text' => 'Introducción (texto breve introductorio)'],
        ['label' => 'news_related_categories', 'text' => 'Categoría relacionada:'],
        [
            'label' => 'news_online_to_tooltip',
            'text'  => 'Especifique la fecha en la que la noticia dejará de estar activa
-Deja el campo en blanco para especificar que siempre estará activa',
        ],
        ['label' => 'news_date_tooltip', 'text' => 'La fecha en la cual este artículo se archiva en el módulo de noticias'],
        ['label' => 'news_online_from_tooltip', 'text' => 'Especifique la fecha en la que la noticia estará activa'],
        ['label' => 'news_enter_title_tooltip', 'text' => 'Introduzca el título de la noticia'],
        ['label' => 'news_title_tooltip', 'text' => 'El título de la noticia'],
        ['label' => 'news_set_online_tooltip', 'text' => 'Activar o desactivar la noticia'],
        ['label' => 'news_news_item', 'text' => 'Noticia'],
        ['label' => 'news_not_changed', 'text' => 'La entrada del blog no ha cambiado'],
        ['label' => 'news_is_offline', 'text' => 'Entrada de blog desactivada'],
        ['label' => 'news_is_online', 'text' => 'Entrada de blog activada'],
        ['label' => 'news_no_news', 'text' => 'No hay noticias para mostrar'],
        ['label' => 'news_delete', 'text' => 'Eliminar noticia'],
        ['label' => 'news_edit', 'text' => 'Editar noticia'],
        ['label' => 'news_set_offline', 'text' => 'Desactivar la entrada del blog'],
        ['label' => 'news_set_online', 'text' => 'Activar la entrada del blog'],
        ['label' => 'news_online_to', 'text' => 'Activa hasta'],
        ['label' => 'news_online_from', 'text' => 'Activa desde'],
        ['label' => 'news_add', 'text' => 'Añadir noticia'],
        ['label' => 'news_add_tooltip', 'text' => 'Añadir una nueva noticia'],
        ['label' => 'news_all', 'text' => 'Todas las noticias'],
        ['label' => 'news_filter', 'text' => 'Filtrar noticias'],
        ['label' => 'news_categories', 'text' => 'Categoría de noticias'],
        ['label' => 'news_add_categories', 'text' => 'Primero se debe agregar una categoría'],
    ],
];

// site translations (front end)
$aNeededSiteTranslations = [
    'nl' => [
        ['label' => 'site_news', 'text' => 'Nieuws', 'editable' => 1],
        ['label' => 'site_source', 'text' => 'Bron', 'editable' => 1],
        ['label' => 'site_read_more', 'text' => 'Lees meer', 'editable' => 1],
        ['label' => 'site_news_archive', 'text' => 'Nieuwsarchief', 'editable' => 1],
        ['label' => 'site_read_more', 'text' => 'Lees meer', 'editable' => 1],
        ['label' => 'site_categories', 'text' => 'Categorieën', 'editable' => 1],
        ['label' => 'site_latest', 'text' => 'Laatste', 'editable' => 1],
        ['label' => 'site_from', 'text' => 'van', 'editable' => 1],
        ['label' => 'site_back_to_overview', 'text' => 'Terug naar het overzicht', 'editable' => 1],
    ],
];

// add page
if (moduleExists('pages') && $oDb->tableExists('pages')) {
    foreach (LocaleManager::getLocalesByFilter(['showAll' => true]) as $oLocale) {
        if (!($oNewPageNI = PageManager::getPageByName('newsitems', $oLocale->languageId))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing page `newsitems` for language `' . strtoupper($oLocale->getLanguage()->code) . '`';
            if ($bInstall) {
                # create news page
                $oNewPageNI             = new Page();
                $oNewPageNI->languageId = $oLocale->languageId;
                $oNewPageNI->name       = 'newsitems';
                $oNewPageNI->title      = 'News';
                $oNewPageNI->content    = '<p>This is the page where the last x news items are displayed.</p>';
                $oNewPageNI->shortTitle = 'News';
                $oNewPageNI->forceUrlPath('/news');
                $oNewPageNI->setControllerPath('/modules/newsItems/site/controllers/newsItem.cont.php');
                $oNewPageNI->setOnlineChangeable(0);
                $oNewPageNI->setDeletable(0);
                $oNewPageNI->setMayHaveSub(0);
                $oNewPageNI->setLockUrlPath(0);
                $oNewPageNI->setLockParent(1);
                $oNewPageNI->setHideImageManagement(1);
                $oNewPageNI->setHideFileManagement(1);
                $oNewPageNI->setHideLinkManagement(1);
                $oNewPageNI->setHideVideoLinkManagement(1);
                if ($oNewPageNI->isValid()) {
                    PageManager::savePage($oNewPageNI);
                } else {
                    _d($oNewPageNI->getInvalidProps());
                    die('Can\'t create page `newsitems`');
                }
            }
        }

        if (!($oNewPageNIArchive = PageManager::getPageByName('newsitems_archive', $oLocale->languageId))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing page `newsitems_archive` for language `' . strtoupper($oLocale->getLanguage()->code) . '`';
            if ($bInstall) {
                # create news archive page
                $oNewPageNIArchive               = new Page();
                $oNewPageNIArchive->languageId   = $oLocale->languageId;
                $oNewPageNIArchive->parentPageId = $oNewPageNI->pageId;
                $oNewPageNIArchive->name         = 'newsitems_archive';
                $oNewPageNIArchive->title        = 'News archive';
                $oNewPageNIArchive->content      = '<p>This is the news archive, here you can find all online news items.</p>';
                $oNewPageNIArchive->shortTitle   = 'News archive';
                $oNewPageNIArchive->forceUrlPath('/news/news-archive');
                $oNewPageNIArchive->setControllerPath('/modules/newsItems/site/controllers/newsItem.cont.php');
                $oNewPageNIArchive->setOnlineChangeable(0);
                $oNewPageNIArchive->setDeletable(0);
                $oNewPageNIArchive->setMayHaveSub(0);
                $oNewPageNIArchive->setLockUrlPath(0);
                $oNewPageNIArchive->setLockParent(1);
                $oNewPageNIArchive->setHideImageManagement(1);
                $oNewPageNIArchive->setHideFileManagement(1);
                $oNewPageNIArchive->setHideLinkManagement(1);
                $oNewPageNIArchive->setHideVideoLinkManagement(1);
                if ($oNewPageNIArchive->isValid()) {
                    PageManager::savePage($oNewPageNIArchive);
                } else {
                    _d($oNewPageNIArchive->getInvalidProps());
                    die('Can\'t create page `newsitems_archive`');
                }
            }
        }
    }
}

// Database checks

if (!$oDb->tableExists('news_items')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `news_items`';
    if ($bInstall) {

        // add table
        $sQuery = '
        CREATE TABLE `news_items` (
          `newsItemId` int(11) NOT NULL AUTO_INCREMENT,
          `languageId` int(11) NOT NULL DEFAULT \'-1\',
          `windowTitle` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `metaKeywords` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `metaDescription` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `intro` text COLLATE utf8_unicode_ci,
          `content` text COLLATE utf8_unicode_ci,
          `shortTitle` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `date` datetime NOT NULL,
          `source` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `onlineFrom` datetime NOT NULL,
          `onlineTo` datetime DEFAULT NULL,
          `online` tinyint(1) NOT NULL DEFAULT \'1\',
          `created` timestamp NULL DEFAULT NULL,
          `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`newsItemId`),
          KEY `languageId` (`languageId`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if ($oDb->tableExists('news_items')) {
    if ($oDb->tableExists('languages')) {
        // check languages constraint
        if (!$oDb->constraintExists('news_items', 'languageId', 'languages', 'languageId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `news_items`.`languageId` => `languages`.`languageId`';
            if ($bInstall) {
                $oDb->addConstraint('news_items', 'languageId', 'languages', 'languageId', 'RESTRICT', 'CASCADE');
            }
        }
    }

    // start image relations
    if (!$oDb->tableExists('news_items_images')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing table `news_items_images`';
        if ($bInstall) {
            // add table
            $sQuery = '
                CREATE TABLE `news_items_images` (
                  `newsItemId` int(11) NOT NULL,
                  `imageId` int(11) NOT NULL,
                  PRIMARY KEY (`newsItemId`, `imageId`),
                  KEY fk_images_newsItemImages (`imageId`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
            ';
            $oDb->query($sQuery, QRY_NORESULT);
        }
    }

    // check news item images constraint
    if ($oDb->tableExists('news_items_images')) {
        if (!$oDb->constraintExists('news_items_images', 'newsItemId', 'news_items', 'newsItemId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `news_items_images`.`newsItemId` => `news_items`.`newsItemId`';
            if ($bInstall) {
                $oDb->addConstraint('news_items_images', 'newsItemId', 'news_items', 'newsItemId', 'RESTRICT', 'CASCADE');
            }
        }

        if (!$oDb->constraintExists('news_items_images', 'imageId', 'images', 'imageId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `news_items_images`.`imageId` => `images`.`imageId`';
            if ($bInstall) {
                $oDb->addConstraint('news_items_images', 'imageId', 'images', 'imageId', 'CASCADE', 'CASCADE');
            }
        }
    }

    // start file relations
    if (!$oDb->tableExists('news_items_files')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing table `news_items_files`';
        if ($bInstall) {
            // add table
            $sQuery = '
                CREATE TABLE `news_items_files` (
                  `newsItemId` int(11) NOT NULL,
                  `mediaId` int(11) NOT NULL,
                  PRIMARY KEY (`newsItemId`, `mediaId`),
                  KEY fk_files_newsItemFiles (`mediaId`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
            ';
            $oDb->query($sQuery, QRY_NORESULT);
        }
    }

    // check news items files constraint
    if ($oDb->tableExists('news_items_files')) {
        if (!$oDb->constraintExists('news_items_files', 'newsItemId', 'news_items', 'newsItemId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `news_items_files`.`newsItemId` => `news_items`.`newsItemId`';
            if ($bInstall) {
                $oDb->addConstraint('news_items_files', 'newsItemId', 'news_items', 'newsItemId', 'RESTRICT', 'CASCADE');
            }
        }

        if (!$oDb->constraintExists('news_items_files', 'mediaId', 'files', 'mediaId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `news_items_files`.`mediaId` => `files`.`mediaId`';
            if ($bInstall) {
                $oDb->addConstraint('news_items_files', 'mediaId', 'files', 'mediaId', 'CASCADE', 'CASCADE');
            }
        }
    }

    // start link relations
    if (!$oDb->tableExists('news_items_links')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing table `news_items_links`';
        if ($bInstall) {
            // add table
            $sQuery = '
                CREATE TABLE `news_items_links` (
                  `newsItemId` int(11) NOT NULL,
                  `mediaId` int(11) NOT NULL,
                  PRIMARY KEY (`newsItemId`, `mediaId`),
                  KEY fk_links_newsItemLinks (`mediaId`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
            ';
            $oDb->query($sQuery, QRY_NORESULT);
        }
    }

    // check news item links constraint
    if ($oDb->tableExists('news_items_links')) {
        if (!$oDb->constraintExists('news_items_links', 'newsItemId', 'news_items', 'newsItemId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `news_items_links`.`newsItemId` => `news_items`.`newsItemId`';
            if ($bInstall) {
                $oDb->addConstraint('news_items_links', 'newsItemId', 'news_items', 'newsItemId', 'RESTRICT', 'CASCADE');
            }
        }

        if (!$oDb->constraintExists('news_items_links', 'mediaId', 'media', 'mediaId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `news_items_links`.`mediaId` => `media`.`mediaId`';
            if ($bInstall) {
                $oDb->addConstraint('news_items_links', 'mediaId', 'media', 'mediaId', 'CASCADE', 'CASCADE');
            }
        }
    }

    // start video link relations
    if (!$oDb->tableExists('news_items_video_links')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing table `news_items_video_links`';
        if ($bInstall) {
            // add table
            $sQuery = '
                CREATE TABLE `news_items_video_links` (
                  `newsItemId` int(11) NOT NULL,
                  `mediaId` int(11) NOT NULL,
                  PRIMARY KEY (`newsItemId`, `mediaId`),
                  KEY fk_videoLinks_newsItemVideoLinks (`mediaId`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
            ';
            $oDb->query($sQuery, QRY_NORESULT);
        }
    }

    // check news item video links constraint
    if ($oDb->tableExists('news_items_video_links')) {
        if (!$oDb->constraintExists('news_items_video_links', 'newsItemId', 'news_items', 'newsItemId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `news_items_video_links`.`newsItemId` => `news_items`.`newsItemId`';
            if ($bInstall) {
                $oDb->addConstraint('news_items_video_links', 'newsItemId', 'news_items', 'newsItemId', 'RESTRICT', 'CASCADE');
            }
        }

        if (!$oDb->constraintExists('news_items_video_links', 'mediaId', 'media', 'mediaId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `news_items_video_links`.`mediaId` => `media`.`mediaId`';
            if ($bInstall) {
                $oDb->addConstraint('news_items_video_links', 'mediaId', 'media', 'mediaId', 'CASCADE', 'CASCADE');
            }
        }
    }
}

// news items categories
if (!$oDb->tableExists('news_item_categories')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `news_item_categories`';
    if ($bInstall) {
        // add table
        $sQuery = '
        CREATE TABLE `news_item_categories` (
          `newsItemCategoryId` int(11) NOT NULL AUTO_INCREMENT,
          `languageId` int(11) NOT NULL DEFAULT \'1\',
          `windowTitle` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `metaKeywords` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `metaDescription` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `urlPart` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `urlPartText` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `online` tinyint(1) NOT NULL DEFAULT \'1\',
          `order` int(11) NOT NULL DEFAULT \'9999\',
          `created` timestamp NULL DEFAULT NULL,
          `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`newsItemCategoryId`),
          KEY (`languageId`),
          UNIQUE KEY `u_newsItemCategories_urlPart` (`urlPart`, `languageId`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }

    // check languages constraint
    if (!$oDb->constraintExists('news_item_categories', 'languageId', 'languages', 'languageId')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `news_item_categories`.`languageId` => `languages`.`languageId`';
        if ($bInstall) {
            $oDb->addConstraint('news_item_categories', 'languageId', 'languages', 'languageId', 'RESTRICT', 'CASCADE');
        }
    }
}

if ($oDb->tableExists('news_items') && $oDb->tableExists('news_item_categories')) {
    // start news items category relations
    if (!$oDb->tableExists('news_item_categories_news_items')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing table `news_item_categories_news_items`';
        if ($bInstall) {

            // add table
            $sQuery = '
            CREATE TABLE `news_item_categories_news_items` (
              `newsItemCategoryId` int(11) NOT NULL,
              `newsItemId` int(11) NOT NULL,
              PRIMARY KEY (`newsItemCategoryId`,`newsItemId`),
              KEY `newsItemId` (`newsItemId`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
            ';
            $oDb->query($sQuery, QRY_NORESULT);
        }
    }

    // check news items categories constraint
    if ($oDb->tableExists('news_item_categories_news_items')) {
        if (!$oDb->constraintExists('news_item_categories_news_items', 'newsItemId', 'news_items', 'newsItemId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `news_item_categories_news_items`.`newsItemId` => `news_items`.`newsItemId`';
            if ($bInstall) {
                $oDb->addConstraint('news_item_categories_news_items', 'newsItemId', 'news_items', 'newsItemId', 'CASCADE', 'CASCADE');
            }
        }

        if (!$oDb->constraintExists('news_item_categories_news_items', 'newsItemCategoryId', 'news_item_categories', 'newsItemCategoryId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `news_item_categories_news_items`.`newsItemCategoryId` => `news_item_categories`.`newsItemCategoryId`';
            if ($bInstall) {
                $oDb->addConstraint('news_item_categories_news_items', 'newsItemCategoryId', 'news_item_categories', 'newsItemCategoryId', 'CASCADE', 'CASCADE');
            }
        }
    }
}

// Add category 'default' as standard
if (class_exists('NewsItemCategoryManager') && $oDb->tableExists('news_item_categories')) {
    $aNewsItemCategories = NewsItemCategoryManager::getNewsItemCategoriesByFilter(['showAll' => true, 'languageId' => DEFAULT_LANGUAGE_ID]);
    if (count($aNewsItemCategories) == 0) {
        $aLogs[$sModuleName]['errors'][] = 'Missing category `default`';
        if ($bInstall) {
            $oNewsItemCategory             = new NewsItemCategory();
            $oNewsItemCategory->name       = "Default";
            $oNewsItemCategory->languageId = DEFAULT_LANGUAGE_ID;
            $oNewsItemCategory->online     = 1;

            if ($oNewsItemCategory->isValid()) {
                NewsItemCategoryManager::saveNewsItemCategory($oNewsItemCategory);
            } else {
                _d($oNewsItemCategory->getInvalidProps());
                die('Can\'t create category `default`');
            }
        }
    }

    foreach (LocaleManager::getLocalesByFilter(['showAll' => true, 'NOTlanguageId' => DEFAULT_LANGUAGE_ID]) as $oLocale) {
        $aNewsItemCategories = NewsItemCategoryManager::getNewsItemCategoriesByFilter(['showAll' => true, 'languageId' => $oLocale->languageId]);
        if (count($aNewsItemCategories) == 0) {
            $aLogs[$sModuleName]['errors'][] = 'Missing category `default` for language `' . strtoupper($oLocale->getLanguage()->code) . '`';
            if ($bInstall) {
                $oNewsItemCategory             = new NewsItemCategory();
                $oNewsItemCategory->name       = "Default";
                $oNewsItemCategory->languageId = $oLocale->languageId;
                $oNewsItemCategory->online     = 1;

                if ($oNewsItemCategory->isValid()) {
                    NewsItemCategoryManager::saveNewsItemCategory($oNewsItemCategory);
                } else {
                    _d($oNewsItemCategory->getInvalidProps());
                    die('Can\'t create category `default`');
                }
            }
        }
    }
}
