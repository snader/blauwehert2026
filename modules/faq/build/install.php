<?php

// check dependencies
$aDependencyModules = [
    'core',
];

$aNeededAdminControllerRoutes = [
    'faq'             => [
        'module'     => 'faq',
        'controller' => 'faqItem',
    ],
    'faq-categorieen' => [
        'module'     => 'faq',
        'controller' => 'faqItemCategory',
    ],
];

$aNeededClassRoutes = [
    'FAQItem'                => [
        'module' => 'faq',
    ],
    'FAQItemManager'         => [
        'module' => 'faq',
    ],
    'FAQItemCategory'        => [
        'module' => 'faq',
    ],
    'FAQItemCategoryManager' => [
        'module' => 'faq',
    ],
];

$aNeededSiteControllerRoutes = [
    'faq' => [
        'module'     => 'faq',
        'controller' => 'faqItem',
    ],
];

$aNeededModulesForMenu = [
    [
        'name'          => 'faq',
        'icon'             => 'fa-question',
        'linkName'      => 'faq_menu',
        'moduleActions' => [
            ['displayName' => 'Volledig', 'name' => 'faqItems_full'],
        ],
    ],
    [
        'name'             => 'faq-categorieen',
        'icon'             => 'fa-object-group',
        'linkName'         => 'faq_category_menu',
        'parentModuleName' => 'faq',
        'moduleActions'    => [
            ['displayName' => 'Volledig', 'name' => 'faqItemCategories_full'],
        ],
    ],
];

$aNeededTranslations = [
    'nl' => [
        ['label' => 'global_FAQ', 'text' => 'FAQ'],
        ['label' => 'faq_faqs', 'text' => 'FAQ'],
        ['label' => 'faq_menu', 'text' => 'FAQ'],
        ['label' => 'faq_category_menu', 'text' => 'Onderwerpen'],
        ['label' => 'faq_item_deleted', 'text' => 'FAQ verwijderd'],
        ['label' => 'faq_related_categories_tooltip', 'text' => 'Kies een gerelateerd onderwerp'],
        ['label' => 'faq_category_not_deleted', 'text' => 'FAQ-onderwerp kan niet worden verwijderd'],
        ['label' => 'faq_category_deleted', 'text' => 'FAQ-onderwerp is verwijderd'],
        ['label' => 'faq_category_not_saved', 'text' => 'FAQ-onderwerp is niet opgeslagen, niet alle velden zijn (juist) ingevuld'],
        ['label' => 'faq_category_saved', 'text' => 'FAQ-onderwerp is opgeslagen'],
        ['label' => 'faq_item_not_deleted', 'text' => 'FAQ kan niet worden verwijderd'],
        ['label' => 'faq_ite_deleted', 'text' => 'FAQ is verwijderd'],
        ['label' => 'faq_item_not_saved', 'text' => 'FAQ is niet opgeslagen, niet alle velden zijn (juist) ingevuld'],
        ['label' => 'faq_item_saved', 'text' => 'FAQ is opgeslagen'],
        ['label' => 'faq_item_not_edited', 'text' => 'FAQ kan niet worden bewerkt'],
        ['label' => 'faq_categories_drag', 'text' => 'Sleep de titels om de volgorde te veranderen'],
        ['label' => 'faq_categories_change_order', 'text' => 'FAQ-onderwerp volgorde wijzigen'],
        ['label' => 'faq_category_not_changed', 'text' => 'FAQ-onderwerp niet gewijzigd'],
        ['label' => 'faq_category_online', 'text' => 'FAQ-onderwerp online gezet'],
        ['label' => 'faq_category_offline', 'text' => 'FAQ-onderwerp offline gezet'],
        ['label' => 'faq_no_categories', 'text' => 'Er zijn geen FAQ-onderwerpen om weer te geven'],
        ['label' => 'faq_category_not_deletable', 'text' => 'Aan dit onderwerp hangen nog FAQ-berichten'],
        ['label' => 'faq_category_delete', 'text' => 'Verwijder FAQ-onderwerp'],
        ['label' => 'faq_category_edit', 'text' => 'Bewerk FAQ-onderwerp'],
        ['label' => 'faq_category_set_online_tooltip', 'text' => 'FAQ-onderwerp online zetten'],
        ['label' => 'faq_category_set_offline_tooltip', 'text' => 'FAQ-onderwerp offline zetten'],
        ['label' => 'faq_category_name', 'text' => 'Onderwerpnaam'],
        ['label' => 'faq_category_add', 'text' => 'FAQ-onderwerp toevoegen'],
        ['label' => 'faq_all_categories', 'text' => 'Alle FAQ-onderwerpen'],
        ['label' => 'faq_category_set_online', 'text' => 'Zet dit FAQ-onderwerp online of offline'],
        ['label' => 'faq_category', 'text' => 'FAQ onderwerp'],
        ['label' => 'faq_FAQ_item_2', 'text' => 'FAQ'],
        ['label' => 'faq_video_warning', 'text' => 'Videolinks kunnen worden toegevoegd nadat het FAQ-bericht eerst is opgeslagen'],
        ['label' => 'faq_links_warning', 'text' => 'Links kunnen worden toegevoegd nadat het FAQ-bericht eerst is opgeslagen'],
        ['label' => 'faq_files_warning', 'text' => 'Bestanden kunnen worden geüpload nadat het FAQ-bericht eerst is opgeslagen'],
        ['label' => 'faq_images_warning', 'text' => 'Afbeeldingen kunnen worden geüpload nadat het FAQ-bericht eerst is opgeslagen'],
        ['label' => 'faq_source', 'text' => 'Bron'],
        ['label' => 'faq_FAQ_item_2_tooltip', 'text' => 'Vul hier uw FAQ in.'],
        [
            'label' => 'faq_intro_tooltip',
            'text'  => 'Vul hier een korte introductie tekst in.
Deze tekst wordt getoond de overzichten en op de homepage',
        ],
        ['label' => 'faq_intro', 'text' => 'Intro (korte intro tekst)'],
        ['label' => 'faq_related_categories', 'text' => 'Gerelateerde onderwerpen'],
        [
            'label' => 'faq_online_to_tooltip',
            'text'  => 'Geef aan tot wanneer uw artikel zichtbaar moet zijn op de website
- Leeg laten voor altijd online',
        ],
        ['label' => 'faq_date_tooltip', 'text' => 'De datum waarop dit item gearchiveerd wordt in de FAQ-module'],
        ['label' => 'faq_online_from_tooltip', 'text' => 'Geef aan vanaf wanneer uw item online mag staan'],
        ['label' => 'faq_enter_title_tooltip', 'text' => 'Vul de titel in van het FAQ-bericht'],
        ['label' => 'faq_title_tooltip', 'text' => 'De titel van uw item'],
        ['label' => 'faq_set_online_tooltip', 'text' => 'Zet het FAQ-bericht online of offline'],
        ['label' => 'faq_FAQ_item', 'text' => 'FAQ'],
        ['label' => 'faq_not_changed', 'text' => 'FAQ niet gewijzigd'],
        ['label' => 'faq_is_offline', 'text' => 'FAQ offline gezet'],
        ['label' => 'faq_is_online', 'text' => 'FAQ online gezet'],
        ['label' => 'faq_no_FAQ', 'text' => 'Er zijn geen FAQ-berichten weer te geven'],
        ['label' => 'faq_delete', 'text' => 'Verwijder FAQ-bericht'],
        ['label' => 'faq_edit', 'text' => 'Bewerk FAQ-bericht'],
        ['label' => 'faq_set_offline', 'text' => 'FAQ offlinezetten'],
        ['label' => 'faq_set_online', 'text' => 'FAQ online zetten'],
        ['label' => 'faq_online_to', 'text' => 'Online tot'],
        ['label' => 'faq_online_from', 'text' => 'Online vanaf'],
        ['label' => 'faq_add', 'text' => 'FAQ toevoegen'],
        ['label' => 'faq_add_tooltip', 'text' => 'Nieuw FAQ-bericht toevoegen'],
        ['label' => 'faq_all', 'text' => 'Alle FAQ-berichten'],
        ['label' => 'faq_filter', 'text' => 'Filter FAQ-items'],
        ['label' => 'faq_categories', 'text' => 'FAQ-onderwerpen'],
        ['label' => 'faq_add_categories', 'text' => 'Voeg eerst een onderwerp toe'],
        ['label' => 'faq_question', 'text' => 'Vraag'],
        ['label' => 'faq_answer', 'text' => 'Antwoord'],
        ['label' => 'faq_question_tooltip', 'text' => 'Dit zijn de vragen die uw klanten eerst lezen voordat ze het antwoord krijgen'],
        ['label' => 'faq_answer_tooltip', 'text' => 'Vul hier het antwoord in voor de klanten'],
        ['label' => 'faq_enter_question_tooltip', 'text' => 'Vul hier de vraag in voor de klanten'],
        ['label' => 'faq_items_drag', 'text' => 'Sleep de titels om de volgorde te veranderen'],
        ['label' => 'faq_items_change_order', 'text' => 'FAQ-berichten volgorde wijzigen'],
        ['label' => 'faq_items_order_saved', 'text' => 'Volgende van FAQ-berichten is opgeslagen'],
    ],
    'en' => [
        ['label' => 'faq_faqs', 'text' => 'FAQ'],
        ['label' => 'faq_menu', 'text' => 'FAQ'],
        ['label' => 'faq_category_menu', 'text' => 'Subjects'],
        ['label' => 'faq_category_not_deleted', 'text' => 'FAQ subject cannot be deleted'],
        ['label' => 'faq_category_deleted', 'text' => 'FAQ subject has been removed'],
        ['label' => 'faq_category_not_saved', 'text' => 'FAQ subject has not been saved, not all fields are (correctly) filled in'],
        ['label' => 'faq_category_saved', 'text' => 'FAQ subject has been saved'],
        ['label' => 'faq_item_not_deleted', 'text' => 'FAQ item cannot be deleted'],
        ['label' => 'faq_ite_deleted', 'text' => 'FAQ item deleted'],
        ['label' => 'faq_item_not_saved', 'text' => 'FAQ item is not saved, not all fields are (correctly) filled in'],
        ['label' => 'faq_item_saved', 'text' => 'FAQ item is saved'],
        ['label' => 'faq_item_not_edited', 'text' => 'FAQ item cannot be edited'],
        ['label' => 'faq_categories_drag', 'text' => 'Drag and drop the titles to change the order'],
        ['label' => 'faq_categories_change_order', 'text' => 'Change FAQ subjects order'],
        ['label' => 'faq_category_not_changed', 'text' => 'FAQ subject not changed'],
        ['label' => 'faq_category_online', 'text' => 'FAQ subject placed online'],
        ['label' => 'faq_category_offline', 'text' => 'FAQ subject placed offline'],
        ['label' => 'faq_no_categories', 'text' => 'There are no FAQ subjects to display'],
        ['label' => 'faq_category_not_deletable', 'text' => 'This subject still has any FAQ items asssociated'],
        ['label' => 'faq_category_delete', 'text' => 'Delete FAQ subject'],
        ['label' => 'faq_category_edit', 'text' => 'Edit FAQ subject'],
        ['label' => 'faq_category_set_online_tooltip', 'text' => 'Set FAQ subject online'],
        ['label' => 'faq_category_set_offline_tooltip', 'text' => 'Set FAQ subject offline'],
        ['label' => 'faq_category_name', 'text' => 'Category Name'],
        ['label' => 'faq_category_add', 'text' => 'Add FAQ subject'],
        ['label' => 'faq_all_categories', 'text' => 'All FAQ subjects:'],
        ['label' => 'faq_category_set_online', 'text' => 'Set the FAQ subject online or offline'],
        ['label' => 'faq_category', 'text' => 'FAQ subject'],
        ['label' => 'faq_FAQ_item_2', 'text' => 'FAQ Message'],
        ['label' => 'faq_video_warning', 'text' => 'Videolinks can be added after the first FAQ item is saved'],
        ['label' => 'faq_links_warning', 'text' => 'Links can be added after the FAQ item is saved'],
        ['label' => 'faq_files_warning', 'text' => 'Files can be uploaded after the FAQ item page is saved'],
        ['label' => 'faq_images_warning', 'text' => 'Images can be uploaded after the FAQ item is saved'],
        ['label' => 'faq_source', 'text' => 'Source'],
        ['label' => 'faq_FAQ_item_2_tooltip', 'text' => 'Enter the FAQ text.'],
        [
            'label' => 'faq_intro_tooltip',
            'text'  => 'Fill in here a short introduction text.
 This text is shown on the homepage and reviews',
        ],
        ['label' => 'faq_intro', 'text' => 'Intro (short intro text)'],
        ['label' => 'faq_related_categories', 'text' => 'Related subject'],
        ['label' => 'faq_online_to_tooltip', 'text' => 'Enter the offline date < br/>-Leave blank for remaining the FAQ item always online'],
        ['label' => 'faq_date_tooltip', 'text' => 'The date when the article is saved'],
        ['label' => 'faq_online_from_tooltip', 'text' => 'Specify the online date for the FAQ item'],
        ['label' => 'faq_enter_title_tooltip', 'text' => 'Fill in the title of the FAQ item'],
        ['label' => 'faq_title_tooltip', 'text' => 'The title of your article'],
        ['label' => 'faq_set_online_tooltip', 'text' => 'Place the FAQ item online or offline'],
        ['label' => 'faq_FAQ_item', 'text' => 'FAQ item'],
        ['label' => 'faq_not_changed', 'text' => 'Blog entry not changed'],
        ['label' => 'faq_is_offline', 'text' => 'Blog entry placed offline'],
        ['label' => 'faq_is_online', 'text' => 'Blog entry placed online'],
        ['label' => 'faq_no_FAQ', 'text' => 'There are no FAQ items to display'],
        ['label' => 'faq_delete', 'text' => 'Delete FAQ item'],
        ['label' => 'faq_edit', 'text' => 'Edit FAQ item'],
        ['label' => 'faq_set_offline', 'text' => 'Set blog entry offline'],
        ['label' => 'faq_set_online', 'text' => 'Set blog entry online'],
        ['label' => 'faq_online_to', 'text' => 'Online to'],
        ['label' => 'faq_online_from', 'text' => 'Online from'],
        ['label' => 'faq_add', 'text' => 'Add FAQ item'],
        ['label' => 'faq_add_tooltip', 'text' => 'Add a piece of FAQ'],
        ['label' => 'faq_all', 'text' => 'All pieces of FAQ'],
        ['label' => 'faq_filter', 'text' => 'Filter FAQ'],
        ['label' => 'faq_categories', 'text' => 'Subjects FAQ'],
        ['label' => 'faq_add_categories', 'text' => 'Add a subject first'],
        ['label' => 'faq_question', 'text' => 'Question'],
        ['label' => 'faq_answer', 'text' => 'Answer'],
        ['label' => 'faq_question_tooltip', 'text' => 'This is the question that your customers will read first before getting the answer'],
        ['label' => 'faq_answer_tooltip', 'text' => 'Please fill in the answer of the question for the customers'],
        ['label' => 'faq_enter_question_tooltip', 'text' => 'Please fill in the question that your customers could have'],
    ],
    'es' => [
        ['label' => 'faq_faqs', 'text' => 'FAQ'],
        ['label' => 'faq_menu', 'text' => 'FAQ'],
        ['label' => 'faq_category_menu', 'text' => 'Subjects'],
        ['label' => 'faq_category_not_deleted', 'text' => 'No se puede eliminar la tema de noticias'],
        ['label' => 'faq_category_deleted', 'text' => 'Se ha eliminado la tema de noticias'],
        ['label' => 'faq_category_not_saved', 'text' => 'La tema de noticias no se ha guardado, no todos los campos están (correctamente) completados.'],
        ['label' => 'faq_category_saved', 'text' => 'Se ha guardado la tema noticias'],
        ['label' => 'faq_item_not_deleted', 'text' => 'La noticia no se ha eliminada'],
        ['label' => 'faq_ite_deleted', 'text' => 'Noticia eliminada'],
        ['label' => 'faq_item_not_saved', 'text' => 'La noticia no se ha guardado, no todos los campos están (correctamente) almacenados'],
        ['label' => 'faq_item_saved', 'text' => 'La noticia se ha guardado'],
        ['label' => 'faq_item_not_edited', 'text' => 'No se puede editar la noticia'],
        ['label' => 'faq_categories_drag', 'text' => 'Arrastre los textos para cambiar el orden de las categorias'],
        ['label' => 'faq_categories_change_order', 'text' => 'Cambiar el orden de las temas'],
        ['label' => 'faq_category_not_changed', 'text' => 'La tema de noticias no ha cambiado'],
        ['label' => 'faq_category_online', 'text' => 'Tema noticias activada'],
        ['label' => 'faq_category_offline', 'text' => 'Tema noticias desactivada'],
        ['label' => 'faq_no_categories', 'text' => 'No hay ninguna tema de noticias para mostrar'],
        ['label' => 'faq_category_not_deletable', 'text' => 'Esta tema tiene noticias asociadas.'],
        ['label' => 'faq_category_delete', 'text' => 'Eliminar la tema de noticias'],
        ['label' => 'faq_category_edit', 'text' => 'Editar tema de noticias'],
        ['label' => 'faq_category_set_online_tooltip', 'text' => 'Activar tema de noticias'],
        ['label' => 'faq_category_set_offline_tooltip', 'text' => 'Desactivar tema de noticias'],
        ['label' => 'faq_category_name', 'text' => 'Nombre de la tema'],
        ['label' => 'faq_category_add', 'text' => 'Añadir tema de noticias'],
        ['label' => 'faq_all_categories', 'text' => 'Todas las temas de noticias:'],
        ['label' => 'faq_category_set_online', 'text' => 'Activar o desactivar la tema de noticias'],
        ['label' => 'faq_category', 'text' => 'Tema de noticias'],
        ['label' => 'faq_FAQ_item_2', 'text' => 'Noticia'],
        ['label' => 'faq_video_warning', 'text' => 'Pueden añadirse enlaces a videos de Video una vez se haya guardado la noticiapor primera vez'],
        ['label' => 'faq_links_warning', 'text' => 'Pueden añadirse enlaces una vez se haya guardado la noticia'],
        ['label' => 'faq_files_warning', 'text' => 'Pueden cargarse archivos una vez se haya guardado la noticia'],
        ['label' => 'faq_images_warning', 'text' => 'Pueden cargarse imágenes una vez se haya guardado la noticia'],
        ['label' => 'faq_source', 'text' => 'Origen'],
        ['label' => 'faq_FAQ_item_2_tooltip', 'text' => 'Introduzca la noticia'],
        [
            'label' => 'faq_intro_tooltip',
            'text'  => 'Introduzca aquí una breve introducción.
 Este texto aparece en la presentación y comentarios',
        ],
        ['label' => 'faq_intro', 'text' => 'Introducción (texto breve introductorio)'],
        ['label' => 'faq_related_categories', 'text' => 'Tema relacionada:'],
        [
            'label' => 'faq_online_to_tooltip',
            'text'  => 'Especifique la fecha en la que la noticia dejará de estar activa
-Deja el campo en blanco para especificar que siempre estará activa',
        ],
        ['label' => 'faq_date_tooltip', 'text' => 'La fecha en la cual este artículo se archiva en el módulo de noticias'],
        ['label' => 'faq_online_from_tooltip', 'text' => 'Especifique la fecha en la que la noticia estará activa'],
        ['label' => 'faq_enter_title_tooltip', 'text' => 'Introduzca el título de la noticia'],
        ['label' => 'faq_title_tooltip', 'text' => 'El título de la noticia'],
        ['label' => 'faq_set_online_tooltip', 'text' => 'Activar o desactivar la noticia'],
        ['label' => 'faq_FAQ_item', 'text' => 'Noticia'],
        ['label' => 'faq_not_changed', 'text' => 'La entrada del blog no ha cambiado'],
        ['label' => 'faq_is_offline', 'text' => 'Entrada de blog desactivada'],
        ['label' => 'faq_is_online', 'text' => 'Entrada de blog activada'],
        ['label' => 'faq_no_FAQ', 'text' => 'No hay noticias para mostrar'],
        ['label' => 'faq_delete', 'text' => 'Eliminar noticia'],
        ['label' => 'faq_edit', 'text' => 'Editar noticia'],
        ['label' => 'faq_set_offline', 'text' => 'Desactivar la entrada del blog'],
        ['label' => 'faq_set_online', 'text' => 'Activar la entrada del blog'],
        ['label' => 'faq_online_to', 'text' => 'Activa hasta'],
        ['label' => 'faq_online_from', 'text' => 'Activa desde'],
        ['label' => 'faq_add', 'text' => 'Añadir noticia'],
        ['label' => 'faq_add_tooltip', 'text' => 'Añadir una nueva noticia'],
        ['label' => 'faq_all', 'text' => 'Todas las noticias'],
        ['label' => 'faq_filter', 'text' => 'Filtrar noticias'],
        ['label' => 'faq_categories', 'text' => 'Tema de noticias'],
        ['label' => 'faq_add_categories', 'text' => 'Primero se debe agregar una tema'],
        ['label' => 'faq_question', 'text' => 'Pregunta'],
        ['label' => 'faq_answer', 'text' => 'Responder'],
        ['label' => 'faq_question_tooltip', 'text' => 'Esta es la pregunta que sus clientes leerán primero antes de obtener la respuesta'],
        ['label' => 'faq_answer_tooltip', 'text' => 'Por favor, complete la respuesta de la pregunta para los clientes'],
        ['label' => 'faq_enter_question_tooltip', 'text' => 'Por favor, rellene la pregunta que sus clientes podrían tener'],
    ],
];

// add page
if (moduleExists('pages') && $oDb->tableExists('pages')) {
    if (!($oPageNI = PageManager::getPageByName('faqitems', DEFAULT_LANGUAGE_ID))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing page `faqitems`';
        if ($bInstall) {
            $oPageNI             = new Page();
            $oPageNI->languageId = DEFAULT_LANGUAGE_ID;
            $oPageNI->name       = 'faqitems';
            $oPageNI->title      = 'FAQ';
            $oPageNI->content    = '<p>Dit is de pagina waarop de laatste x FAQ-items worden getoond.</p>';
            $oPageNI->shortTitle = 'FAQ';
            $oPageNI->forceUrlPath('/faq');
            $oPageNI->setControllerPath('/modules/faq/site/controllers/faqItem.cont.php');
            $oPageNI->setOnlineChangeable(0);
            $oPageNI->setDeletable(0);
            $oPageNI->setMayHaveSub(0);
            $oPageNI->setLockUrlPath(1);
            $oPageNI->setLockParent(1);
            $oPageNI->setHideImageManagement(1);
            $oPageNI->setHideFileManagement(1);
            $oPageNI->setHideLinkManagement(1);
            $oPageNI->setHideVideoLinkManagement(1);
            if ($oPageNI->isValid()) {
                PageManager::savePage($oPageNI);
            } else {
                _d($oPageNI->getInvalidProps());
                die('Can\'t create page `FAQ`');
            }
        }
    }

    foreach (LocaleManager::getLocalesByFilter(['showAll' => true, 'NOTlanguageId' => DEFAULT_LANGUAGE_ID]) as $oLocale) {
        if (!($oNewPageNI = PageManager::getPageByName('faqitems', $oLocale->languageId))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing page `faqitems` for language `' . strtoupper($oLocale->getLanguage()->code) . '`';
            if ($bInstall) {
                # create news page
                $oNewPageNI             = new Page();
                $oNewPageNI->languageId = $oLocale->languageId;
                $oNewPageNI->name       = 'faqitems';
                $oNewPageNI->title      = 'FAQ';
                $oNewPageNI->content    = '<p>This is the page where the FAQ items are displayed.</p>';
                $oNewPageNI->shortTitle = 'FAQ';
                $oNewPageNI->forceUrlPath('/faq');
                $oNewPageNI->setControllerPath('/modules/faq/site/controllers/faqItem.cont.php');
                $oNewPageNI->setOnlineChangeable(0);
                $oNewPageNI->setDeletable(0);
                $oNewPageNI->setMayHaveSub(0);
                $oNewPageNI->setLockUrlPath(1);
                $oNewPageNI->setLockParent(1);
                $oNewPageNI->setHideImageManagement(1);
                $oNewPageNI->setHideFileManagement(1);
                $oNewPageNI->setHideLinkManagement(1);
                $oNewPageNI->setHideVideoLinkManagement(1);
                if ($oNewPageNI->isValid()) {
                    PageManager::savePage($oNewPageNI);
                } else {
                    _d($oNewPageNI->getInvalidProps());
                    die('Can\'t create page `faqitems`');
                }
            }
        }
    }
}

// Database checks

if (!$oDb->tableExists('faq_items')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `faq_items`';
    if ($bInstall) {

        // add table
        $sQuery = '
        CREATE TABLE `faq_items` (
          `faqItemId` int(11) NOT NULL AUTO_INCREMENT,
          `languageId` int(11) NOT NULL DEFAULT \'-1\',
          `question` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `answer` text COLLATE utf8_unicode_ci,
          `online` tinyint(1) NOT NULL DEFAULT \'1\',
          PRIMARY KEY (`faqItemId`),
          KEY `languageId` (`languageId`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if ($oDb->tableExists('faq_items')) {

    if ($oDb->tableExists('languages')) {
        // check languages constraint
        if (!$oDb->constraintExists('faq_items', 'languageId', 'languages', 'languageId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `faq_items`.`languageId` => `languages`.`languageId`';
            if ($bInstall) {
                $oDb->addConstraint('faq_items', 'languageId', 'languages', 'languageId', 'RESTRICT', 'CASCADE');
            }
        }
    }

    // FAQ items categories
    if (!$oDb->tableExists('faq_item_categories')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing table `faq_item_categories`';
        if ($bInstall) {

            // add table
            $sQuery = '
        CREATE TABLE `faq_item_categories` (
          `faqItemCategoryId` int(11) NOT NULL AUTO_INCREMENT,
          `languageId` int(11) NOT NULL DEFAULT \'-1\',
          `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `online` tinyint(1) NOT NULL DEFAULT \'1\',
          `order` int(11) NOT NULL DEFAULT \'9999\',
          PRIMARY KEY (`faqItemCategoryId`),
          KEY `languageId` (`languageId`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
        ';
            $oDb->query($sQuery, QRY_NORESULT);
        }

        // check languages constraint
        if (!$oDb->constraintExists('faq_item_categories', 'languageId', 'languages', 'languageId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `faq_item_categories`.`languageId` => `languages`.`languageId`';
            if ($bInstall) {
                $oDb->addConstraint('faq_item_categories', 'languageId', 'languages', 'languageId', 'RESTRICT', 'CASCADE');
            }
        }
    }

    if ($oDb->tableExists('faq_items') && $oDb->tableExists('faq_item_categories')) {
        // start FAQ items category relations
        if (!$oDb->tableExists('faq_item_categories_faq_items')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing table `faq_item_categories_faq_items`';
            if ($bInstall) {

                // add table
                $sQuery = '
            CREATE TABLE `faq_item_categories_faq_items` (
              `faqItemCategoryId` int(11) NOT NULL,
              `faqItemId` int(11) NOT NULL,
              `order` int(11) NOT NULL DEFAULT 9999,
              PRIMARY KEY (`faqItemCategoryId`,`faqItemId`),
              KEY `faqItemId` (`faqItemId`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
            ';
                $oDb->query($sQuery, QRY_NORESULT);
            }
        }

        if ($oDb->tableExists('faq_item_categories_faq_items')) {

            // check photo albums constraint
            if (!$oDb->constraintExists('faq_item_categories_faq_items', 'faqItemId', 'faq_items', 'faqItemId')) {
                $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `faq_item_categories_faq_items`.`faqItemId` => `faq_items`.`faqItemId`';
                if ($bInstall) {
                    $oDb->addConstraint('faq_item_categories_faq_items', 'faqItemId', 'faq_items', 'faqItemId', 'CASCADE', 'CASCADE');
                }
            }

            // check images constraint
            if (!$oDb->constraintExists('faq_item_categories_faq_items', 'faqItemCategoryId', 'faq_item_categories', 'faqItemCategoryId')) {
                $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `faq_item_categories_faq_items`.`faqItemCategoryId` => `faq_item_categories`.`faqItemCategoryId`';
                if ($bInstall) {
                    $oDb->addConstraint('faq_item_categories_faq_items', 'faqItemCategoryId', 'faq_item_categories', 'faqItemCategoryId', 'CASCADE', 'CASCADE');
                }
            }
        }

        if ($oDb->tableExists('faq_item_categories_faq_items')) {
            if (!$oDb->columnExists('faq_item_categories_faq_items', 'order')) {
                $aLogs[$sModuleName]['errors'][] = 'Missing column `faq_item_categories_faq_items` in `order`';
                if ($bInstall) {
                    $oDb->addColumn('faq_item_categories_faq_items', 'order', 'int', '11', 'faqItemId', 9999, false);
                }
            }
        }
    }
}

// Add category 'default' as standard
if (class_exists('FAQItemCategoryManager') && $oDb->tableExists('faq_item_categories')) {
    $aFaqItemCategories = FAQItemCategoryManager::getFAQItemCategoriesByFilter(['showAll' => true, 'languageId' => DEFAULT_LANGUAGE_ID]);
    if (count($aFaqItemCategories) == 0) {
        $aLogs[$sModuleName]['errors'][] = 'Missing category `default`';
        if ($bInstall) {
            $oFAQItemCategory             = new FAQItemCategory();
            $oFAQItemCategory->name       = "Default";
            $oFAQItemCategory->languageId = DEFAULT_LANGUAGE_ID;
            $oFAQItemCategory->online     = 1;

            if ($oFAQItemCategory->isValid()) {
                FAQItemCategoryManager::saveFAQItemCategory($oFAQItemCategory);
            } else {
                _d($oFAQItemCategory->getInvalidProps());
                die('Can\'t create category `default`');
            }
        }
    }

    foreach (LocaleManager::getLocalesByFilter(['showAll' => true, 'NOTlanguageId' => DEFAULT_LANGUAGE_ID]) as $oLocale) {
        $aFaqItemCategories = FAQItemCategoryManager::getFAQItemCategoriesByFilter(['showAll' => true, 'languageId' => $oLocale->languageId]);
        if (count($aFaqItemCategories) == 0) {
            $aLogs[$sModuleName]['errors'][] = 'Missing category `default` for language `' . strtoupper($oLocale->getLanguage()->code) . '`';
            if ($bInstall) {
                $oFAQItemCategory             = new FAQItemCategory();
                $oFAQItemCategory->name       = "Default";
                $oFAQItemCategory->languageId = $oLocale->languageId;
                $oFAQItemCategory->online     = 1;

                if ($oFAQItemCategory->isValid()) {
                    FAQItemCategoryManager::saveFAQItemCategory($oFAQItemCategory);
                } else {
                    _d($oFAQItemCategory->getInvalidProps());
                    die('Can\'t create category `default`');
                }
            }
        }
    }
}
