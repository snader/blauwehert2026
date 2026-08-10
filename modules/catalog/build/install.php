<?php

// check folders existance and writing rights
if (moduleExists('catalog')) {
    $aCheckRightFolders = [
        '/uploads/images/catalog'                   => true,
        CatalogProduct::IMAGES_PATH                 => true,
        CatalogProduct::IMAGES_PATH . '/cms_thumb'  => true,
        CatalogProduct::IMAGES_PATH . '/crop_small' => true,
        CatalogProduct::IMAGES_PATH . '/detail'     => true,
        CatalogProduct::IMAGES_PATH . '/original'   => true,
    ];
}
// check dependencies
$aDependencyModules = [
    'core',
];

$aNeededAdminControllerRoutes = [
    'catalogus'                           => [
        'module'     => 'catalog',
        'controller' => 'catalogProduct',
    ],
    'catalogus-merken'                    => [
        'module'     => 'catalog',
        'controller' => 'catalogBrand',
    ],
    'catalogus-producttypes'              => [
        'module'     => 'catalog',
        'controller' => 'catalogProductType',
    ],
    'catalogus-producteigenschappen'      => [
        'module'     => 'catalog',
        'controller' => 'catalogProductPropertyType',
    ],
    'catalogus-categorieen'               => [
        'module'     => 'catalog',
        'controller' => 'catalogProductCategory',
    ],
    'catalogus-producteigenschap-groepen' => [
        'module'     => 'catalog',
        'controller' => 'catalogProductPropertyTypeGroup',
    ],
    'catalogus-productkleuren'            => [
        'module'     => 'catalog',
        'controller' => 'catalogProductColor',
    ],
    'catalogus-productmaten'              => [
        'module'     => 'catalog',
        'controller' => 'catalogProductSize',
    ],
];

$aNeededClassRoutes = [
    'CatalogBrand'                                              => [
        'module' => 'catalog',
    ],
    'CatalogBrandManager'                                       => [
        'module' => 'catalog',
    ],
    'CatalogBrandTranslation'                                   => [
        'module' => 'catalog',
    ],
    'CatalogBrandTranslationManager'                            => [
        'module' => 'catalog',
    ],
    'CatalogProduct'                                            => [
        'module' => 'catalog',
    ],
    'CatalogProductCategory'                                    => [
        'module' => 'catalog',
    ],
    'CatalogProductCategoryManager'                             => [
        'module' => 'catalog',
    ],
    'CatalogProductCategoryTranslation'                         => [
        'module' => 'catalog',
    ],
    'CatalogProductCategoryTranslationManager'                  => [
        'module' => 'catalog',
    ],
    'CatalogProductColor'                                       => [
        'module' => 'catalog',
    ],
    'CatalogProductColorManager'                                => [
        'module' => 'catalog',
    ],
    'CatalogProductColorTranslation'                            => [
        'module' => 'catalog',
    ],
    'CatalogProductColorTranslationManager'                     => [
        'module' => 'catalog',
    ],
    'CatalogProductImageRelation'                               => [
        'module' => 'catalog',
    ],
    'CatalogProductImageRelationManager'                        => [
        'module' => 'catalog',
    ],
    'CatalogProductManager'                                     => [
        'module' => 'catalog',
    ],
    'CatalogProductTranslation'                                 => [
        'module' => 'catalog',
    ],
    'CatalogProductTranslationManager'                          => [
        'module' => 'catalog',
    ],
    'CatalogProductPropertyType'                                => [
        'module' => 'catalog',
    ],
    'CatalogProductPropertyTypeGroup'                           => [
        'module' => 'catalog',
    ],
    'CatalogProductPropertyTypeGroupManager'                    => [
        'module' => 'catalog',
    ],
    'CatalogProductPropertyTypeGroupTranslation'                => [
        'module' => 'catalog',
    ],
    'CatalogProductPropertyTypeGroupTranslationManager'         => [
        'module' => 'catalog',
    ],
    'CatalogProductPropertyTypeManager'                         => [
        'module' => 'catalog',
    ],
    'CatalogProductPropertyTypeTranslation'                     => [
        'module' => 'catalog',
    ],
    'CatalogProductPropertyTypeTranslationManager'              => [
        'module' => 'catalog',
    ],
    'CatalogProductPropertyTypePossibleValue'                   => [
        'module' => 'catalog',
    ],
    'CatalogProductPropertyTypePossibleValueManager'            => [
        'module' => 'catalog',
    ],
    'CatalogProductPropertyTypePossibleValueTranslation'        => [
        'module' => 'catalog',
    ],
    'CatalogProductPropertyTypePossibleValueTranslationManager' => [
        'module' => 'catalog',
    ],
    'CatalogProductPropertyValue'                               => [
        'module' => 'catalog',
    ],
    'CatalogProductPropertyValueManager'                        => [
        'module' => 'catalog',
    ],
    'CatalogProductSize'                                        => [
        'module' => 'catalog',
    ],
    'CatalogProductSizeColorRelation'                           => [
        'module' => 'catalog',
    ],
    'CatalogProductSizeColorRelationManager'                    => [
        'module' => 'catalog',
    ],
    'CatalogProductSizeManager'                                 => [
        'module' => 'catalog',
    ],
    'CatalogProductSizeTranslation'                             => [
        'module' => 'catalog',
    ],
    'CatalogProductSizeTranslationManager'                      => [
        'module' => 'catalog',
    ],
    'CatalogProductType'                                        => [
        'module' => 'catalog',
    ],
    'CatalogProductTypeManager'                                 => [
        'module' => 'catalog',
    ],
    'CatalogProductTypeTranslation'                             => [
        'module' => 'catalog',
    ],
    'CatalogProductTypeTranslationManager'                      => [
        'module' => 'catalog',
    ],
    'TaxManager'                                                => [
        'module' => 'catalog',
    ],
];

$aNeededSiteControllerRoutes = [
];

$aNeededModulesForMenu = [
    [
        'name'          => 'catalogus',
        'icon'          => 'fa-shopping-basket',
        'linkName'      => 'catalog_menu',
        'moduleActions' => [
            ['displayName' => 'Volledig', 'name' => 'catalogProducts_full'],
        ],
    ],
    [
        'name'             => 'catalogus-merken',
        'icon'             => 'fa-tag',
        'linkName'         => 'catalog_brand_menu',
        'parentModuleName' => 'catalogus',
        'moduleActions'    => [
            ['displayName' => 'Volledig', 'name' => 'catalogBrands_full'],
        ],
    ],
    [
        'name'             => 'catalogus-producteigenschap-groepen',
        'icon'             => 'fa-object-group',
        'linkName'         => 'catalog_product_property_group_menu',
        'parentModuleName' => 'catalogus',
        'moduleActions'    => [
            ['displayName' => 'Volledig', 'name' => 'catalogProductPropertyGroups_full'],
        ],
    ],
    [
        'name'             => 'catalogus-producteigenschappen',
        'icon'             => 'fa-pencil',
        'linkName'         => 'catalog_product_property_type_menu',
        'parentModuleName' => 'catalogus',
        'moduleActions'    => [
            ['displayName' => 'Volledig', 'name' => 'catalogProductPropertyTypes_full'],
        ],
    ],
    [
        'name'             => 'catalogus-producttypes',
        'icon'             => 'fa-th',
        'linkName'         => 'catalog_product_type_menu',
        'parentModuleName' => 'catalogus',
        'moduleActions'    => [
            ['displayName' => 'Volledig', 'name' => 'catalogProductTypes_full'],
        ],
    ],
    [
        'name'             => 'catalogus-categorieen',
        'icon'             => 'fa-list',
        'linkName'         => 'catalog_product_category_menu',
        'parentModuleName' => 'catalogus',
        'moduleActions'    => [
            ['displayName' => 'Volledig', 'name' => 'catalogProductCategories_full'],
        ],
    ],
    [
        'name'             => 'catalogus-productkleuren',
        'icon'             => 'fa-paint-brush',
        'linkName'         => 'catalog_product_color_menu',
        'parentModuleName' => 'catalogus',
        'moduleActions'    => [
            ['displayName' => 'Volledig', 'name' => 'catalogProductColors_full'],
        ],
    ],
    [
        'name'             => 'catalogus-productmaten',
        'icon'             => 'fa-arrows-v',
        'linkName'         => 'catalog_product_size_menu',
        'parentModuleName' => 'catalogus',
        'moduleActions'    => [
            ['displayName' => 'Volledig', 'name' => 'catalogProductSizes_full'],
        ],
    ],
];

$aNeededTranslations = [
    'nl' => [
        ['label' => 'catalog_product_size_menu', 'text' => 'Maten'],
        ['label' => 'catalog_product_color_menu', 'text' => 'Kleuren'],
        ['label' => 'catalog_product_property_group_menu', 'text' => 'Product eigenschap groepen'],
        ['label' => 'catalog_product_category_menu', 'text' => 'Categorieën'],
        ['label' => 'catalog_product_type_menu', 'text' => 'Product types'],
        ['label' => 'catalog_product_property_type_menu', 'text' => 'Product eigenschappen'],
        ['label' => 'catalog_brand_menu', 'text' => 'Merken'],
        ['label' => 'catalog_menu', 'text' => 'Catalogus'],
        ['label' => 'catalog_value_not_saved', 'text' => 'Waarde niet opgeslagen'],
        ['label' => 'catalog_category_not_deleted', 'text' => 'Categorie niet verwijderd'],
        ['label' => 'catalog_no_related_products', 'text' => 'Geen gerelateerd producten gekoppeld'],
        ['label' => 'catalog_stock_not_added', 'text' => 'Voorraad <u>niet</u> toegevoegd, combinatie bestaat al!'],
        ['label' => 'catalog_product_not_saved', 'text' => 'Product is niet opgeslagen, niet alle velden zijn (juist) ingevuld'],
        ['label' => 'catalog_category_no_deleted', 'text' => 'Categorie kan niet worden verwijderd'],
        ['label' => 'catalog_category_deleted', 'text' => 'Categorie is verwijderd'],
        ['label' => 'catalog_category_order_saved', 'text' => 'Categoriestructuur is opgeslagen'],
        ['label' => 'catalog_category_not_saved', 'text' => 'Categorie is niet opgeslagen, niet alle velden zijn (juist) ingevuld'],
        ['label' => 'catalog_category_saved', 'text' => 'Categorie is opgeslagen'],
        ['label' => 'catalog_all_links', 'text' => ', en alle koppelingen met producten'],
        ['label' => 'catalog_product_property_add', 'text' => 'Producteigenschap toevoegen'],
        ['label' => 'catalog_brand_name_tooltip', 'text' => 'Vul de naam in'],
        ['label' => 'catalog_online_offline_tooltip', 'text' => 'Zet de product online OF offline'],
        ['label' => 'catalog_product_number', 'text' => 'Productnaam'],
        ['label' => 'catalog_stock_updating', 'text' => 'Voorraad bijwerken'],
        ['label' => 'catalog_choose_image_for_color', 'text' => 'Kies een afbeelding voor de kleur'],
        ['label' => 'catalog_product_type_not_deleted', 'text' => 'Product type kan niet worden verwijderd'],
        ['label' => 'catalog_product_type_deleted', 'text' => 'Product type is verwijderd'],
        ['label' => 'catalog_product_type_not_saved', 'text' => 'Product type is niet opgeslagen, niet alle velden zijn (juist) ingevuld'],
        ['label' => 'catalog_product_type_saved', 'text' => 'Product type is opgeslagen'],
        ['label' => 'catalog_product_types', 'text' => 'Product types'],
        ['label' => 'catalog_size_not_deleted', 'text' => 'Maat kan niet worden verwijderd'],
        ['label' => 'catalog_size_deleted', 'text' => 'Maat is verwijderd'],
        ['label' => 'catalog_size_not_saved', 'text' => 'Maat is niet opgeslagen, niet alle velden zijn (juist) ingevuld'],
        ['label' => 'catalog_size_saved', 'text' => 'Maat is opgeslagen'],
        ['label' => 'catalog_property_group_not_deleted', 'text' => 'Eigenschapgroep kan niet worden verwijderd'],
        ['label' => 'catalog_property_group_deleted', 'text' => 'Eigenschapgroep is verwijderd'],
        ['label' => 'catalog_property_group_not_saved', 'text' => 'Eigenschapgroep is niet opgeslagen, niet alle velden zijn (juist) ingevuld'],
        ['label' => 'catalog_property_group_saved', 'text' => 'Eigenschapgroep is opgeslagen'],
        ['label' => 'catalog_product_type_property_group', 'text' => 'Producttype specifieke eigenschapgroepen'],
        ['label' => 'catalog_product_property_not_deleted', 'text' => 'Eigenschap kan niet worden verwijderd'],
        ['label' => 'catalog_product_property_deleted', 'text' => 'Eigenschap is verwijderd'],
        ['label' => 'catalog_property_value_not_saved', 'text' => 'Waarde is niet opgeslagen, niet alle velden zijn (juist) ingevuld'],
        ['label' => 'catalog_property_value_saved', 'text' => 'Waarde is opgeslagen'],
        ['label' => 'catalog_product_properties_not_saved', 'text' => 'Eigenschap is niet opgeslagen, niet alle velden zijn (juist) ingevuld'],
        ['label' => 'catalog_product_properties_saved', 'text' => 'Product eigenschap is opgeslagen'],
        ['label' => 'catalog_product_type_properties', 'text' => 'Producttype specifieke eigenschappen'],
        ['label' => 'catalog_color_not_deleted', 'text' => 'Kleur kan niet worden verwijderd'],
        ['label' => 'catalog_color_deleted', 'text' => 'Kleur is verwijderd'],
        ['label' => 'catalog_color_not_saved', 'text' => 'Kleur is niet opgeslagen, niet alle velden zijn (juist) ingevuld'],
        ['label' => 'catalog_color_saved', 'text' => 'Kleur is opgeslagen'],
        ['label' => 'catalog_brand_not_deleted', 'text' => 'Merk kan niet worden verwijderd'],
        ['label' => 'catalog_brand_deleted', 'text' => 'Merk is verwijderd'],
        ['label' => 'catalog_brand_not_saved', 'text' => 'Merk is niet opgeslagen, niet alle velden zijn (juist) ingevuld'],
        ['label' => 'catalog_brand_saved', 'text' => 'Merk is opgeslagen'],
        ['label' => 'catalog_size_back_overview', 'text' => 'Terug naar het maten overzicht'],
        ['label' => 'catalog_no_sizes', 'text' => 'Er zijn geen maten om weer te geven'],
        ['label' => 'catalog_size_with_products', 'text' => 'Aan deze maat hangen nog producten'],
        ['label' => 'catalog_size_delete', 'text' => 'Verwijder maat'],
        ['label' => 'catalog_size_edit', 'text' => 'Bewerk maat'],
        ['label' => 'catalog_size_add', 'text' => 'Maat toevoegen'],
        ['label' => 'catalog_all_sizes', 'text' => 'Alle maten'],
        ['label' => 'catalog_color_back_overview', 'text' => 'Terug naar het kleuren overzicht'],
        ['label' => 'catalog_color_drag', 'text' => 'Sleep de kleuren om de volgorde te veranderen'],
        ['label' => 'catalog_color_change_order', 'text' => 'Kleurenen volgorde wijzigen'],
        ['label' => 'catalog_no_colors', 'text' => 'Er zijn geen kleuren om weer te geven'],
        ['label' => 'catalog_color_with_products', 'text' => 'Aan deze kleur hangen nog producten'],
        ['label' => 'catalog_color_delete', 'text' => 'Verwijder kleur'],
        ['label' => 'catalog_color_edit', 'text' => 'Bewerk kleur'],
        ['label' => 'catalog_color_add', 'text' => 'Kleur toevoegen'],
        ['label' => 'catalog_all_colors', 'text' => 'Alle kleuren'],
        [
            'label' => 'catalog_property_group_tooltip_set',
            'text'  => 'Maak hier een nieuwe producttype specifieke eigenschapgroep aan. Hier wordt alleen de naam aangemaakt.<br />Voorbeelden voor een `TV`:<br />- Beeldscherm<br />- Aansluitingen<br />- Gewicht en omvang<br />- etc',
        ],
        ['label' => 'catalog_property_group_back_overview', 'text' => 'Terug naar het eigenschapgroepen overzicht'],
        ['label' => 'catalog_property_group_change_order', 'text' => 'Eigenschapgroepen volgorde wijzigen'],
        ['label' => 'catalog_not_property_group', 'text' => 'Er zijn geen eigenschapgroepen om weer te geven met dit filter'],
        ['label' => 'catalog_property_group_with_properties', 'text' => 'Aan deze groep hangen nog eigenschappen'],
        ['label' => 'catalog_property_group_delete', 'text' => 'Verwijder eigenschapgroep (deze wordt ook verwijderd van de producten die deze hebben)'],
        ['label' => 'catalog_property_group_edit', 'text' => 'Bewerk eigenschapgroep'],
        ['label' => 'catalog_property_group_add', 'text' => 'Eigenschapgroep toevoegen'],
        ['label' => 'catalog_property_group_found', 'text' => 'Gevonden eigenschapgroepen'],
        ['label' => 'catalog_filter_property_groups', 'text' => 'Filter eigenschapgroepen'],
        ['label' => 'catalog_category_name', 'text' => 'Categorienaam'],
        ['label' => 'catalog_category_online_offline_tooltip', 'text' => 'Zet de categorie online OF offline'],
        ['label' => 'catalog_category', 'text' => 'Categorieën'],
        ['label' => 'catalog_category_back_overview', 'text' => 'Terug naar het categorieën overzicht'],
        ['label' => 'catalog_categories_drag', 'text' => 'Sleep de categorie titels om de structuur te veranderen'],
        ['label' => 'catalog_category_change_structure', 'text' => 'Product categorie structuur wijzigen'],
        ['label' => 'catalog_category_status_not_changed', 'text' => 'Categorie niet gewijzigd'],
        ['label' => 'catalog_category_status_online', 'text' => 'Categorie online gezet'],
        ['label' => 'catalog_category_status_offline', 'text' => 'Categorie offline gezet'],
        ['label' => 'catalog_no_category', 'text' => 'Er zijn geen categorieën om weer te geven'],
        ['label' => 'catalog_category_delete', 'text' => 'Verwijder eerst alle gerelateerde sub categoriën en producten'],
        ['label' => 'catalog_category_edit', 'text' => 'Product categorie bewerken'],
        ['label' => 'catalog_category_set_online', 'text' => 'Product categorie online zetten'],
        ['label' => 'catalog_category_set_offline', 'text' => 'Product categorie offline zetten'],
        ['label' => 'catalog_add_sub_category', 'text' => 'Sub categorie onder deze categorie toevoegen'],
        ['label' => 'catalog_add_main_category', 'text' => 'Hoofdcategorie toevoegen'],
        ['label' => 'catalog_categories_all', 'text' => 'Alle product categorieën'],
        ['label' => 'catalog_back_overview_product_type', 'text' => 'Terug naar het product types overzicht'],
        ['label' => 'catalog_set_with_genders', 'text' => 'Maak gebruik van geslacht voor dit type product'],
        ['label' => 'catalog_unset_with_genders', 'text' => 'Maak geen gebruik van geslacht voor dit type product'],
        ['label' => 'catalog_with_genders_tooltip', 'text' => 'Dit type product heeft meerdere geslacht per toegevoegd product (niet meer te wijzigen na toevoegen eerste product)'],
        ['label' => 'catalog_unset_with_colors', 'text' => 'Maak geen gebruik van kleuren voor dit type product'],
        ['label' => 'catalog_set_with_colors', 'text' => 'Maak gebruik van kleuren voor dit type product'],
        ['label' => 'catalog_with_colors_tooltip', 'text' => 'Dit type product heeft meerdere kleuren per toegevoegd product (niet meer te wijzigen na toevoegen eerste product)'],
        ['label' => 'catalog_product_type_warning', 'text' => 'Zolang er aan dit type product, producten hangen kan deze waarde niet gewijzigd worden'],
        ['label' => 'catalog_unset_with_sizes', 'text' => 'Maak geen gebruik van maten voor dit type product'],
        ['label' => 'catalog_set_with_sizes', 'text' => 'Maak gebruik van maten voor dit type product'],
        ['label' => 'catalog_with_sizes_tooltip', 'text' => 'Dit type product heeft meerdere maten per toegevoegd product (niet meer te wijzigen na toevoegen eerste product)'],
        ['label' => 'catalog_set_product_type_tooltip', 'text' => 'Vul het product type in'],
        ['label' => 'catalog_product_type_order', 'text' => 'Product types volgorde wijzigen'],
        ['label' => 'catalog_value_not_deleted', 'text' => 'Waarde kon niet worden verwijderd'],
        ['label' => 'catalog_value_deleted', 'text' => 'Waarde succesvol verwijderd'],
        ['label' => 'catalog_value_saved', 'text' => 'Waarde opgeslagen'],
        ['label' => 'catalog_value_not_changed', 'text' => 'Waarde kon niet worden gewijzigd'],
        ['label' => 'catalog_value_reordered', 'text' => 'Waarde volgorde gewijzigd'],
        ['label' => 'catalog_change_value', 'text' => 'Waarde wijzigen'],
        ['label' => 'catalog_product_properties_warning', 'text' => 'Mogelijke waarden kunnen worden toegevoegd nadat de product eigenschap eerst is opgeslagen en alleen voor type SELECT en CHECKBOX'],
        ['label' => 'catalog_edit_value', 'text' => 'Bewerk waarde'],
        ['label' => 'catalog_saved_values_tooltip', 'text' => 'Sleep de regels om de volgorde aan te passen'],
        ['label' => 'catalog_saved_values', 'text' => 'Reeds toegevoegde waarden'],
        ['label' => 'catalog_save_value', 'text' => 'Waarde opslaan'],
        ['label' => 'catalog_property_set_value', 'text' => 'Vul de waarde in'],
        ['label' => 'catalog_property_possible_values_tooltip', 'text' => 'Als er wordt gekozen voor SELECT of CHECKBOX kunnen hier waarden worden toegevoegd die dan gekozen kunnen worden'],
        ['label' => 'catalog_property_possible_values', 'text' => 'Mogelijke waarden'],
        ['label' => 'catalog_property_group_choose', 'text' => 'Kies een eigenschapgroep'],
        ['label' => 'catalog_property_group_select', 'text' => 'Kies eerst een product type'],
        ['label' => 'catalog_property_group_tooltip', 'text' => 'De eigenschapgroep waarvoor deze eigenschap van toepasssing is'],
        ['label' => 'catalog_product_type_tooltip', 'text' => 'Het producttype waarvoor deze eigenschap van toepasssing is'],
        ['label' => 'catalog_no_filter', 'text' => 'Geen filter'],
        ['label' => 'catalog_type_filter_select', 'text' => 'Kies een filter type'],
        [
            'label' => 'catalog_type_filter_tooltip',
            'text'  => 'Kies een type weergave voor het filter<br />- TEXT: zoeken op een (deel) van de ingevoerde waarde<br />- CHECKBOX: meerdere opties kunnen aanvinken<br />- SELECT: selecteer 1 waarde van een eigenschap<br />- MIN-MAX: filteren tussen min en max waarde (getallen)',
        ],
        ['label' => 'catalog_type_filter', 'text' => 'Type filter'],
        ['label' => 'catalog_select_entry', 'text' => 'Kies een invoer type'],
        [
            'label' => 'catalog_input_type_tooltip',
            'text'  => 'De manier van het invoeren van de gegevens bij een product<br />SELECT: kies 1 vooraf gedefinieerde waarde uit een dropdown/selectbox<br />CHECKBOX: vink meerdere vooraf gedefinieerde waarden aan<br />TEXT: vul vrij een waarde in in een tekst veld',
        ],
        ['label' => 'catalog_input_type', 'text' => 'Type invoer'],
        ['label' => 'catalog_product_feature_tooltip', 'text' => 'Vul de producteigenschap in'],
        ['label' => 'catalog_product_feature', 'text' => 'Product eigenschap'],
        [
            'label' => 'catalog_product_features_tooltip',
            'text'  => 'Maak hier een nieuwe producttype specifieke producteigenschap aan. Hier wordt alleen de naam aangemaakt.<br />Voorbeelden voor een `bed`:<br />- Voetbord breedte<br />- voetbord hoogte<br />- hoofdbord breedte x diepte x hoogte<br />- etc',
        ],
        ['label' => 'catalog_product_feature_back_overview', 'text' => 'Terug naar het producteigenschappen overzicht'],
        ['label' => 'catalog_product_type_drag_names', 'text' => 'Sleep de namen om de volgorde te veranderen'],
        ['label' => 'catalog_product_type_change_order', 'text' => 'Producteigenschappen volgorde wijzigen'],
        ['label' => 'catalog_no_product_types', 'text' => 'Er zijn geen product types om weer te geven'],
        ['label' => 'catalog_product_type_with_products', 'text' => 'Aan dit type hangen nog producten'],
        ['label' => 'catalog_product_type_delete', 'text' => 'Verwijder product type'],
        ['label' => 'catalog_product_type_edit', 'text' => 'Producttype bewerken'],
        ['label' => 'catalog_with_genders', 'text' => 'Met geslacht'],
        ['label' => 'catalog_with_colors', 'text' => 'Met kleuren'],
        ['label' => 'catalog_with_sizes', 'text' => 'Met maten'],
        ['label' => 'catalog_product_type_add', 'text' => 'Product type toevoegen'],
        ['label' => 'catalog_product_types_all', 'text' => 'Alle product types'],
        ['label' => 'catalog_property_group', 'text' => 'Eigenschapgroep'],
        ['label' => 'catalog_no_product_properties', 'text' => 'Er zijn geen producteigenschappen om weer te geven met dit filter'],
        ['label' => 'catalog_product_properties_delete_tooltip', 'text' => 'Verwijder producteigenschap (deze wordt ook verwijderd van de producten die deze hebben)'],
        ['label' => 'catalog_product_properties_edit', 'text' => 'Bewerken producteigenschappen'],
        ['label' => 'catalog_filter_type', 'text' => 'Filter type'],
        ['label' => 'catalog_product_features', 'text' => 'Producteigenschap'],
        ['label' => 'catalog_product_properties_found', 'text' => 'Gevonden producteigenschappen'],
        ['label' => 'catalog_product_properties_filter', 'text' => 'Filter producteigenschappen'],
        ['label' => 'catalog_product_properties_all', 'text' => 'Alle eigenschapgroepen'],
        ['label' => 'catalog_product_type_choose', 'text' => 'Kies eerst een product type'],
        ['label' => 'catalog_brand_drag_names', 'text' => 'Sleep de merknamen om de volgorde te veranderen'],
        ['label' => 'catalog_brand_change_order', 'text' => 'Merken volgorde wijzigen'],
        ['label' => 'catalog_brand_back_overview', 'text' => 'Terug naar het merken overzicht'],
        ['label' => 'catalog_brand_online_offline_tooltip', 'text' => 'Zet het merk online OF offline'],
        ['label' => 'catalog_brand_status_not_changed', 'text' => 'Merk niet gewijzigd'],
        ['label' => 'catalog_brand_status_offline', 'text' => 'Merk offline gezet'],
        ['label' => 'catalog_brand_status_online', 'text' => 'Merk online gezet'],
        ['label' => 'catalog_brand_name', 'text' => 'Merknaam'],
        ['label' => 'catalog_add_brand', 'text' => 'Merk toevoegen'],
        ['label' => 'catalog_stock_not_updated', 'text' => 'Voorraad kon <u>niet</u> worden bijgewerkt'],
        ['label' => 'catalog_stock_updated', 'text' => 'Voorraad is bijgewerkt'],
        ['label' => 'catalog_stock_not_deleted', 'text' => 'Voorraad kan niet worden verwijderd'],
        ['label' => 'catalog_stock_deleted', 'text' => 'Voorraad is verwijderd'],
        ['label' => 'catalog_product_not_deleted', 'text' => 'Product kan niet worden verwijderd'],
        ['label' => 'catalog_product_deleted', 'text' => 'Product is verwijderd'],
        ['label' => 'catalog_not_added_stock', 'text' => 'Voorraad <u>niet</u> toegevoegd, niet alle velden zijn (juist) ingevuld'],
        ['label' => 'catalog_stock_added', 'text' => 'Voorraad toegevoegd'],
        ['label' => 'catalog_product_saved', 'text' => 'Product is opgeslagen'],
        ['label' => 'catalog_not_valid_google_category', 'text' => 'Dit is geen geldige Google Categorie'],
        ['label' => 'catalog_status_added', 'text' => 'Het product werd toegevoegd'],
        ['label' => 'catalog_status_removed', 'text' => 'Het product werd succesvol verwijderd'],
        ['label' => 'catalog_related_product', 'text' => 'Gerelateerde product'],
        ['label' => 'catalog_related_products_warning', 'text' => 'Gerelateerde producten eigenschappen kunnen worden toegevoegd nadat het product eerst is opgeslagen'],
        ['label' => 'catalog_related_products', 'text' => 'Gerelateerde producten'],
        ['label' => 'catalog_no_type', 'text' => 'Er zijn geen type-specifieke eigenschappen voor dit product'],
        ['label' => 'catalog_type_warning', 'text' => 'Type-specifieke eigenschappen kunnen worden toegevoegd nadat het product eerst is opgeslagen'],
        ['label' => 'catalog_type_properties', 'text' => 'Type-specifieke eigenschappen'],
        ['label' => 'catalog_color_size_relation_warning', 'text' => 'Voorraad kan beheerd worden nadat het product eerst is opgeslagen'],
        ['label' => 'catalog_no_size_color_relations', 'text' => 'Er zijn nog geen voorraden toegevoegd. Dit product wordt dan ook <u>niet</u> getoond in de webshop'],
        ['label' => 'catalog_added_stock', 'text' => 'Toegevoegde voorraad'],
        ['label' => 'catalog_add_stock', 'text' => 'Voorraad toevoegen'],
        ['label' => 'catalog_mpn', 'text' => 'MPN'],
        ['label' => 'catalog_no_image_color_relations', 'text' => 'Deze maat/kleur combinatie heeft geen voorraad. Dit product wordt <u>niet</u> getoond in de webshop'],
        ['label' => 'catalog_extra_price_tooltip', 'text' => 'Vul hier het bedrag in dat het product meer kost door een bepaalde maat en/of kleur (combinatie). Als er geen meerprijs is, vul 0.00 in'],
        ['label' => 'catalog_extra_price', 'text' => 'Meerprijs'],
        ['label' => 'catalog_stock_tooltip', 'text' => 'Vul hier het aantal producten dat voorradig is in, als de voorraad onbeperkt is, laat dit veld dan leeg en druk op `voorraad toevoegen`'],
        ['label' => 'catalog_stock', 'text' => 'Voorraad'],
        ['label' => 'catalog_stock_color_tooltip', 'text' => 'Kies hier de kleur waaraan u een afbeelding wilt koppelen'],
        ['label' => 'catalog_stock_size_tooltip', 'text' => 'Kies hier de maat waarvoor u voorraad wilt toevoegen'],
        ['label' => 'catalog_stock_management_tooltip', 'text' => 'Beheer hier de voorraden van dit product'],
        ['label' => 'catalog_stock_management', 'text' => 'Voorraad beheer'],
        ['label' => 'catalog_no_images', 'text' => 'Er zijn nog geen afbeeldingen toegevoegd.'],
        ['label' => 'catalog_image_color_relations', 'text' => 'Gekoppelde afbeeldingen en kleuren'],
        ['label' => 'catalog_save_image_color_relation', 'text' => 'Afbeeldingen en kleur koppelen'],
        ['label' => 'catalog_image_color_relation_tooltip', 'text' => 'Koppel hier een kleur aan een specifieke afbeelding.Dit is nodig voor de google product feed om aan te geven welke afbeelding bij welke kleur hoort'],
        ['label' => 'catalog_image_color_relation', 'text' => 'Afbeelding/kleur koppeling'],
        ['label' => 'catalog_images_warning', 'text' => 'Afbeeldingen kunnen worden geüpload nadat het product eerst is opgeslagen'],
        ['label' => 'catalog_select_one_category', 'text' => 'Selecteer minstens 1 categorie'],
        ['label' => 'catalog_categories', 'text' => 'Categorieën'],
        ['label' => 'catalog_select_google_category', 'text' => 'Kies een Google categories'],
        ['label' => 'catalog_google_category', 'text' => 'Google categorie'],
        ['label' => 'catalog_select_tax_percentage', 'text' => 'Kies een BTW percentage'],
        ['label' => 'catalog_taxes_percentage', 'text' => 'BTW percentage'],
        ['label' => 'catalog_general_mpn_tooltip', 'text' => 'Manufacter productnummer'],
        ['label' => 'catalog_general_mpn', 'text' => 'MPN General'],
        ['label' => 'catalog_select_brand', 'text' => 'Kies een merk'],
        ['label' => 'catalog_select_type', 'text' => 'Kies een producttype'],
        ['label' => 'catalog_product_type', 'text' => 'Product type'],
        ['label' => 'catalog_set_homepage', 'text' => 'Zet het product zichtbaar OF onzichtbaar op de homepage'],
        ['label' => 'catalog_homepage', 'text' => 'Op homepage'],
        ['label' => 'catalog_product', 'text' => 'Product'],
        ['label' => 'catalog_back_overview', 'text' => 'Terug naar het producten overzicht'],
        ['label' => 'catalog_status_not_changed', 'text' => 'Product niet gewijzigd'],
        ['label' => 'catalog_status_online', 'text' => 'Product online gezet'],
        ['label' => 'catalog_status_offline', 'text' => 'Product offline gezet'],
        ['label' => 'catalog_no_products', 'text' => 'Er zijn geen producten om weer te geven met dit filter'],
        ['label' => 'catalog_delete_product', 'text' => 'Verwijder product'],
        ['label' => 'catalog_edit_product', 'text' => 'Bewerk product'],
        ['label' => 'catalog_set_online', 'text' => 'Product online zetten'],
        ['label' => 'catalog_set_offline', 'text' => 'Product offline zetten'],
        ['label' => 'catalog_margin', 'text' => 'Marge'],
        ['label' => 'catalog_purchase_price', 'text' => 'inkoopprijs'],
        ['label' => 'catalog_reduced_price', 'text' => 'actie prijs'],
        ['label' => 'catalog_add_product', 'text' => 'Product toevoegen'],
        ['label' => 'catalog_found_products', 'text' => 'Gevonden producten'],
        ['label' => 'catalog_filter_products', 'text' => 'Filter producten'],
        ['label' => 'catalog_search_homepage', 'text' => 'Zoek alleen producten die op de homepage worden weergegeven'],
        ['label' => 'catalog_all_types', 'text' => 'alle typen'],
        ['label' => 'catalog_all_brands', 'text' => 'alle merken'],
        ['label' => 'catalog_tooltip_filter_name', 'text' => 'Zoek op de naam van het product of een gedeelte daarvan'],
        ['label' => 'catalog_navigate_proceed', 'text' => 'Klik links op de navigatie om verder te gaan.'],
        ['label' => 'global_translatable', 'text' => 'Vertaalbaar'],
        ['label' => 'catalog_input_translatable', 'text' => 'Input is vertaalbaar'],
        ['label' => 'global_for_language', 'text' => 'voor taal'],
        ['label' => 'catalog_translatable_set_online_tooltip', 'text' => 'Zet vertaling online/offline'],
        ['label' => 'catalog_input_translatable_tooltip', 'text' => 'Kies of het open veld vertaalbaar is.<br />Bijvoorbeeld: getallen zijn bijna nooit vertaalbaar'],
    ],
    'es' => [
        ['label' => 'catalog_no_related_products', 'text' => 'No hay productos relacionados vinculados'],
        ['label' => 'catalog_stock_not_added', 'text' => 'No se añadío el stock, ya existe esa combinación'],
        ['label' => 'catalog_product_not_saved', 'text' => 'No se guardó el producto, no todos los campos fueron rellenados correctamente'],
        ['label' => 'catalog_category_no_deleted', 'text' => 'No se pudo eliminar la categoría'],
        ['label' => 'catalog_category_deleted', 'text' => 'Categoría eliminada'],
        ['label' => 'catalog_category_order_saved', 'text' => 'Orden de las categorías guardado'],
        ['label' => 'catalog_category_not_saved', 'text' => 'No se guardo la categoría, no se completaron todos los campos correctamente'],
        ['label' => 'catalog_category_saved', 'text' => 'Categoría guardada'],
        ['label' => 'catalog_all_links', 'text' => 'y todos los enlaces a los productos'],
        ['label' => 'catalog_product_property_add', 'text' => 'Añadir propiedad de producto'],
        ['label' => 'catalog_brand_name_tooltip', 'text' => 'Introduzca el nombre de la marca'],
        ['label' => 'catalog_online_offline_tooltip', 'text' => '(Des)activa el producto'],
        ['label' => 'catalog_no_image_color_relations', 'text' => 'Actualmente <u>no existe inventario</u> para este producto, por lo que no se mostrará en la tienda online'],
        ['label' => 'catalog_product_number', 'text' => 'Nombre del producto'],
        ['label' => 'catalog_stock_updating', 'text' => 'Actualización de inventario'],
        ['label' => 'catalog_choose_image_for_color', 'text' => 'Seleccione una imagen para el color'],
        ['label' => 'catalog_product_type_not_deleted', 'text' => 'No se pudo eliminar el tipo de producto'],
        ['label' => 'catalog_product_type_deleted', 'text' => 'Tipo de producto eliminado satisfactoriamente'],
        ['label' => 'catalog_product_type_not_saved', 'text' => 'No se pudo guardar el tipo de producto, no todos los campos están (correctamente) completados'],
        ['label' => 'catalog_product_type_saved', 'text' => 'Tipo de producto guardado satisfactoriamente'],
        ['label' => 'catalog_product_types', 'text' => 'Tipos de producto'],
        ['label' => 'catalog_size_not_deleted', 'text' => 'No se pudo eliminar la talla'],
        ['label' => 'catalog_size_deleted', 'text' => 'Talla eliminada satisfactoriamente'],
        ['label' => 'catalog_size_not_saved', 'text' => 'No se ha guardado la talla, no todos los campos están (correctamente) completados'],
        ['label' => 'catalog_size_saved', 'text' => 'Talla guardada satisfactoriamente'],
        ['label' => 'catalog_property_group_not_deleted', 'text' => 'No se pudo eliminar el grupo de propiedades'],
        ['label' => 'catalog_property_group_deleted', 'text' => 'Grupo de propiedades eliminado satisfactoriamente'],
        ['label' => 'catalog_property_group_not_saved', 'text' => 'No se pudo eliminar el grupo de propiedades, no todos los campos están (correctamente) completados'],
        ['label' => 'catalog_property_group_saved', 'text' => 'Grupo de propiedades guardado satisfactoriamente'],
        ['label' => 'catalog_product_type_property_group', 'text' => 'Grupos de propiedades de productos'],
        ['label' => 'catalog_product_property_not_deleted', 'text' => 'No se pudo eliminar la propiedad'],
        ['label' => 'catalog_product_property_deleted', 'text' => 'Propiedad eliminada satisfactoriamente'],
        ['label' => 'catalog_property_value_not_saved', 'text' => 'No se ha guardado el valor, no todos los campos se rellenaron correctamente'],
        ['label' => 'catalog_property_value_saved', 'text' => 'Valor guardado satisfactoriamente'],
        ['label' => 'catalog_product_properties_not_saved', 'text' => 'No se pudieron guardar las propiedades del producto, no todos los campos están (correctamente) completados'],
        ['label' => 'catalog_product_properties_saved', 'text' => 'Propiedad guardada correctamente'],
        ['label' => 'catalog_product_type_properties', 'text' => 'Propiedades específicas del tipo de producto'],
        ['label' => 'catalog_color_not_deleted', 'text' => 'No se puedo eliminar el color'],
        ['label' => 'catalog_color_deleted', 'text' => 'Color eliminado satisfactoriamente'],
        ['label' => 'catalog_color_not_saved', 'text' => 'No se pudo guardar el color, no todos los campos están (correctamente) completados'],
        ['label' => 'catalog_color_saved', 'text' => 'Color guardado satisfactoriamente'],
        ['label' => 'catalog_brand_not_deleted', 'text' => 'La marca no pudo ser eliminada'],
        ['label' => 'catalog_brand_deleted', 'text' => 'Marca eliminada satisfactoriamente'],
        ['label' => 'catalog_brand_not_saved', 'text' => 'No se pudo guardar la marca, no todos los campos están (correctamente) completados'],
        ['label' => 'catalog_brand_saved', 'text' => 'La marca ha sido guardada satisfactoriamente'],
        ['label' => 'catalog_size_back_overview', 'text' => 'Volver al listado de tallas'],
        ['label' => 'catalog_no_sizes', 'text' => 'Hay no hay tamaños para mostrar'],
        ['label' => 'catalog_size_with_products', 'text' => 'Aún existen productos con esta talla'],
        ['label' => 'catalog_size_delete', 'text' => 'Eliminar talla'],
        ['label' => 'catalog_size_edit', 'text' => 'Editar talla'],
        ['label' => 'catalog_size_add', 'text' => 'Añadir talla del producto'],
        ['label' => 'catalog_all_sizes', 'text' => 'Todos los tamaños'],
        ['label' => 'catalog_color_back_overview', 'text' => 'Volver al listado de colores'],
        ['label' => 'catalog_color_drag', 'text' => 'Arrastre los nombres de los colores para modificar el orden'],
        ['label' => 'catalog_color_change_order', 'text' => 'Reordenar los colores'],
        ['label' => 'catalog_no_colors', 'text' => 'Hay no hay colores que mostrar'],
        ['label' => 'catalog_color_with_products', 'text' => 'Todavía existen productos con ese color'],
        ['label' => 'catalog_color_delete', 'text' => 'Eliminar color'],
        ['label' => 'catalog_color_edit', 'text' => 'Editar color'],
        ['label' => 'catalog_color_add', 'text' => 'Añadir color del producto'],
        ['label' => 'catalog_all_colors', 'text' => 'Todos los colores'],
        [
            'label' => 'catalog_property_group_tooltip_set',
            'text'  => 'Crear un nuevo grupo de propiedades específicas de tipo de producto. Sólo se crea el nombre. <br/> Ejemplos para una \'TV\': <br/>-Pantalla<br/>-Conexiones<br/>-Peso y dimensiones<br/>-etc.',
        ],
        ['label' => 'catalog_property_group_back_overview', 'text' => 'Volver al listado de grupos de propiedades'],
        ['label' => 'catalog_property_group_change_order', 'text' => 'Reordenar grupos de propiedades'],
        ['label' => 'catalog_not_property_group', 'text' => 'No hay grupos de propiedades para mostrar con este filtro'],
        ['label' => 'catalog_property_group_with_properties', 'text' => 'Este grupo aún tiene propiedades asignadas'],
        ['label' => 'catalog_property_group_delete', 'text' => 'Eliminar grupo de propiedades(se eliminarán los productos que las tienen)'],
        ['label' => 'catalog_property_group_edit', 'text' => 'Editar grupo de propiedades'],
        ['label' => 'catalog_property_group_add', 'text' => 'Añadir grupo de propiedades'],
        ['label' => 'catalog_property_group_found', 'text' => 'Grupos de propiedades encontrados'],
        ['label' => 'catalog_filter_property_groups', 'text' => 'Filtrar grupos de propiedades'],
        ['label' => 'catalog_category_name', 'text' => 'Nombre de la categoría'],
        ['label' => 'catalog_category_online_offline_tooltip', 'text' => '(Des)activa la categoría'],
        ['label' => 'catalog_category', 'text' => 'Categorías'],
        ['label' => 'catalog_category_back_overview', 'text' => 'Volver al listado de categorías'],
        ['label' => 'catalog_categories_drag', 'text' => 'Arrastre los nombres de la categorías para modificar el orden'],
        ['label' => 'catalog_category_change_structure', 'text' => 'Reordenar categorías'],
        ['label' => 'catalog_category_status_not_changed', 'text' => 'No se pudo(des)activar la categoría'],
        ['label' => 'catalog_category_status_online', 'text' => 'Categoría activada'],
        ['label' => 'catalog_category_status_offline', 'text' => 'Categoría desactivada'],
        ['label' => 'catalog_no_category', 'text' => 'Hay no hay categorías que mostrar'],
        ['label' => 'catalog_category_delete', 'text' => 'Debe eliminar primero todos los productos y subcategorías relacionadas'],
        ['label' => 'catalog_category_edit', 'text' => 'Editar categoría'],
        ['label' => 'catalog_category_set_online', 'text' => 'Activar categoría'],
        ['label' => 'catalog_category_set_offline', 'text' => 'Desactivar categoría'],
        ['label' => 'catalog_add_sub_category', 'text' => 'Añadir subcategoría a esta categoría'],
        ['label' => 'catalog_add_main_category', 'text' => 'Añadir categoría principal'],
        ['label' => 'catalog_categories_all', 'text' => 'Todas las categorías'],
        ['label' => 'catalog_back_overview_product_type', 'text' => 'Volver al listado de tipos de producto'],
        ['label' => 'catalog_set_with_genders', 'text' => 'Este producto tiene versiones para los dos sexos'],
        ['label' => 'catalog_unset_with_genders', 'text' => 'Este producto es unisex'],
        ['label' => 'catalog_with_genders_tooltip', 'text' => 'Este tipo de producto tiene versiones para ambos sexos (no se puede cambiar tras añadir el primer producto)'],
        ['label' => 'catalog_unset_with_colors', 'text' => 'Este producto no tiene diferentes colores'],
        ['label' => 'catalog_set_with_colors', 'text' => 'Este producto tiene diferentes colores'],
        ['label' => 'catalog_with_colors_tooltip', 'text' => 'Este tipo de producto existe en varios colores (no se puede cambiar tras añadir el primer producto)'],
        ['label' => 'catalog_product_type_warning', 'text' => 'Mientras existan productos de este tipo, este valor no puede ser modificado'],
        ['label' => 'catalog_unset_with_sizes', 'text' => 'Este producto tiene talla única'],
        ['label' => 'catalog_set_with_sizes', 'text' => 'Este producto tiene diferentes tallas'],
        ['label' => 'catalog_with_sizes_tooltip', 'text' => 'Existen diferentes tallas para este producto (no se puede cambiar tras añadir el primer producto)'],
        ['label' => 'catalog_set_product_type_tooltip', 'text' => 'Indique el tipo de producto'],
        ['label' => 'catalog_product_type_order', 'text' => 'Reordenar tipos de producto'],
        ['label' => 'catalog_value_not_deleted', 'text' => 'No se pudo eliminar el valor'],
        ['label' => 'catalog_value_deleted', 'text' => 'Valor eliminado satisfactoriamente'],
        ['label' => 'catalog_value_saved', 'text' => 'Valor guardado satisfactoriamente'],
        ['label' => 'catalog_value_not_changed', 'text' => 'No se pudo modificar el valor'],
        ['label' => 'catalog_value_reordered', 'text' => 'Valores reordenados correctamente'],
        ['label' => 'catalog_change_value', 'text' => 'Reemplazar valor'],
        ['label' => 'catalog_product_properties_warning', 'text' => 'Sólo se pueden añadir valores una vez se haya guardado la propiedad.'],
        ['label' => 'catalog_edit_value', 'text' => 'Editar valor'],
        ['label' => 'catalog_saved_values_tooltip', 'text' => 'Arrastre las filas para modificar el orden'],
        ['label' => 'catalog_saved_values', 'text' => 'Valores guardados satisfactoriamente'],
        ['label' => 'catalog_save_value', 'text' => 'Guardar valor'],
        ['label' => 'catalog_property_set_value', 'text' => 'Introduzca el valor'],
        ['label' => 'catalog_property_possible_values_tooltip', 'text' => 'Si se elige SELECT ó CHECKBOX, pueden añadirse los valores que pueden ser seleccionados después aquí'],
        ['label' => 'catalog_property_possible_values', 'text' => 'Valores posibles'],
        ['label' => 'catalog_property_group_choose', 'text' => 'Elija un grupo de propiedades'],
        ['label' => 'catalog_property_group_select', 'text' => 'Primero ha de elegir un tipo de producto'],
        ['label' => 'catalog_property_group_tooltip', 'text' => 'Grupo de propiedades para esta propiedad'],
        ['label' => 'catalog_product_type_tooltip', 'text' => 'Tipo de producto específico para esta propiedad'],
        ['label' => 'catalog_no_filter', 'text' => 'Sin filtro'],
        ['label' => 'catalog_type_filter_select', 'text' => 'Elija un tipo de filtro'],
        [
            'label' => 'catalog_type_filter_tooltip',
            'text'  => 'Elija un tipo de vista para el filtro <br/>-TEXT: buscar en una (parte) del valor importado <br/>-CHECKBOX: se pueden seleccionar múltiples opciones<br/>-SELECT: seleccione 1 valor de una propiedad<br/>-MIN-MAX: filtrar usando un intervalo numérico',
        ],
        ['label' => 'catalog_type_filter', 'text' => 'Tipo de filtro'],
        ['label' => 'catalog_select_entry', 'text' => 'Elija un tipo de entrada'],
        [
            'label' => 'catalog_input_type_tooltip',
            'text'  => 'La manera de introducir los datos de un producto <br/> SELECT: elegir 1 valor predefinido de un menú desplegable / <br/> CHECKBOX: seleccionar múltiples valores predefinidos <br/> TEXT: introducir un valor en un campo de texto',
        ],
        ['label' => 'catalog_input_type', 'text' => 'Tipo de entrada'],
        ['label' => 'catalog_product_feature_tooltip', 'text' => 'Indique la propiedad del producto'],
        ['label' => 'catalog_product_feature', 'text' => 'Propiedades de producto'],
        [
            'label' => 'catalog_product_features_tooltip',
            'text'  => 'Cree aquí una nueva propiedad específica de un tipo de producto. Aquí solo se crea el nombre. Ejemplos para una \'cama\': <br/> -Ancho de la cama <br/> -Altura del cabecero <br/> etc.',
        ],
        ['label' => 'catalog_product_feature_back_overview', 'text' => 'Volver al listado de propiedades del producto'],
        ['label' => 'catalog_product_type_drag_names', 'text' => 'Arrastre los nombres para modificar el orden'],
        ['label' => 'catalog_product_type_change_order', 'text' => 'Reordenar propiedades'],
        ['label' => 'catalog_no_product_types', 'text' => 'No hay ningún tipo de producto para mostrar'],
        ['label' => 'catalog_product_type_with_products', 'text' => 'Existen productos de este tipo'],
        ['label' => 'catalog_product_type_delete', 'text' => 'Eliminar el tipo de producto'],
        ['label' => 'catalog_product_type_edit', 'text' => 'Editar tipo de producto'],
        ['label' => 'catalog_with_genders', 'text' => 'Tiene versiones para ambos sexos'],
        ['label' => 'catalog_with_colors', 'text' => 'Tiene diferentes colores'],
        ['label' => 'catalog_with_sizes', 'text' => 'Tiene diferentes tallas'],
        ['label' => 'catalog_product_type_add', 'text' => 'Añadir un tipo de producto'],
        ['label' => 'catalog_product_types_all', 'text' => 'Todos los tipos de producto'],
        ['label' => 'catalog_property_group', 'text' => 'Grupo de propiedades'],
        ['label' => 'catalog_no_product_properties', 'text' => 'No hay propiedades que mostrar con este filtro'],
        ['label' => 'catalog_product_properties_delete_tooltip', 'text' => 'Eliminar propiedad (también se elimina los productos que la tienen)'],
        ['label' => 'catalog_product_properties_edit', 'text' => 'Editar las propiedades del producto'],
        ['label' => 'catalog_filter_type', 'text' => 'Filtrar tipo'],
        ['label' => 'catalog_product_features', 'text' => 'Propiedades del producto'],
        ['label' => 'catalog_product_properties_found', 'text' => 'Propiedades del producto encontradas'],
        ['label' => 'catalog_product_properties_filter', 'text' => 'Filtrar propiedades del producto'],
        ['label' => 'catalog_product_properties_all', 'text' => 'Todas las propiedades del producto'],
        ['label' => 'catalog_product_type_choose', 'text' => 'Debe elegir primero un tipo de producto'],
        ['label' => 'catalog_brand_drag_names', 'text' => 'Arrastre los nombres para cambiar el orden'],
        ['label' => 'catalog_brand_change_order', 'text' => 'Modificar el orden de las marcas'],
        ['label' => 'catalog_brand_back_overview', 'text' => 'Volver al listado de marcas'],
        ['label' => 'catalog_brand_online_offline_tooltip', 'text' => '(Des)activa la marca'],
        ['label' => 'catalog_brand_status_not_changed', 'text' => 'La marca no pudo ser (des)activada'],
        ['label' => 'catalog_brand_status_offline', 'text' => 'Marca desactivada'],
        ['label' => 'catalog_brand_status_online', 'text' => 'Marca activada'],
        ['label' => 'catalog_brand_name', 'text' => 'Nombre de la marca'],
        ['label' => 'catalog_add_brand', 'text' => 'Añadir nueva marca'],
        ['label' => 'catalog_stock_not_updated', 'text' => '<u>No</u> pudieron actualizarse los valores'],
        ['label' => 'catalog_stock_updated', 'text' => 'Stock actualizado satisfactoriamente'],
        ['label' => 'catalog_stock_not_deleted', 'text' => 'No se pudo eliminar el stock'],
        ['label' => 'catalog_stock_deleted', 'text' => 'Stock eliminado satisfactoriamente'],
        ['label' => 'catalog_product_not_deleted', 'text' => 'No se pudo eliminar el producto'],
        ['label' => 'catalog_product_deleted', 'text' => 'Producto eliminado satisfactoriamente'],
        ['label' => 'catalog_not_added_stock', 'text' => '<u>No</u> se añadió nada inventario, no todos los campos están (correctamente) completados'],
        ['label' => 'catalog_stock_added', 'text' => 'Stock añadido'],
        ['label' => 'catalog_product_saved', 'text' => 'Producto guardado'],
        ['label' => 'catalog_not_valid_google_category', 'text' => 'Esta no es una categoría válida de Google'],
        ['label' => 'catalog_status_added', 'text' => 'Producto añadido satisfactoriamente'],
        ['label' => 'catalog_status_removed', 'text' => 'Producto eliminado satisfactoriamente'],
        ['label' => 'catalog_related_product', 'text' => 'Recomendación'],
        ['label' => 'catalog_related_products_warning', 'text' => 'Se pueden añadir recomendaciones una vez se haya guardado el producto'],
        ['label' => 'catalog_related_products', 'text' => 'Recomendaciones'],
        ['label' => 'catalog_no_type', 'text' => 'No existen propiedades específicas para este producto'],
        ['label' => 'catalog_type_warning', 'text' => 'Pueden añadirse propiedades específicas del tipo una vez se haya guardado el producto'],
        ['label' => 'catalog_type_properties', 'text' => 'Propiedades específicas del tipo'],
        ['label' => 'catalog_color_size_relation_warning', 'text' => 'El inventario sólo puede ser modificado una vez haya guardado el producto'],
        ['label' => 'catalog_no_size_color_relations', 'text' => 'Actualmente <u>no existe inventario</u> para este producto, por lo que no se mostrará en la tienda online'],
        ['label' => 'catalog_added_stock', 'text' => 'Cantidad añadido'],
        ['label' => 'catalog_add_stock', 'text' => 'Añadir al inventario'],
        ['label' => 'catalog_mpn', 'text' => 'MPN'],
        ['label' => 'catalog_extra_price_tooltip', 'text' => 'Introduzca el coste adicional que supone una combinación de talla y color determinada. Si no hay ningún costo adicional, ingrese en 0.00'],
        ['label' => 'catalog_extra_price', 'text' => 'Coste adicional'],
        ['label' => 'catalog_stock_tooltip', 'text' => 'Introduzca aquí el número de productos en stock, si el stock es ilimitado, deje este campo en blanco y pulse \'Añadir\' stock'],
        ['label' => 'catalog_stock', 'text' => 'Inventario'],
        ['label' => 'catalog_stock_color_tooltip', 'text' => 'Elija el color que desee asociar a una imagen'],
        ['label' => 'catalog_stock_size_tooltip', 'text' => 'Elija la talla a la que desea añadir inventario'],
        ['label' => 'catalog_stock_management_tooltip', 'text' => 'Gestione el inventario desde aquí'],
        ['label' => 'catalog_stock_management', 'text' => 'Gestión del inventario'],
        ['label' => 'catalog_no_images', 'text' => 'No se han añadido imágenes.'],
        ['label' => 'catalog_image_color_relations', 'text' => 'Colores e imágenes relacionadas'],
        ['label' => 'catalog_save_image_color_relation', 'text' => 'Enlazar color e imágenes'],
        ['label' => 'catalog_image_color_relation_tooltip', 'text' => 'Enlaza un color con una imagen específica. Esto es necesario feed de productos de Google para indicar qué imagen corresponde a cada color.'],
        ['label' => 'catalog_image_color_relation', 'text' => 'Relación imagen/color'],
        ['label' => 'catalog_images_warning', 'text' => 'Sólo se pueden subir imágenes una vez se haya guardado el producto'],
        ['label' => 'catalog_select_one_category', 'text' => 'Por favor, seleccione al menos 1 categoría'],
        ['label' => 'catalog_categories', 'text' => 'Categorías'],
        ['label' => 'catalog_select_google_category', 'text' => 'Elija una categoría de Google'],
        ['label' => 'catalog_google_category', 'text' => 'Categoría de Google'],
        ['label' => 'catalog_select_tax_percentage', 'text' => 'Elija un tipo de IVA'],
        ['label' => 'catalog_taxes_percentage', 'text' => 'Porcentaje de IVA'],
        ['label' => 'catalog_general_mpn_tooltip', 'text' => 'Número del producto del fabricante'],
        ['label' => 'catalog_general_mpn', 'text' => 'MPN General'],
        ['label' => 'catalog_select_brand', 'text' => 'Elija una marca'],
        ['label' => 'catalog_select_type', 'text' => 'Elija un tipo de producto'],
        ['label' => 'catalog_product_type', 'text' => 'Tipo de producto'],
        ['label' => 'catalog_set_homepage', 'text' => 'Muestra u oculta el producto en la página principal'],
        ['label' => 'catalog_homepage', 'text' => 'En la página principal'],
        ['label' => 'catalog_product', 'text' => 'Producto'],
        ['label' => 'catalog_back_overview', 'text' => 'Volver al listado de productos'],
        ['label' => 'catalog_status_not_changed', 'text' => 'El producto no se modificó'],
        ['label' => 'catalog_status_online', 'text' => 'Producto activado'],
        ['label' => 'catalog_status_offline', 'text' => 'Producto desactivado'],
        ['label' => 'catalog_no_products', 'text' => 'No hay productos para mostrar con este filtro'],
        ['label' => 'catalog_delete_product', 'text' => 'Eliminar producto'],
        ['label' => 'catalog_edit_product', 'text' => 'Editar productos'],
        ['label' => 'catalog_set_online', 'text' => 'Activar producto'],
        ['label' => 'catalog_set_offline', 'text' => 'Desactivar producto'],
        ['label' => 'catalog_margin', 'text' => 'Margen'],
        ['label' => 'catalog_purchase_price', 'text' => 'precio de compra'],
        ['label' => 'catalog_reduced_price', 'text' => 'precio de oferta'],
        ['label' => 'catalog_add_product', 'text' => 'Añadir producto'],
        ['label' => 'catalog_found_products', 'text' => 'Productos encontrados'],
        ['label' => 'catalog_filter_products', 'text' => 'Filtrar productos'],
        ['label' => 'catalog_search_homepage', 'text' => 'Buscar sólo los productos que aparecen en la página de inicio'],
        ['label' => 'catalog_all_types', 'text' => 'todos los tipos'],
        ['label' => 'catalog_all_brands', 'text' => 'todas las marcas'],
        ['label' => 'catalog_tooltip_filter_name', 'text' => 'Buscar por nombre del producto'],
        ['label' => 'catalog_navigate_proceed', 'text' => 'Clic izquierdo sobre la navegación para continuar.'],
    ],
    'en' => [
        ['label' => 'catalog_no_related_products', 'text' => 'No related products linked'],
        ['label' => 'catalog_stock_not_added', 'text' => 'Stock not added combination already exists!'],
        ['label' => 'catalog_product_not_saved', 'text' => 'Product not saved, not all the field were correctly completed'],
        ['label' => 'catalog_category_no_deleted', 'text' => 'Category cannot be deleted'],
        ['label' => 'catalog_category_deleted', 'text' => 'Category deleted'],
        ['label' => 'catalog_category_order_saved', 'text' => 'Category order saved'],
        ['label' => 'catalog_category_not_saved', 'text' => 'Category not saved, not all the fields were correctly completed'],
        ['label' => 'catalog_category_saved', 'text' => 'Category is saved'],
        ['label' => 'catalog_all_links', 'text' => 'and all links to products'],
        ['label' => 'catalog_product_property_add', 'text' => 'Add product property'],
        ['label' => 'catalog_brand_name_tooltip', 'text' => 'Enter the brand name'],
        ['label' => 'catalog_online_offline_tooltip', 'text' => 'Set the product online or offline'],
        ['label' => 'catalog_no_image_color_relations', 'text' => 'This size/color combination has not stock.This product is <u>not</u> shown in the webshop'],
        ['label' => 'catalog_product_number', 'text' => 'Product name'],
        ['label' => 'catalog_stock_updating', 'text' => 'Inventory has been update'],
        ['label' => 'catalog_choose_image_for_color', 'text' => 'Choose an image for the color'],
        ['label' => 'catalog_product_type_not_deleted', 'text' => 'Product type cannot be deleted'],
        ['label' => 'catalog_product_type_deleted', 'text' => 'Product type has been deleted'],
        ['label' => 'catalog_product_type_not_saved', 'text' => 'Product type is not saved, not all fields are (right) filled in'],
        ['label' => 'catalog_product_type_saved', 'text' => 'Product type is saved'],
        ['label' => 'catalog_product_types', 'text' => 'Product types'],
        ['label' => 'catalog_size_not_deleted', 'text' => 'Size cannot be deleted'],
        ['label' => 'catalog_size_deleted', 'text' => 'Size has been deleted'],
        ['label' => 'catalog_size_not_saved', 'text' => 'Size has not been saved, not all fields are (right) filled in'],
        ['label' => 'catalog_size_saved', 'text' => 'Size has been saved'],
        ['label' => 'catalog_property_group_not_deleted', 'text' => 'Cannot delete property group'],
        ['label' => 'catalog_property_group_deleted', 'text' => 'Property group has been deleted'],
        ['label' => 'catalog_property_group_not_saved', 'text' => 'Property group has not been saved, not all fields are (right) filled in'],
        ['label' => 'catalog_property_group_saved', 'text' => 'Property group saved'],
        ['label' => 'catalog_product_type_property_group', 'text' => 'Product type specific property groups'],
        ['label' => 'catalog_product_property_not_deleted', 'text' => 'Property cannot be deleted'],
        ['label' => 'catalog_product_property_deleted', 'text' => 'Property has been deleted'],
        ['label' => 'catalog_property_value_not_saved', 'text' => 'Value is not saved, not all fields are (right) filled in'],
        ['label' => 'catalog_property_value_saved', 'text' => 'Value is saved'],
        ['label' => 'catalog_product_properties_not_saved', 'text' => 'Property is not saved, not all fields are (right) filled in'],
        ['label' => 'catalog_product_properties_saved', 'text' => 'Product porperty is saved'],
        ['label' => 'catalog_product_type_properties', 'text' => 'Product type specific properties'],
        ['label' => 'catalog_color_not_deleted', 'text' => 'Color cannot be deleted'],
        ['label' => 'catalog_color_deleted', 'text' => 'Color has been deleted'],
        ['label' => 'catalog_color_not_saved', 'text' => 'Color is not saved, not all fields are (right) filled in'],
        ['label' => 'catalog_color_saved', 'text' => 'Color is saved'],
        ['label' => 'catalog_brand_not_deleted', 'text' => 'Brand cannot be deleted'],
        ['label' => 'catalog_brand_deleted', 'text' => 'Brand has been deleted'],
        ['label' => 'catalog_brand_not_saved', 'text' => 'Brand is not saved, not all fields are (right) filled in'],
        ['label' => 'catalog_brand_saved', 'text' => 'Brand is stored'],
        ['label' => 'catalog_size_back_overview', 'text' => 'Back to the sizes overview'],
        ['label' => 'catalog_no_sizes', 'text' => 'There are no sizes to display'],
        ['label' => 'catalog_size_with_products', 'text' => 'There are still products associated to this size'],
        ['label' => 'catalog_size_delete', 'text' => 'Delete size'],
        ['label' => 'catalog_size_edit', 'text' => 'Edit custom'],
        ['label' => 'catalog_size_add', 'text' => 'Add size'],
        ['label' => 'catalog_all_sizes', 'text' => 'All sizes'],
        ['label' => 'catalog_color_back_overview', 'text' => 'Back to the color overview'],
        ['label' => 'catalog_color_drag', 'text' => 'Drag and drop the colors to change the order'],
        ['label' => 'catalog_color_change_order', 'text' => 'Kleurenen reorder'],
        ['label' => 'catalog_no_colors', 'text' => 'There are no colors to display'],
        ['label' => 'catalog_color_with_products', 'text' => 'This color has still images associated'],
        ['label' => 'catalog_color_delete', 'text' => 'Delete color'],
        ['label' => 'catalog_color_edit', 'text' => 'Edit color'],
        ['label' => 'catalog_color_add', 'text' => 'Add color'],
        ['label' => 'catalog_all_colors', 'text' => 'All colors'],
        [
            'label' => 'catalog_property_group_tooltip_set',
            'text'  => 'Create a new property group for a specific product type. Displays only the name created. < br/> Examples for a \' TV \': < br/>-Display < br/> -Connectors  < br/>-Weight & dimensions<br />etc',
        ],
        ['label' => 'catalog_property_group_back_overview', 'text' => 'Back to the property groups overview'],
        ['label' => 'catalog_property_group_change_order', 'text' => 'Change property groups order'],
        ['label' => 'catalog_not_property_group', 'text' => 'There are no property groups to display with this filter'],
        ['label' => 'catalog_property_group_with_properties', 'text' => 'There are still properties associated to this property group'],
        ['label' => 'catalog_property_group_delete', 'text' => 'Delete property group (it is also deleted from the products associated to this property group)'],
        ['label' => 'catalog_property_group_edit', 'text' => 'Edit property group'],
        ['label' => 'catalog_property_group_add', 'text' => 'Add property group'],
        ['label' => 'catalog_property_group_found', 'text' => 'Found property groups'],
        ['label' => 'catalog_filter_property_groups', 'text' => 'Filter property groups'],
        ['label' => 'catalog_category_name', 'text' => 'Category name'],
        ['label' => 'catalog_category_online_offline_tooltip', 'text' => 'Set the category online or offline'],
        ['label' => 'catalog_category', 'text' => 'Categories'],
        ['label' => 'catalog_category_back_overview', 'text' => 'Back to the categories overview'],
        ['label' => 'catalog_categories_drag', 'text' => 'Drag and drop the category titles to change the order'],
        ['label' => 'catalog_category_change_structure', 'text' => 'Product category reorder'],
        ['label' => 'catalog_category_status_not_changed', 'text' => 'Category status has not changed'],
        ['label' => 'catalog_category_status_online', 'text' => 'Category placed online'],
        ['label' => 'catalog_category_status_offline', 'text' => 'Category placed offline'],
        ['label' => 'catalog_no_category', 'text' => 'There are no categories to display'],
        ['label' => 'catalog_category_delete', 'text' => 'First delete all related sub categories and products'],
        ['label' => 'catalog_category_edit', 'text' => 'Edit product category'],
        ['label' => 'catalog_category_set_online', 'text' => 'Product category placed online'],
        ['label' => 'catalog_category_set_offline', 'text' => 'Product category placed offline'],
        ['label' => 'catalog_add_sub_category', 'text' => 'Add Sub categories under this category'],
        ['label' => 'catalog_add_main_category', 'text' => 'Add main category'],
        ['label' => 'catalog_categories_all', 'text' => 'All product categories'],
        ['label' => 'catalog_back_overview_product_type', 'text' => 'Back to product types overview'],
        ['label' => 'catalog_set_with_genders', 'text' => 'Use genders for this product type'],
        ['label' => 'catalog_unset_with_genders', 'text' => 'Do no use gender for this type of product'],
        ['label' => 'catalog_with_genders_tooltip', 'text' => 'This type of product has multiple genders per added product (it\'s not possible to change this value after you save the first product)'],
        ['label' => 'catalog_unset_with_colors', 'text' => 'Do not use colors for this type of product'],
        ['label' => 'catalog_set_with_colors', 'text' => 'Use colors for this product type'],
        ['label' => 'catalog_with_colors_tooltip', 'text' => 'This type of product has multiple colors per added product (it\'s not possible to change this value after you save the first product)'],
        ['label' => 'catalog_product_type_warning', 'text' => 'As long as there are products associated to this product type, it\'s not possible to change this value'],
        ['label' => 'catalog_unset_with_sizes', 'text' => 'Do no use sizes for this type of product'],
        ['label' => 'catalog_set_with_sizes', 'text' => 'Use sizes for this product type'],
        ['label' => 'catalog_with_sizes_tooltip', 'text' => 'This product type has multiple sizes per added product (it\'s not possible to change this value after you save the first product)'],
        ['label' => 'catalog_set_product_type_tooltip', 'text' => 'Fill in the product type'],
        ['label' => 'catalog_product_type_order', 'text' => 'Change product types order'],
        ['label' => 'catalog_value_not_deleted', 'text' => 'Value cannot be deleted'],
        ['label' => 'catalog_value_deleted', 'text' => 'Value successfully deleted'],
        ['label' => 'catalog_value_saved', 'text' => 'Value has been stored'],
        ['label' => 'catalog_value_not_changed', 'text' => 'Value cannot be changed'],
        ['label' => 'catalog_value_reordered', 'text' => 'Value order changed'],
        ['label' => 'catalog_change_value', 'text' => 'Change value'],
        ['label' => 'catalog_product_properties_warning', 'text' => 'Possible values can be added after the product property is saved and only for type SELECT and CHECKBOX'],
        ['label' => 'catalog_edit_value', 'text' => 'Edit value'],
        ['label' => 'catalog_saved_values_tooltip', 'text' => 'Drag and drop to change the rules order'],
        ['label' => 'catalog_saved_values', 'text' => 'Already added values'],
        ['label' => 'catalog_save_value', 'text' => 'Save value'],
        ['label' => 'catalog_property_set_value', 'text' => 'Fill the value'],
        ['label' => 'catalog_property_possible_values_tooltip', 'text' => 'If SELECT or CHECKBOX selected, you can add the possible selected values here'],
        ['label' => 'catalog_property_possible_values', 'text' => 'Possible values'],
        ['label' => 'catalog_property_group_choose', 'text' => 'Choose a property group'],
        ['label' => 'catalog_property_group_select', 'text' => 'Choose a product type'],
        ['label' => 'catalog_property_group_tooltip', 'text' => 'The property group associated to this property'],
        ['label' => 'catalog_product_type_tooltip', 'text' => 'The product type associated to property'],
        ['label' => 'catalog_no_filter', 'text' => 'No filter'],
        ['label' => 'catalog_type_filter_select', 'text' => 'Choose a filter type'],
        [
            'label' => 'catalog_type_filter_tooltip',
            'text'  => 'Choose a type of view for the filter <br/>-TEXT: search on a part of the imported value <br/>-CHECKBOX: Multiple options can selected<br/>-SELECT: select 1 value of a property <br/>-MIN-MAX: Filtering between min and max value (numbers)',
        ],
        ['label' => 'catalog_type_filter', 'text' => 'Type of filter'],
        ['label' => 'catalog_select_entry', 'text' => 'Choose an input type'],
        [
            'label' => 'catalog_input_type_tooltip',
            'text'  => 'The way of entering the data < br/> SELECT: choose 1 predefined value from a dropdown select box/< br/>CHECKBOX: check multiple  predefined values< br/>TEXT: fill a value in a text field',
        ],
        ['label' => 'catalog_input_type', 'text' => 'Type of input'],
        ['label' => 'catalog_product_feature_tooltip', 'text' => 'Fill in the product property'],
        ['label' => 'catalog_product_feature', 'text' => 'Product property'],
        [
            'label' => 'catalog_product_features_tooltip',
            'text'  => 'Add a new product type specific product property. It displays only the name created. < br/> Examples for a \' bed \': < br/> < br/>-Foot board width< br/>-Foot board height <br/>-Headboard width x depth x height < br/>-etc',
        ],
        ['label' => 'catalog_product_feature_back_overview', 'text' => 'Back to product properties overview'],
        ['label' => 'catalog_product_type_drag_names', 'text' => 'Drag and drop the names to change the order'],
        ['label' => 'catalog_product_type_change_order', 'text' => 'Change product property order'],
        ['label' => 'catalog_no_product_types', 'text' => 'There are no product types to display'],
        ['label' => 'catalog_product_type_with_products', 'text' => 'There are still products associated to this product type'],
        ['label' => 'catalog_product_type_delete', 'text' => 'Delete product type'],
        ['label' => 'catalog_product_type_edit', 'text' => 'Edit Product type'],
        ['label' => 'catalog_with_genders', 'text' => 'With gender'],
        ['label' => 'catalog_with_colors', 'text' => 'With colors'],
        ['label' => 'catalog_with_sizes', 'text' => 'With sizes'],
        ['label' => 'catalog_product_type_add', 'text' => 'Add product type'],
        ['label' => 'catalog_product_types_all', 'text' => 'All product types'],
        ['label' => 'catalog_property_group', 'text' => 'Property group'],
        ['label' => 'catalog_no_product_properties', 'text' => 'There are no product properties to display with this filter'],
        ['label' => 'catalog_product_properties_delete_tooltip', 'text' => 'Delete product property(it is also deleted from the products associated to this property)'],
        ['label' => 'catalog_product_properties_edit', 'text' => 'Edit product properties'],
        ['label' => 'catalog_filter_type', 'text' => 'Filter type'],
        ['label' => 'catalog_product_features', 'text' => 'Product property'],
        ['label' => 'catalog_product_properties_found', 'text' => 'Found product properties'],
        ['label' => 'catalog_product_properties_filter', 'text' => 'Filter product properties'],
        ['label' => 'catalog_product_properties_all', 'text' => 'All property groups'],
        ['label' => 'catalog_product_type_choose', 'text' => 'Choose a product type'],
        ['label' => 'catalog_brand_drag_names', 'text' => 'Drag and drop the brand names to change the order'],
        ['label' => 'catalog_brand_change_order', 'text' => 'Change brands order'],
        ['label' => 'catalog_brand_back_overview', 'text' => 'Back to the brands overview'],
        ['label' => 'catalog_brand_online_offline_tooltip', 'text' => 'Put the brand online or offline'],
        ['label' => 'catalog_brand_status_not_changed', 'text' => 'Brand status has not changed'],
        ['label' => 'catalog_brand_status_offline', 'text' => 'Brand placed offline'],
        ['label' => 'catalog_brand_status_online', 'text' => 'Brand placed online'],
        ['label' => 'catalog_brand_name', 'text' => 'Brand Name'],
        ['label' => 'catalog_add_brand', 'text' => 'Add brand'],
        ['label' => 'catalog_stock_not_updated', 'text' => 'Stock <u>cannot</u> be updated'],
        ['label' => 'catalog_stock_updated', 'text' => 'Stock has been updated'],
        ['label' => 'catalog_stock_not_deleted', 'text' => 'Stock cannot be deleted'],
        ['label' => 'catalog_stock_deleted', 'text' => 'Stock has been deleted'],
        ['label' => 'catalog_product_not_deleted', 'text' => 'Product cannot be deleted'],
        ['label' => 'catalog_product_deleted', 'text' => 'Product has been deleted'],
        ['label' => 'catalog_not_added_stock', 'text' => 'Stock <u>have not been</u> added, not all fields are (right) filled in'],
        ['label' => 'catalog_stock_added', 'text' => 'Stock added'],
        ['label' => 'catalog_product_saved', 'text' => 'Product is saved'],
        ['label' => 'catalog_not_valid_google_category', 'text' => 'This is not a valid Google Category'],
        ['label' => 'catalog_status_added', 'text' => 'The product was added'],
        ['label' => 'catalog_status_removed', 'text' => 'The product was successfully deleted'],
        ['label' => 'catalog_related_product', 'text' => 'Related product'],
        ['label' => 'catalog_related_products_warning', 'text' => 'Related products properties can be added after the product is saved'],
        ['label' => 'catalog_related_products', 'text' => 'Related products'],
        ['label' => 'catalog_no_type', 'text' => 'There are no type-specific properties for this product'],
        ['label' => 'catalog_type_warning', 'text' => 'Specific type properties can be added after the product is first stored'],
        ['label' => 'catalog_type_properties', 'text' => 'Specific type properties'],
        ['label' => 'catalog_color_size_relation_warning', 'text' => 'Stock can be managed after the product is saved'],
        ['label' => 'catalog_no_size_color_relations', 'text' => 'This size/color combination has not stock.This product is <u>not</u> shown in the webshop'],
        ['label' => 'catalog_added_stock', 'text' => 'Added stock'],
        ['label' => 'catalog_add_stock', 'text' => 'Add stock'],
        ['label' => 'catalog_mpn', 'text' => 'MPN'],
        ['label' => 'catalog_extra_price_tooltip', 'text' => 'Fill the extra price for the product combination of color and/or size.\r\nIf there is no extra price, fill with 0.00'],
        ['label' => 'catalog_extra_price', 'text' => 'Extra price'],
        ['label' => 'catalog_stock_tooltip', 'text' => 'Fill in here the number of products in stock, if the stcok is unlimited, leve this field blank and press button'],
        ['label' => 'catalog_stock', 'text' => 'Stock'],
        ['label' => 'catalog_stock_color_tooltip', 'text' => 'Choose the color that you want to associate to the image'],
        ['label' => 'catalog_stock_size_tooltip', 'text' => 'Choose the size which you want to add stock to'],
        ['label' => 'catalog_stock_management_tooltip', 'text' => 'Manage stocks of this product here'],
        ['label' => 'catalog_stock_management', 'text' => 'Stock management'],
        ['label' => 'catalog_no_images', 'text' => 'There are no images stored.'],
        ['label' => 'catalog_image_color_relations', 'text' => 'Related images and colors'],
        ['label' => 'catalog_save_image_color_relation', 'text' => 'Add image and color relations'],
        ['label' => 'catalog_image_color_relation_tooltip', 'text' => 'Link a color to a specific image.This is necessary for the google product feed to indicate which image matches which color'],
        ['label' => 'catalog_image_color_relation', 'text' => 'Image/color link'],
        ['label' => 'catalog_images_warning', 'text' => 'Images can be uploaded after the product is saved'],
        ['label' => 'catalog_select_one_category', 'text' => 'Please select at least 1 category'],
        ['label' => 'catalog_categories', 'text' => 'Categories'],
        ['label' => 'catalog_select_google_category', 'text' => 'Choose a Google categories'],
        ['label' => 'catalog_google_category', 'text' => 'Google category'],
        ['label' => 'catalog_select_tax_percentage', 'text' => 'Choose a sales tax percentage'],
        ['label' => 'catalog_taxes_percentage', 'text' => 'SALES TAX percentage'],
        ['label' => 'catalog_general_mpn_tooltip', 'text' => 'Manufacter product number'],
        ['label' => 'catalog_general_mpn', 'text' => 'General MPN'],
        ['label' => 'catalog_select_brand', 'text' => 'Choose a brand'],
        ['label' => 'catalog_select_type', 'text' => 'Choose a product type'],
        ['label' => 'catalog_product_type', 'text' => 'Product type'],
        ['label' => 'catalog_set_homepage', 'text' => 'Turn the product visible or invisible on the homepage'],
        ['label' => 'catalog_homepage', 'text' => 'On homepage'],
        ['label' => 'catalog_product', 'text' => 'Product'],
        ['label' => 'catalog_back_overview', 'text' => 'Back to the product overview'],
        ['label' => 'catalog_status_not_changed', 'text' => 'Product status has not changed'],
        ['label' => 'catalog_status_online', 'text' => 'Product placed online'],
        ['label' => 'catalog_status_offline', 'text' => 'Product placed offline'],
        ['label' => 'catalog_no_products', 'text' => 'There are no products to display with this filter'],
        ['label' => 'catalog_delete_product', 'text' => 'Delete product'],
        ['label' => 'catalog_edit_product', 'text' => 'Edit product'],
        ['label' => 'catalog_set_online', 'text' => 'Product online'],
        ['label' => 'catalog_set_offline', 'text' => 'Product offline'],
        ['label' => 'catalog_margin', 'text' => 'Margin'],
        ['label' => 'catalog_purchase_price', 'text' => 'Purchase price'],
        ['label' => 'catalog_reduced_price', 'text' => 'Promotional price'],
        ['label' => 'catalog_add_product', 'text' => 'Add Product'],
        ['label' => 'catalog_found_products', 'text' => 'Products found'],
        ['label' => 'catalog_filter_products', 'text' => 'Filter products'],
        ['label' => 'catalog_search_homepage', 'text' => 'Search only products that appear on the home page'],
        ['label' => 'catalog_all_types', 'text' => 'All types'],
        ['label' => 'catalog_all_brands', 'text' => 'All brands'],
        ['label' => 'catalog_tooltip_filter_name', 'text' => 'Search on all the name or in a part of the name of the product'],
        ['label' => 'catalog_navigate_proceed', 'text' => 'Click on the left navigation bar to continue.'],
        ['label' => 'catalog_input_translatable', 'text' => 'Input is translatable'],
        ['label' => 'catalog_translatable_set_online_tooltip', 'text' => 'Set translation online/offline'],
        ['label' => 'global_for_language', 'text' => 'for language'],
        ['label' => 'catalog_input_translatable_tooltip', 'text' => 'Choose if the open text input values should be translatable or not.<br />Example: numbers are almost never translated'],
    ],
];

// site translations (front end)
$aNeededSiteTranslations = [
    'nl' => [
        ['label' => 'site_colors', 'text' => 'Kleuren', 'editable' => 1],
        ['label' => 'site_sizes', 'text' => 'Maten', 'editable' => 1],
        ['label' => 'site_type', 'text' => 'Type', 'editable' => 1],
        ['label' => 'site_brands', 'text' => 'Merken', 'editable' => 1],
        ['label' => 'site_fill_in_just_numbers_min_max', 'text' => 'Vul bij min/max alleen getallen in', 'editable' => 1],
        ['label' => 'site_max_price', 'text' => 'Max. Prijs', 'editable' => 1],
        ['label' => 'site_min_price', 'text' => 'Min. Prijs', 'editable' => 1],
        ['label' => 'site_filter', 'text' => 'Filter', 'editable' => 1],
        ['label' => 'site_add_to_cart', 'text' => 'In winkelwagen', 'editable' => 1],
        ['label' => 'site_error_retrieving_stock_refresh', 'text' => 'Er is een fout opgetreden bij het ophalen van de voorraden, ververs de pagina en probeer nog eens.', 'editable' => 1],
        ['label' => 'site_share_on_social_media', 'text' => 'Delen op social media', 'editable' => 1],
        ['label' => 'site_share', 'text' => 'Delen', 'editable' => 1],
        ['label' => 'site_specifications', 'text' => 'Specificaties', 'editable' => 1],
        ['label' => 'site_information', 'text' => 'Informatie', 'editable' => 1],
        ['label' => 'site_not_available', 'text' => 'niet beschikbaar', 'editable' => 1],
        ['label' => 'site_size_or_color', 'text' => 'Maat of kleur', 'editable' => 1],
        ['label' => 'site_order_now', 'text' => 'Bestel nu', 'editable' => 1],
        ['label' => 'site_amount', 'text' => 'aantal', 'editable' => 1],
        ['label' => 'site_select_a_color', 'text' => 'kies een kleur', 'editable' => 1],
        ['label' => 'site_color', 'text' => 'Kleur', 'editable' => 1],
        ['label' => 'site_brand', 'text' => 'Merk', 'editable' => 1],
        ['label' => 'site_size', 'text' => 'Maat', 'editable' => 1],
        ['label' => 'site_ex_vat', 'text' => 'excl. BTW', 'editable' => 1],
        ['label' => 'site_price', 'text' => 'Prijs', 'editable' => 1],
        ['label' => 'site_gender', 'text' => 'Geslacht', 'editable' => 1],
        ['label' => 'site_products', 'text' => 'Producten', 'editable' => 1],
        ['label' => 'site_discount_code_accepted', 'text' => 'Kortingscode geaccepteerd', 'editable' => 1],
        ['label' => 'site_discount_removed', 'text' => 'Korting verwijderd', 'editable' => 1],
        ['label' => 'site_can_not_change_payment_method_try_later', 'text' => 'Betaalmethode kon niet gewijzigd worden. Probeer het later nog eens.', 'editable' => 1],
        ['label' => 'site_can_not_change_delivery_method_try_later', 'text' => 'Verzendmethode kon niet gewijzigd worden. Probeer het later nog eens.', 'editable' => 1],
        ['label' => 'site_products_is_added', 'text' => 'product(en) is toegevoegd', 'editable' => 1],
        ['label' => 'site_max_stock_of', 'text' => 'Maximum voorraad van', 'editable' => 1],
        ['label' => 'site_can_not_change_amount', 'text' => 'Kon het aantal niet wijzigen', 'editable' => 1],
        ['label' => 'site_can_not_change_amount_try_later', 'text' => 'Productaantal kon niet gewijzigd worden. Probeer het later nog eens.', 'editable' => 1],
        ['label' => 'site_can_not_delete_product_try_later', 'text' => 'Product kon niet verwijderd worden van het winkelmandje. Probeer het later nog eens.', 'editable' => 1],
        ['label' => 'site_can_not_add_product_try_later', 'text' => 'Product kon niet toegevoegd worden aan het winkelmandje. Probeer het later nog eens.', 'editable' => 1],
        ['label' => 'site_product_added_to_shopping_cart', 'text' => 'Product toegevoegd aan uw winkelwagen', 'editable' => 1],
        ['label' => 'site_no_products_in_your_shopping_cart', 'text' => 'Geen producten in uw winkelmandje', 'editable' => 1],
        ['label' => 'site_order', 'text' => 'Bestellen', 'editable' => 1],
        ['label' => 'site_subtotal', 'text' => 'Subtotaal', 'editable' => 1],
        ['label' => 'site_go_to_your_shopping_cart', 'text' => 'Ga naar uw winkelwagen', 'editable' => 1],
        ['label' => 'site_your_shopping_cart', 'text' => 'Uw winkelwagen', 'editable' => 1],
        ['label' => 'site_no_preference', 'text' => 'Geen voorkeur', 'editable' => 1],
        ['label' => 'site_reset', 'text' => 'Reset', 'editable' => 1],
        ['label' => 'site_fill_in_a_coupon_code', 'text' => 'U moet een coupon code in voeren.', 'editable' => 1],
        ['label' => 'site_coupon_not_valid', 'text' => 'Coupon niet geldig', 'editable' => 1],
        ['label' => 'site_vat', 'text' => 'BTW', 'editable' => 1],
        ['label' => 'site_vat_excluded', 'text' => 'excl. BTW', 'editable' => 1],
        ['label' => 'site_total', 'text' => 'Totaal', 'editable' => 1],
        ['label' => 'site_payment_method', 'text' => 'Betaalmethode', 'editable' => 1],
        ['label' => 'site_delivery_method', 'text' => 'Aflevermethode', 'editable' => 1],
        ['label' => 'site_discount_code', 'text' => 'Kortingscode', 'editable' => 1],
        ['label' => 'site_can_not_change_shipping_method_try_later', 'text' => 'Verzendmethode kon niet gewijzigd worden. Probeer het later nog eens.', 'editable' => 1],
        ['label' => 'site_continue_shopping', 'text' => 'Verder winkelen', 'editable' => 1],
        ['label' => 'site_more', 'text' => 'meer', 'editable' => 1],
        ['label' => 'site_more_information_about', 'text' => 'Meer informatie over de', 'editable' => 1],
        ['label' => 'site_your_shopping_cart_is_empty', 'text' => 'U heeft geen artikelen in uw winkelwagen.', 'editable' => 1],
        ['label' => 'site_check_out', 'text' => 'Afrekenen', 'editable' => 1],
        ['label' => 'site_your_data', 'text' => 'Uw gegevens', 'editable' => 1],
        ['label' => 'site_proceed_to_checkout', 'text' => 'Doorgaan naar uw bestelling', 'editable' => 1],
        ['label' => 'site_select_color_amount', 'text' => 'Kies eerst een kleur en aantal', 'editable' => 1],
        ['label' => 'site_select_size_amount', 'text' => 'Kies eerst een maat en aantal', 'editable' => 1],
        ['label' => 'site_select_size_color_amount', 'text' => 'Kies eerst een maat, kleur en aantal', 'editable' => 1],
        ['label' => 'site_combiantion_delivery_payment_not_accepted', 'text' => 'Combinatie van aflevermethode en betaalmethode is niet toegestaan.', 'editable' => 1],
        ['label' => 'site_payment_method_not_accepted', 'text' => 'Betaalwijze wordt niet geaccepteerd', 'editable' => 1],
        ['label' => 'site_delivery_method_not_accepted', 'text' => 'Verzendwijze wordt niet geaccepteerd', 'editable' => 1],
        ['label' => 'site_shopping_cart', 'text' => 'Winkelwagen', 'editable' => 1],
        ['label' => 'site_male', 'text' => 'Man', 'editable' => 1],
        ['label' => 'site_female', 'text' => 'Vrouw', 'editable' => 1],
        ['label' => 'site_unisex', 'text' => 'Unisex', 'editable' => 1],
        ['label' => 'global_basket', 'text' => 'Winkelwagen', 'editable' => 1],
        ['label' => 'to_basket', 'text' => 'In winkelwagen', 'editable' => 1],
        ['label' => 'site_view_product', 'text' => 'Bekijk product', 'editable' => 1],
        ['label' => 'site_search_by_name', 'text' => 'Zoek op naam', 'editable' => 1],
        ['label' => 'site_zoom', 'text' => 'Inzoomen', 'editable' => 1],
    ],
];

// add pages
if (moduleExists('pages') && $oDb->tableExists('pages')) {
    if (!($oPageProducts = PageManager::getPageByName('products', DEFAULT_LANGUAGE_ID))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing page `products`';
        if ($bInstall) {
            $oPageProducts             = new Page();
            $oPageProducts->languageId = DEFAULT_LANGUAGE_ID;
            $oPageProducts->name       = 'products';
            $oPageProducts->title      = 'Webshop';
            $oPageProducts->content    = '<p>Bekijk hier alle producten zonder filter</p>';
            $oPageProducts->shortTitle = 'Webshop';
            $oPageProducts->forceUrlPath('/producten');
            $oPageProducts->setControllerPath('/modules/catalog/site/controllers/catalogProduct.cont.php');
            $oPageProducts->setOnlineChangeable(0);
            $oPageProducts->setDeletable(0);
            $oPageProducts->setMayHaveSub(0);
            $oPageProducts->setLockUrlPath(1);
            $oPageProducts->setLockParent(1);
            $oPageProducts->setHideImageManagement(1);
            $oPageProducts->setHideFileManagement(1);
            $oPageProducts->setHideLinkManagement(1);
            $oPageProducts->setHideVideoLinkManagement(1);
            if ($oPageProducts->isValid()) {
                PageManager::savePage($oPageProducts);
            } else {
                _d($oPageProducts->getInvalidProps());
                die('Can\'t create page `products`');
            }
        }
    }

    if (!($oPageProductCategories = PageManager::getPageByName('product_categories', DEFAULT_LANGUAGE_ID))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing page `product_categories`';
        if ($bInstall) {
            $oPageProductCategories             = new Page();
            $oPageProductCategories->languageId = DEFAULT_LANGUAGE_ID;
            $oPageProductCategories->name       = 'product_categories';
            $oPageProductCategories->title      = 'Product categorieën';
            $oPageProductCategories->content    = '<p>Hier staan alle product categorieën van de webshop</p>';
            $oPageProductCategories->shortTitle = 'Product categorieën';
            $oPageProductCategories->forceUrlPath('/productcategorieen');
            $oPageProductCategories->setControllerPath('/modules/catalog/site/controllers/catalogProductCategory.cont.php');
            $oPageProductCategories->setInMenu(0);
            $oPageProductCategories->setOnlineChangeable(0);
            $oPageProductCategories->setDeletable(0);
            $oPageProductCategories->setMayHaveSub(0);
            $oPageProductCategories->setLockUrlPath(1);
            $oPageProductCategories->setLockParent(1);
            $oPageProductCategories->setHideImageManagement(1);
            $oPageProductCategories->setHideFileManagement(1);
            $oPageProductCategories->setHideLinkManagement(1);
            $oPageProductCategories->setHideVideoLinkManagement(1);
            if ($oPageProductCategories->isValid()) {
                PageManager::savePage($oPageProductCategories);
            } else {
                _d($oPageProductCategories->getInvalidProps());
                die('Can\'t create page `product_categories`');
            }
        }
    }

    foreach (LocaleManager::getLocalesByFilter(['showAll' => true, 'NOTlanguageId' => DEFAULT_LANGUAGE_ID]) as $oLocale) {
        if (!($oNewPageProducts = PageManager::getPageByName('products', $oLocale->languageId))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing page `products` for language `' . strtoupper($oLocale->getLanguage()->code) . '`';
            if ($bInstall) {
                # create products page
                $oNewPageProducts             = new Page();
                $oNewPageProducts->languageId = $oLocale->languageId;
                $oNewPageProducts->name       = 'products';
                $oNewPageProducts->title      = 'Webshop';
                $oNewPageProducts->content    = '<p>See all products without filter</p>';
                $oNewPageProducts->shortTitle = 'Webshop';
                $oNewPageProducts->forceUrlPath('/products');
                $oNewPageProducts->setControllerPath('/modules/catalog/site/controllers/catalogProduct.cont.php');
                $oNewPageProducts->setOnlineChangeable(0);
                $oNewPageProducts->setDeletable(0);
                $oNewPageProducts->setMayHaveSub(0);
                $oNewPageProducts->setLockUrlPath(1);
                $oNewPageProducts->setLockParent(1);
                $oNewPageProducts->setHideImageManagement(1);
                $oNewPageProducts->setHideFileManagement(1);
                $oNewPageProducts->setHideLinkManagement(1);
                $oNewPageProducts->setHideVideoLinkManagement(1);
                if ($oNewPageProducts->isValid()) {
                    PageManager::savePage($oNewPageProducts);
                } else {
                    _d($oNewPageProducts->getInvalidProps());
                    die('Can\'t create page `products`');
                }
            }
        }

        if (!($oNewPageProductCategories = PageManager::getPageByName('product_categories', $oLocale->languageId))) {
            $aLogs[$sModuleName]['errors'][] = 'Missing page `product_categories` for language `' . strtoupper($oLocale->getLanguage()->code) . '`';
            if ($bInstall) {
                # create product categories page
                $oNewPageProductCategories             = new Page();
                $oNewPageProductCategories->languageId = $oLocale->languageId;
                $oNewPageProductCategories->name       = 'product_categories';
                $oNewPageProductCategories->title      = 'Produc categories';
                $oNewPageProductCategories->content    = '<p>Here you can find all the product categories of the webshop.</p>';
                $oNewPageProductCategories->shortTitle = 'Product categories';
                $oNewPageProductCategories->forceUrlPath('/product-categories');
                $oNewPageProductCategories->setControllerPath('/modules/catalog/site/controllers/catalogProductCategory.cont.php');
                $oNewPageProductCategories->setInMenu(0);
                $oNewPageProductCategories->setOnlineChangeable(0);
                $oNewPageProductCategories->setDeletable(0);
                $oNewPageProductCategories->setMayHaveSub(0);
                $oNewPageProductCategories->setLockUrlPath(1);
                $oNewPageProductCategories->setLockParent(1);
                $oNewPageProductCategories->setHideImageManagement(1);
                $oNewPageProductCategories->setHideFileManagement(1);
                $oNewPageProductCategories->setHideLinkManagement(1);
                $oNewPageProductCategories->setHideVideoLinkManagement(1);
                if ($oNewPageProductCategories->isValid()) {
                    PageManager::savePage($oNewPageProductCategories);
                } else {
                    _d($oNewPageProductCategories->getInvalidProps());
                    die('Can\'t create page `product_categories`');
                }
            }
        }
    }
}

// check settings
if (moduleExists('core')) {
    if (!($oSetting1 = SettingManager::getSettingByName('taxIncluded'))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing setting `taxIncluded`';
        if ($bInstall) {
            $oSetting1        = new Setting();
            $oSetting1->name  = 'taxIncluded';
            $oSetting1->value = '1';
            if ($oSetting1->isValid()) {
                SettingManager::saveSetting($oSetting1);
            } else {
                _d($oSetting1->getInvalidProps());
                die('Can\'t create setting `taxIncluded`');
            }
        }
    }

    if (!($oSetting2 = SettingManager::getSettingByName('catalogFilterShowAllOptions'))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing setting `catalogFilterShowAllOptions`';
        if ($bInstall) {
            $oSetting2        = new Setting();
            $oSetting2->name  = 'catalogFilterShowAllOptions';
            $oSetting2->value = '0';
            if ($oSetting2->isValid()) {
                SettingManager::saveSetting($oSetting2);
            } else {
                _d($oSetting2->getInvalidProps());
                die('Can\'t create setting `catalogFilterShowAllOptions`');
            }
        }
    }

    if (!($oSetting3 = SettingManager::getSettingByName('catalogFilterMaxChars'))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing setting `catalogFilterMaxChars`';
        if ($bInstall) {
            $oSetting3        = new Setting();
            $oSetting3->name  = 'catalogFilterMaxChars';
            $oSetting3->value = '17';
            if ($oSetting3->isValid()) {
                SettingManager::saveSetting($oSetting3);
            } else {
                _d($oSetting3->getInvalidProps());
                die('Can\'t create setting `catalogFilterMaxChars`');
            }
        }
    }

    if (!($oSetting4 = SettingManager::getSettingByName('reducedPriceWithDiscount'))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing setting `reducedPriceWithDiscount`';
        if ($bInstall) {
            $oSetting4        = new Setting();
            $oSetting4->name  = 'reducedPriceWithDiscount';
            $oSetting4->value = '1';
            if ($oSetting4->isValid()) {
                SettingManager::saveSetting($oSetting4);
            } else {
                _d($oSetting4->getInvalidProps());
                die('Can\'t create setting `reducedPriceWithDiscount`');
            }
        }
    }
}

// Database checks
if (!$oDb->tableExists('catalog_brands')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `catalog_brands`';
    if ($bInstall) {
        // add table
        $sQuery = '
        CREATE TABLE `catalog_brands` (
          `catalogBrandId` int(11) NOT NULL AUTO_INCREMENT,
          `multilingual` tinyint(1) NOT NULL DEFAULT \'0\',
          `online` int(1) NOT NULL DEFAULT \'1\',
          `order` int(11) NOT NULL DEFAULT \'99999\',
          `created` timestamp NULL DEFAULT NULL,
          `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`catalogBrandId`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if (!$oDb->tableExists('catalog_brand_translations')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `catalog_brand_translations`';
    if ($bInstall) {
        // add table
        $sQuery = '
        CREATE TABLE `catalog_brand_translations` (
          `catalogBrandTranslationId` int(11) NOT NULL AUTO_INCREMENT,
          `catalogBrandId` int(11) NOT NULL,
          `languageId` int(11) NOT NULL DEFAULT \'1\',
          `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          PRIMARY KEY (`catalogBrandTranslationId`),
          UNIQUE KEY `catalogBrandId_languageId` (`catalogBrandId`,`languageId`),
          KEY `catalogBrandId` (`catalogBrandId`),
          KEY `languageId` (`languageId`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if (!$oDb->tableExists('catalog_products')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `catalog_products`';
    if ($bInstall) {
        // add table
        $sQuery = '
        CREATE TABLE `catalog_products` (
          `catalogProductId` int(11) NOT NULL AUTO_INCREMENT,
          `salePrice` decimal(10,4) NOT NULL,
          `purchasePrice` decimal(10,4) NOT NULL,
          `reducedPrice` decimal(10,4) DEFAULT NULL,
          `catalogProductMPN` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
          `gender` enum(\'male\',\'female\',\'unisex\') COLLATE utf8_unicode_ci NOT NULL,
          `online` int(1) NOT NULL DEFAULT \'1\',
          `created` timestamp NULL DEFAULT NULL,
          `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
          `catalogProductTypeId` int(11) NOT NULL,
          `catalogBrandId` int(11) NOT NULL,
          `taxPercentageId` int(11) NOT NULL DEFAULT \'1\',
          `showOnHome` tinyint(1) NOT NULL DEFAULT \'0\',
          PRIMARY KEY (`catalogProductId`),
          KEY `catalogProducts_catalogProductTypeId` (`catalogProductTypeId`),
          KEY `catalogProducts_catalogBrandId` (`catalogBrandId`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if (!$oDb->tableExists('catalog_product_translations')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `catalog_product_translations`';
    if ($bInstall) {
        // add table
        $sQuery = '
        CREATE TABLE `catalog_product_translations` (
          `catalogProductTranslationId` int(11) NOT NULL AUTO_INCREMENT,
          `catalogProductId` int(11) NOT NULL,
          `languageId` int(11) NOT NULL DEFAULT \'1\',
          `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `description` text COLLATE utf8_unicode_ci,
          `windowTitle` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `metaKeywords` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `metaDescription` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `urlPart` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `googleCategory` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          PRIMARY KEY (`catalogProductTranslationId`),
          UNIQUE KEY `catalogProductId_languageId` (`catalogProductId`,`languageId`),
          KEY `catalogProductId` (`catalogProductId`),
          KEY `languageId` (`languageId`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if (!$oDb->tableExists('catalog_products_catalog_product_categories')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `catalog_products_catalog_product_categories`';
    if ($bInstall) {
        // add table
        $sQuery = '
        CREATE TABLE `catalog_products_catalog_product_categories` (
          `catalogProductCategoryId` int(11) NOT NULL,
          `catalogProductId` int(11) NOT NULL,
          PRIMARY KEY (`catalogProductCategoryId`,`catalogProductId`),
          KEY `catalogProductsCatalogProductCategories_catalogProductCategoryId` (`catalogProductCategoryId`),
          KEY `catalogProductsCatalogProductCategories_catalogProductId` (`catalogProductId`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if (!$oDb->tableExists('catalog_products_images')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `catalog_products_images`';
    if ($bInstall) {
        // add table
        $sQuery = '
            CREATE TABLE `catalog_products_images` (
              `catalogProductId` int(11) NOT NULL,
              `imageId` int(11) NOT NULL,
              `catalogProductColorId` int(11) DEFAULT NULL,
              PRIMARY KEY (`catalogProductId`,`imageId`),
              KEY `catalogProductsImages_catalogProductId` (`catalogProductId`),
              KEY `catalogProductsImages_imageId` (`imageId`),
              KEY `catalogProductColorId` (`catalogProductColorId`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
            ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if (!$oDb->tableExists('catalog_product_categories')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `catalog_product_categories`';
    if ($bInstall) {

        // add table
        $sQuery = '
        CREATE TABLE `catalog_product_categories` (
          `catalogProductCategoryId` int(11) NOT NULL AUTO_INCREMENT,
          `online` tinyint(1) NOT NULL DEFAULT \'0\',
          `order` int(11) NOT NULL DEFAULT \'99999\',
          `parentCatalogProductCategoryId` int(11) DEFAULT NULL,
          `level` int(11) NOT NULL DEFAULT \'1\',
          `lockParent` tinyint(1) NOT NULL DEFAULT \'0\',
          `created` timestamp NULL DEFAULT NULL,
          `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`catalogProductCategoryId`),
          KEY `parentCatalogProductCategories_parentCatalogProductCategoryId` (`parentCatalogProductCategoryId`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if (!$oDb->tableExists('catalog_product_category_translations')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `catalog_product_category_translations`';
    if ($bInstall) {
        // add table
        $sQuery = '
        CREATE TABLE `catalog_product_category_translations` (
          `catalogProductCategoryTranslationId` int(11) NOT NULL AUTO_INCREMENT,
          `catalogProductCategoryId` int(11) NOT NULL,
          `languageId` int(11) NOT NULL DEFAULT \'0\',
          `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `content` text COLLATE utf8_unicode_ci,
          `windowTitle` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `metaKeywords` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `metaDescription` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          `urlPart` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          PRIMARY KEY (`catalogProductCategoryTranslationId`),
          UNIQUE KEY `catalogProductCategoryId_languageId` (`catalogProductCategoryId`,`languageId`),
          KEY `catalogProductCategoryId` (`catalogProductCategoryId`),
          KEY `languageId` (`languageId`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if (!$oDb->tableExists('catalog_product_colors')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `catalog_product_colors`';
    if ($bInstall) {
        // add table
        $sQuery = '
        CREATE TABLE `catalog_product_colors` (
          `catalogProductColorId` int(11) NOT NULL AUTO_INCREMENT,
          `order` int(11) NOT NULL DEFAULT \'9999\',
          `created` timestamp NULL DEFAULT NULL,
          `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`catalogProductColorId`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if (!$oDb->tableExists('catalog_product_color_translations')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `catalog_product_color_translations`';
    if ($bInstall) {
        // add table
        $sQuery = '
        CREATE TABLE `catalog_product_color_translations` (
          `catalogProductColorTranslationId` int(11) NOT NULL AUTO_INCREMENT,
          `catalogProductColorId` int(11) NOT NULL,
          `languageId` int(11) NOT NULL DEFAULT \'0\',
          `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          PRIMARY KEY (`catalogProductColorTranslationId`),
          UNIQUE KEY `catalogProductColorId_languageId` (`catalogProductColorId`,`languageId`),
          KEY `catalogProductColorId` (`catalogProductColorId`),
          KEY `languageId` (`languageId`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if (!$oDb->tableExists('catalog_product_property_types')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `catalog_product_property_types`';
    if ($bInstall) {
        // add table
        $sQuery = '
        CREATE TABLE `catalog_product_property_types` (
          `catalogProductPropertyTypeId` int(11) NOT NULL AUTO_INCREMENT,
          `type` enum(\'text\',\'checkbox\',\'select\') COLLATE utf8_unicode_ci NOT NULL,
          `inputTranslatable` TINYINT(1) NOT NULL DEFAULT \'1\',
          `filterType` enum(\'text\',\'checkbox\',\'select\',\'min-max\') COLLATE utf8_unicode_ci DEFAULT NULL,
          `order` int(11) NOT NULL,
          `catalogProductPropertyTypeGroupId` int(11) NOT NULL,
          `created` timestamp NULL DEFAULT NULL,
          `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`catalogProductPropertyTypeId`),
          KEY `catalogProductPropertyTypeGroupId` (`catalogProductPropertyTypeGroupId`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if (!$oDb->tableExists('catalog_product_property_type_translations')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `catalog_product_property_type_translations`';
    if ($bInstall) {
        // add table
        $sQuery = '
        CREATE TABLE `catalog_product_property_type_translations` (
          `catalogProductPropertyTypeTranslationId` int(11) NOT NULL AUTO_INCREMENT,
          `catalogProductPropertyTypeId` int(11) NOT NULL,
          `languageId` int(11) NOT NULL DEFAULT \'0\',
          `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          PRIMARY KEY (`catalogProductPropertyTypeTranslationId`),
          UNIQUE KEY `catalogProductPropertyTypeId_languageId` (`catalogProductPropertyTypeId`,`languageId`),
          KEY `catalogProductPropertyTypeId` (`catalogProductPropertyTypeId`),
          KEY `languageId` (`languageId`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if (!$oDb->tableExists('catalog_product_property_type_groups')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `catalog_product_property_type_groups`';
    if ($bInstall) {
        // add table
        $sQuery = '
        CREATE TABLE `catalog_product_property_type_groups` (
          `catalogProductPropertyTypeGroupId` int(11) NOT NULL AUTO_INCREMENT,
          `catalogProductTypeId` int(11) NOT NULL,
          `order` int(11) NOT NULL,
          `created` timestamp NULL DEFAULT NULL,
          `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`catalogProductPropertyTypeGroupId`),
          KEY `catalogProductPropertyTypeGroups_catalogProductTypeId` (`catalogProductTypeId`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if (!$oDb->tableExists('catalog_product_property_type_group_translations')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `catalog_product_property_type_group_translations`';
    if ($bInstall) {

        // add table
        $sQuery = '
        CREATE TABLE `catalog_product_property_type_group_translations` (
          `catalogProductPropertyTypeGroupTranslationId` int(11) NOT NULL AUTO_INCREMENT,
          `catalogProductPropertyTypeGroupId` int(11) NOT NULL,
          `languageId` int(11) NOT NULL DEFAULT \'0\',
          `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          PRIMARY KEY (`catalogProductPropertyTypeGroupTranslationId`),
          UNIQUE KEY `catalogProductPropertyTypeGroupId_languageId` (`catalogProductPropertyTypeGroupId`,`languageId`),
          KEY `catalogProductPropertyTypeGroupId` (`catalogProductPropertyTypeGroupId`),
          KEY `languageId` (`languageId`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if (!$oDb->tableExists('catalog_product_property_type_possible_values')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `catalog_product_property_type_possible_values`';
    if ($bInstall) {
        // add table
        $sQuery = '
        CREATE TABLE `catalog_product_property_type_possible_values` (
          `catalogProductPropertyTypePossibleValueId` int(11) NOT NULL AUTO_INCREMENT,
          `multilingual` tinyint(1) NOT NULL DEFAULT \'0\',
          `order` int(11) NOT NULL DEFAULT \'99999\',
          `catalogProductPropertyTypeId` int(11) NOT NULL,
          `created` timestamp NULL DEFAULT NULL,
          `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`catalogProductPropertyTypePossibleValueId`),
          KEY `catalogPrdctPrprtyTypePssbleValues_catalogProductPropertyTypeId` (`catalogProductPropertyTypeId`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if (!$oDb->tableExists('catalog_product_property_type_possible_value_translations')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `catalog_product_property_type_possible_value_translations`';
    if ($bInstall) {
        // add table
        $sQuery = '
        CREATE TABLE `catalog_product_property_type_possible_value_translations` (
          `catalogProductPropertyTypePossibleValueTranslationId` int(11) NOT NULL AUTO_INCREMENT,
          `catalogProductPropertyTypePossibleValueId` int(11) NOT NULL,
          `languageId` int(11) NOT NULL DEFAULT \'0\',
          `value` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
          PRIMARY KEY (`catalogProductPropertyTypePossibleValueTranslationId`),
          UNIQUE KEY `catalogProductPropertyTypePossibleValueId_languageId` (`catalogProductPropertyTypePossibleValueId`,`languageId`),
          KEY `catalogProductPropertyTypePossibleValueId` (`catalogProductPropertyTypePossibleValueId`),
          KEY `languageId` (`languageId`),
          KEY `value` (`value`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if (!$oDb->tableExists('catalog_product_property_values')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `catalog_product_property_values`';
    if ($bInstall) {
        // add table
        $sQuery = '
        CREATE TABLE IF NOT EXISTS `catalog_product_property_values` (
          `catalogProductPropertyValueId` int(11) NOT NULL AUTO_INCREMENT,
          `catalogProductId` int(11) NOT NULL,
          `catalogProductPropertyTypeId` int(11) NOT NULL,
          `languageId` int(11) NOT NULL,
          `value` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
          PRIMARY KEY (`catalogProductPropertyValueId`),
          KEY `catalogProductPropertyValues_catalogProductId` (`catalogProductId`),
          KEY `catalogProductPropertyValues_catalogProductPropertyTypeId` (`catalogProductPropertyTypeId`),
          KEY `value` (`value`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if (!$oDb->tableExists('catalog_product_related_products')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `catalog_product_related_products`';
    if ($bInstall) {
        // add table
        $sQuery = '
        CREATE TABLE `catalog_product_related_products` (
          `productId` int(11) NOT NULL,
          `relatedProductId` int(11) NOT NULL,
          PRIMARY KEY (`productId`,`relatedProductId`),
          KEY `fk_webshopRelatedProducts_productId` (`productId`),
          KEY `fk_webshopRelatedProducts_relatedProductId` (`relatedProductId`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if (!$oDb->tableExists('catalog_product_sizes')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `catalog_product_sizes`';
    if ($bInstall) {
        // add table
        $sQuery = '
        CREATE TABLE `catalog_product_sizes` (
          `catalogProductSizeId` int(11) NOT NULL AUTO_INCREMENT,
          `multilingual` tinyint(1) NOT NULL DEFAULT \'0\',
          `order` int(11) NOT NULL DEFAULT \'9999\',
          `created` timestamp NULL DEFAULT NULL,
          `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`catalogProductSizeId`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if (!$oDb->tableExists('catalog_product_size_translations')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `catalog_product_size_translations`';
    if ($bInstall) {
        // add table
        $sQuery = '
        CREATE TABLE `catalog_product_size_translations` (
          `catalogProductSizeTranslationId` int(11) NOT NULL AUTO_INCREMENT,
          `catalogProductSizeId` int(11) NOT NULL,
          `languageId` int(11) NOT NULL DEFAULT \'0\',
          `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          PRIMARY KEY (`catalogProductSizeTranslationId`),
          UNIQUE KEY `catalogProductSizeId_languageId` (`catalogProductSizeId`,`languageId`),
          KEY `catalogProductSizeId` (`catalogProductSizeId`),
          KEY `languageId` (`languageId`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if (!$oDb->tableExists('catalog_product_size_color_relations')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `catalog_product_size_color_relations`';
    if ($bInstall) {
        // add table
        $sQuery = '
        CREATE TABLE IF NOT EXISTS `catalog_product_size_color_relations` (
          `catalogProductId` int(11) NOT NULL,
          `catalogProductColorId` int(11) NOT NULL,
          `catalogProductSizeId` int(11) NOT NULL,
          `catalogProductSizeColorMPN` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
          `stock` int(11) DEFAULT NULL,
          `extraPrice` decimal(10,2) NOT NULL DEFAULT \'0.00\',
          UNIQUE KEY `catalogProductId` (`catalogProductId`,`catalogProductColorId`,`catalogProductSizeId`),
          KEY `catalogProductSizesColors_catalogProductSizeId` (`catalogProductSizeId`),
          KEY `catalogProductSizesColors_catalogProductColorId` (`catalogProductColorId`),
          KEY `catalogProductSizesColors_catalogProductId` (`catalogProductId`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if (!$oDb->tableExists('catalog_product_types')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `catalog_product_types`';
    if ($bInstall) {
        // add table
        $sQuery = '
        CREATE TABLE `catalog_product_types` (
          `catalogProductTypeId` int(11) NOT NULL AUTO_INCREMENT,
          `withSizes` tinyint(1) NOT NULL DEFAULT \'0\',
          `withColors` tinyint(1) NOT NULL DEFAULT \'0\',
          `withGenders` tinyint(1) NOT NULL DEFAULT \'0\',
          `order` int(11) NOT NULL DEFAULT \'99999\',
          `created` timestamp NULL DEFAULT NULL,
          `modified` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`catalogProductTypeId`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

if (!$oDb->tableExists('catalog_product_type_translations')) {
    $aLogs[$sModuleName]['errors'][] = 'Missing table `catalog_product_type_translations`';
    if ($bInstall) {
        // add table
        $sQuery = '
        CREATE TABLE `catalog_product_type_translations` (
          `catalogProductTypeTranslationId` int(11) NOT NULL AUTO_INCREMENT,
          `catalogProductTypeId` int(11) NOT NULL,
          `languageId` int(11) NOT NULL DEFAULT \'0\',
          `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          PRIMARY KEY (`catalogProductTypeTranslationId`),
          UNIQUE KEY `catalogProductTypeId_languageId` (`catalogProductTypeId`,`languageId`),
          KEY `catalogProductTypeId` (`catalogProductTypeId`),
          KEY `languageId` (`languageId`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        ';
        $oDb->query($sQuery, QRY_NORESULT);
    }
}

// check catalog_products constraints
if ($oDb->tableExists('catalog_products')) {
    if ($oDb->tableExists('catalog_brands')) {
        // check catalog_brands constraint
        if (!$oDb->constraintExists('catalog_products', 'catalogBrandId', 'catalog_brands', 'catalogBrandId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `catalog_products`.`catalogBrandId` => `catalog_brands`.`catalogBrandId`';
            if ($bInstall) {
                $oDb->addConstraint('catalog_products', 'catalogBrandId', 'catalog_brands', 'catalogBrandId', 'CASCADE', 'CASCADE');
            }
        }
    }
    if ($oDb->tableExists('catalog_product_types')) {
        // check catalog_product_types constraint
        if (!$oDb->constraintExists('catalog_products', 'catalogProductTypeId', 'catalog_product_types', 'catalogProductTypeId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `catalog_products`.`catalogProductTypeId` => `catalog_product_types`.`catalogProductTypeId`';
            if ($bInstall) {
                $oDb->addConstraint('catalog_products', 'catalogProductTypeId', 'catalog_product_types', 'catalogProductTypeId', 'CASCADE', 'CASCADE');
            }
        }
    }
}

// check catalog_products_catalog_product_categories constraints
if ($oDb->tableExists('catalog_products_catalog_product_categories')) {
    if ($oDb->tableExists('catalog_product_categories')) {
        // check catalog_product_categories constraint
        if (!$oDb->constraintExists('catalog_products_catalog_product_categories', 'catalogProductCategoryId', 'catalog_product_categories', 'catalogProductCategoryId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `catalog_products_catalog_product_categories`.`catalogProductCategoryId` => `catalog_product_categories`.`catalogProductCategoryId`';
            if ($bInstall) {
                $oDb->addConstraint('catalog_products_catalog_product_categories', 'catalogProductCategoryId', 'catalog_product_categories', 'catalogProductCategoryId', 'CASCADE', 'CASCADE');
            }
        }
    }
    if ($oDb->tableExists('catalog_products')) {
        // check catalog_products constraint
        if (!$oDb->constraintExists('catalog_products_catalog_product_categories', 'catalogProductId', 'catalog_products', 'catalogProductId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `catalog_products_catalog_product_categories`.`catalogProductId` => `catalog_products`.`catalogProductId`';
            if ($bInstall) {
                $oDb->addConstraint('catalog_products_catalog_product_categories', 'catalogProductId', 'catalog_products', 'catalogProductId', 'CASCADE', 'CASCADE');
            }
        }
    }
}

// check catalog_products_images constraints
if ($oDb->tableExists('catalog_products_images')) {
    if ($oDb->tableExists('catalog_products')) {
        // check catalog_products constraint
        if (!$oDb->constraintExists('catalog_products_images', 'catalogProductId', 'catalog_products', 'catalogProductId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `catalog_products_images`.`catalogProductId` => `catalog_products`.`catalogProductId`';
            if ($bInstall) {
                $oDb->addConstraint('catalog_products_images', 'catalogProductId', 'catalog_products', 'catalogProductId', 'RESTRICT', 'CASCADE');
            }
        }
    }

    if ($oDb->tableExists('catalog_product_colors')) {
        // check catalog_product_colors constraint
        if (!$oDb->constraintExists('catalog_products_images', 'catalogProductColorId', 'catalog_product_colors', 'catalogProductColorId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `catalog_products_images`.`catalogProductColorId` => `catalog_product_colors`.`catalogProductColorId`';
            if ($bInstall) {
                $oDb->addConstraint('catalog_products_images', 'catalogProductColorId', 'catalog_product_colors', 'catalogProductColorId', 'SET NULL', 'CASCADE');
            }
        }
    }

    if ($oDb->tableExists('images')) {
        // check images constraint
        if (!$oDb->constraintExists('catalog_products_images', 'imageId', 'images', 'imageId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `catalog_products_images`.`imageId` => `images`.`imageId`';
            if ($bInstall) {
                $oDb->addConstraint('catalog_products_images', 'imageId', 'images', 'imageId', 'CASCADE', 'CASCADE');
            }
        }
    }
}

// check catalog_product_categories constraints
if ($oDb->tableExists('catalog_product_categories')) {
    // check catalog_product_categories constraint
    if (!$oDb->constraintExists('catalog_product_categories', 'parentCatalogProductCategoryId', 'catalog_product_categories', 'catalogProductCategoryId')) {
        $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `catalog_product_categories`.`parentCatalogProductCategoryId` => `catalog_product_categories`.`catalogProductCategoryId`';
        if ($bInstall) {
            $oDb->addConstraint('catalog_product_categories', 'parentCatalogProductCategoryId', 'catalog_product_categories', 'catalogProductCategoryId', 'RESTRICT', 'CASCADE');
        }
    }
}

// check catalog_product_property_types constraints
if ($oDb->tableExists('catalog_product_property_types')) {
    if ($oDb->tableExists('catalog_product_property_type_groups')) {
        // check catalog_product_property_type_groups constraint
        if (!$oDb->constraintExists('catalog_product_property_types', 'catalogProductPropertyTypeGroupId', 'catalog_product_property_type_groups', 'catalogProductPropertyTypeGroupId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `catalog_product_property_types`.`catalogProductPropertyTypeGroupId` => `catalog_product_property_type_groups`.`catalogProductPropertyTypeGroupId`';
            if ($bInstall) {
                $oDb->addConstraint('catalog_product_property_types', 'catalogProductPropertyTypeGroupId', 'catalog_product_property_type_groups', 'catalogProductPropertyTypeGroupId', 'RESTRICT', 'CASCADE');
            }
        }
    }
}

// check catalog_product_property_type_groups constraints
if ($oDb->tableExists('catalog_product_property_type_groups')) {
    if ($oDb->tableExists('catalog_product_types')) {
        // check catalog_product_types constraint
        if (!$oDb->constraintExists('catalog_product_property_type_groups', 'catalogProductTypeId', 'catalog_product_types', 'catalogProductTypeId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `catalog_product_property_type_groups`.`catalogProductTypeId` => `catalog_product_types`.`catalogProductTypeId`';
            if ($bInstall) {
                $oDb->addConstraint('catalog_product_property_type_groups', 'catalogProductTypeId', 'catalog_product_types', 'catalogProductTypeId', 'CASCADE', 'CASCADE');
            }
        }
    }
}

// check catalog_product_property_type_possible_values constraints
if ($oDb->tableExists('catalog_product_property_type_possible_values')) {
    if ($oDb->tableExists('catalog_product_property_types')) {
        // check catalog_product_property_types constraint
        if (!$oDb->constraintExists('catalog_product_property_type_possible_values', 'catalogProductPropertyTypeId', 'catalog_product_property_types', 'catalogProductPropertyTypeId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `catalog_product_property_type_possible_values`.`catalogProductPropertyTypeId` => `catalog_product_property_types`.`catalogProductPropertyTypeId`';
            if ($bInstall) {
                $oDb->addConstraint('catalog_product_property_type_possible_values', 'catalogProductPropertyTypeId', 'catalog_product_property_types', 'catalogProductPropertyTypeId', 'CASCADE', 'CASCADE');
            }
        }
    }
}

// check catalog_product_property_type_possible_values constraints
if ($oDb->tableExists('catalog_product_property_values')) {
    if ($oDb->tableExists('catalog_products')) {
        // check catalog_products constraint
        if (!$oDb->constraintExists('catalog_product_property_values', 'catalogProductId', 'catalog_products', 'catalogProductId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `catalog_product_property_values`.`catalogProductId` => `catalog_products`.`catalogProductId`';
            if ($bInstall) {
                $oDb->addConstraint('catalog_product_property_values', 'catalogProductId', 'catalog_products', 'catalogProductId', 'CASCADE', 'CASCADE');
            }
        }
    }

    if ($oDb->tableExists('catalog_product_property_types')) {
        // check catalog_product_property_types constraint
        if (!$oDb->constraintExists('catalog_product_property_values', 'catalogProductPropertyTypeId', 'catalog_product_property_types', 'catalogProductPropertyTypeId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `catalog_product_property_values`.`catalogProductPropertyTypeId` => `catalog_product_property_types`.`catalogProductPropertyTypeId`';
            if ($bInstall) {
                $oDb->addConstraint('catalog_product_property_values', 'catalogProductPropertyTypeId', 'catalog_product_property_types', 'catalogProductPropertyTypeId', 'CASCADE', 'CASCADE');
            }
        }
    }

    if ($oDb->tableExists('languages')) {
        // check catalog_product_property_types constraint
        if (!$oDb->constraintExists('catalog_product_property_values', 'languageId', 'languages', 'languageId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `catalog_product_property_values`.`languageId` => `languages`.`languageId`';
            if ($bInstall) {
                $oDb->addConstraint('catalog_product_property_values', 'languageId', 'languages', 'languageId', 'CASCADE', 'CASCADE');
            }
        }
    }
}

// check catalog_product_related_products constraints
if ($oDb->tableExists('catalog_product_related_products')) {
    if ($oDb->tableExists('catalog_products')) {
        // check catalog_products constraint
        if (!$oDb->constraintExists('catalog_product_related_products', 'productId', 'catalog_products', 'catalogProductId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `catalog_product_related_products`.`productId` => `catalog_products`.`catalogProductId`';
            if ($bInstall) {
                $oDb->addConstraint('catalog_product_related_products', 'productId', 'catalog_products', 'catalogProductId', 'CASCADE', 'CASCADE');
            }
        }

        // check catalog_products constraint
        if (!$oDb->constraintExists('catalog_product_related_products', 'relatedProductId', 'catalog_products', 'catalogProductId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `catalog_product_related_products`.`relatedProductId` => `catalog_products`.`catalogProductId`';
            if ($bInstall) {
                $oDb->addConstraint('catalog_product_related_products', 'relatedProductId', 'catalog_products', 'catalogProductId', 'CASCADE', 'CASCADE');
            }
        }
    }
}

// check catalog_product_property_type_possible_values constraints
if ($oDb->tableExists('catalog_product_size_color_relations')) {
    if ($oDb->tableExists('catalog_product_colors')) {
        // check catalog_product_colors constraint
        if (!$oDb->constraintExists('catalog_product_size_color_relations', 'catalogProductColorId', 'catalog_product_colors', 'catalogProductColorId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `catalog_product_size_color_relations`.`catalogProductColorId` => `catalog_product_colors`.`catalogProductColorId`';
            if ($bInstall) {
                $oDb->addConstraint('catalog_product_size_color_relations', 'catalogProductColorId', 'catalog_product_colors', 'catalogProductColorId', 'CASCADE', 'CASCADE');
            }
        }
    }

    if ($oDb->tableExists('catalog_products')) {
        // check catalog_products constraint
        if (!$oDb->constraintExists('catalog_product_size_color_relations', 'catalogProductId', 'catalog_products', 'catalogProductId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `catalog_product_size_color_relations`.`catalogProductId` => `catalog_products`.`catalogProductId`';
            if ($bInstall) {
                $oDb->addConstraint('catalog_product_size_color_relations', 'catalogProductId', 'catalog_products', 'catalogProductId', 'CASCADE', 'CASCADE');
            }
        }
    }

    if ($oDb->tableExists('catalog_product_sizes')) {
        // check catalog_product_sizes constraint
        if (!$oDb->constraintExists('catalog_product_size_color_relations', 'catalogProductSizeId', 'catalog_product_sizes', 'catalogProductSizeId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `catalog_product_size_color_relations`.`catalogProductSizeId` => `catalog_product_sizes`.`catalogProductSizeId`';
            if ($bInstall) {
                $oDb->addConstraint('catalog_product_size_color_relations', 'catalogProductSizeId', 'catalog_product_sizes', 'catalogProductSizeId', 'CASCADE', 'CASCADE');
            }
        }
    }
}

// check catalog_brand_translations constraints
if ($oDb->tableExists('catalog_brand_translations')) {
    if ($oDb->tableExists('catalog_brands')) {
        // check catalog_brands constraint
        if (!$oDb->constraintExists('catalog_brand_translations', 'catalogBrandId', 'catalog_brands', 'catalogBrandId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `catalog_brand_translations`.`catalogBrandId` => `catalog_brands`.`catalogBrandId`';
            if ($bInstall) {
                $oDb->addConstraint('catalog_brand_translations', 'catalogBrandId', 'catalog_brands', 'catalogBrandId', 'CASCADE', 'CASCADE');
            }
        }
    }

    if ($oDb->tableExists('languages')) {
        // check languages constraint
        if (!$oDb->constraintExists('catalog_brand_translations', 'languageId', 'languages', 'languageId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `catalog_brand_translations`.`languageId` => `languages`.`languageId`';
            if ($bInstall) {
                $oDb->addConstraint('catalog_brand_translations', 'languageId', 'languages', 'languageId', 'RESTRICT', 'CASCADE');
            }
        }
    }
}

// check catalog_product_category_translations constraints
if ($oDb->tableExists('catalog_product_category_translations')) {
    if ($oDb->tableExists('catalog_product_categories')) {
        // check catalog_product_categories constraint
        if (!$oDb->constraintExists('catalog_product_category_translations', 'catalogProductCategoryId', 'catalog_product_categories', 'catalogProductCategoryId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `catalog_product_category_translations`.`catalogProductCategoryId` => `catalog_product_categories`.`catalogProductCategoryId`';
            if ($bInstall) {
                $oDb->addConstraint('catalog_product_category_translations', 'catalogProductCategoryId', 'catalog_product_categories', 'catalogProductCategoryId', 'CASCADE', 'CASCADE');
            }
        }
    }

    if ($oDb->tableExists('languages')) {
        // check languages constraint
        if (!$oDb->constraintExists('catalog_product_category_translations', 'languageId', 'languages', 'languageId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `catalog_product_category_translations`.`languageId` => `languages`.`languageId`';
            if ($bInstall) {
                $oDb->addConstraint('catalog_product_category_translations', 'languageId', 'languages', 'languageId', 'RESTRICT', 'CASCADE');
            }
        }
    }
}

// check catalog_product_color_translations constraints
if ($oDb->tableExists('catalog_product_color_translations')) {
    if ($oDb->tableExists('catalog_product_colors')) {
        // check catalog_product_colors constraint
        if (!$oDb->constraintExists('catalog_product_color_translations', 'catalogProductColorId', 'catalog_product_colors', 'catalogProductColorId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `catalog_product_color_translations`.`catalogProductColorId` => `catalog_product_colors`.`catalogProductColorId`';
            if ($bInstall) {
                $oDb->addConstraint('catalog_product_color_translations', 'catalogProductColorId', 'catalog_product_colors', 'catalogProductColorId', 'CASCADE', 'CASCADE');
            }
        }
    }

    if ($oDb->tableExists('languages')) {
        // check languages constraint
        if (!$oDb->constraintExists('catalog_product_color_translations', 'languageId', 'languages', 'languageId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `catalog_product_color_translations`.`languageId` => `languages`.`languageId`';
            if ($bInstall) {
                $oDb->addConstraint('catalog_product_color_translations', 'languageId', 'languages', 'languageId', 'RESTRICT', 'CASCADE');
            }
        }
    }
}

// check catalog_product_property_type_group_translations constraints
if ($oDb->tableExists('catalog_product_property_type_group_translations')) {
    if ($oDb->tableExists('catalog_product_property_type_groups')) {
        // check catalog_product_property_type_groups constraint
        if (!$oDb->constraintExists('catalog_product_property_type_group_translations', 'catalogProductPropertyTypeGroupId', 'catalog_product_property_type_groups', 'catalogProductPropertyTypeGroupId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `catalog_product_property_type_group_translations`.`catalogProductPropertyTypeGroupId` => `catalog_product_property_type_groups`.`catalogProductPropertyTypeGroupId`';
            if ($bInstall) {
                $oDb->addConstraint('catalog_product_property_type_group_translations', 'catalogProductPropertyTypeGroupId', 'catalog_product_property_type_groups', 'catalogProductPropertyTypeGroupId', 'CASCADE', 'CASCADE');
            }
        }
    }

    if ($oDb->tableExists('languages')) {
        // check languages constraint
        if (!$oDb->constraintExists('catalog_product_property_type_group_translations', 'languageId', 'languages', 'languageId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `catalog_product_property_type_group_translations`.`languageId` => `languages`.`languageId`';
            if ($bInstall) {
                $oDb->addConstraint('catalog_product_property_type_group_translations', 'languageId', 'languages', 'languageId', 'RESTRICT', 'CASCADE');
            }
        }
    }
}

// check catalog_product_property_type_possible_value_translations constraints
if ($oDb->tableExists('catalog_product_property_type_possible_value_translations')) {
    if ($oDb->tableExists('catalog_product_property_type_possible_values')) {
        // check catalog_product_property_type_possible_values constraint
        if (!$oDb->constraintExists('catalog_product_property_type_possible_value_translations', 'catalogProductPropertyTypePossibleValueId', 'catalog_product_property_type_possible_values', 'catalogProductPropertyTypePossibleValueId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `catalog_product_property_type_possible_value_translations`.`catalogProductPropertyTypePossibleValueId` => `catalog_product_property_type_possible_values`.`catalogProductPropertyTypePossibleValueId`';
            if ($bInstall) {
                $oDb->addConstraint(
                    'catalog_product_property_type_possible_value_translations',
                    'catalogProductPropertyTypePossibleValueId',
                    'catalog_product_property_type_possible_values',
                    'catalogProductPropertyTypePossibleValueId',
                    'CASCADE',
                    'CASCADE'
                );
            }
        }
    }

    if ($oDb->tableExists('languages')) {
        // check languages constraint
        if (!$oDb->constraintExists('catalog_product_property_type_possible_value_translations', 'languageId', 'languages', 'languageId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `catalog_product_property_type_possible_value_translations`.`languageId` => `languages`.`languageId`';
            if ($bInstall) {
                $oDb->addConstraint('catalog_product_property_type_possible_value_translations', 'languageId', 'languages', 'languageId', 'RESTRICT', 'CASCADE');
            }
        }
    }
}

// check catalog_product_property_type_translations constraints
if ($oDb->tableExists('catalog_product_property_type_translations')) {
    if ($oDb->tableExists('catalog_product_property_types')) {
        // check catalog_product_property_types constraint
        if (!$oDb->constraintExists('catalog_product_property_type_translations', 'catalogProductPropertyTypeId', 'catalog_product_property_types', 'catalogProductPropertyTypeId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `catalog_product_property_type_translations`.`catalogProductPropertyTypeId` => `catalog_product_property_types`.`catalogProductPropertyTypeId`';
            if ($bInstall) {
                $oDb->addConstraint('catalog_product_property_type_translations', 'catalogProductPropertyTypeId', 'catalog_product_property_types', 'catalogProductPropertyTypeId', 'CASCADE', 'CASCADE');
            }
        }
    }

    if ($oDb->tableExists('languages')) {
        // check languages constraint
        if (!$oDb->constraintExists('catalog_product_property_type_translations', 'languageId', 'languages', 'languageId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `catalog_product_property_type_translations`.`languageId` => `languages`.`languageId`';
            if ($bInstall) {
                $oDb->addConstraint('catalog_product_property_type_translations', 'languageId', 'languages', 'languageId', 'RESTRICT', 'CASCADE');
            }
        }
    }
}

// check catalog_product_size_translations constraints
if ($oDb->tableExists('catalog_product_size_translations')) {
    if ($oDb->tableExists('catalog_product_sizes')) {
        // check catalog_product_sizes constraint
        if (!$oDb->constraintExists('catalog_product_size_translations', 'catalogProductSizeId', 'catalog_product_sizes', 'catalogProductSizeId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `catalog_product_size_translations`.`catalogProductSizeId` => `catalog_product_sizes`.`catalogProductSizeId`';
            if ($bInstall) {
                $oDb->addConstraint('catalog_product_size_translations', 'catalogProductSizeId', 'catalog_product_sizes', 'catalogProductSizeId', 'CASCADE', 'CASCADE');
            }
        }
    }

    if ($oDb->tableExists('languages')) {
        // check languages constraint
        if (!$oDb->constraintExists('catalog_product_size_translations', 'languageId', 'languages', 'languageId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `catalog_product_size_translations`.`languageId` => `languages`.`languageId`';
            if ($bInstall) {
                $oDb->addConstraint('catalog_product_size_translations', 'languageId', 'languages', 'languageId', 'RESTRICT', 'CASCADE');
            }
        }
    }
}

// check catalog_product_translations constraints
if ($oDb->tableExists('catalog_product_translations')) {
    if ($oDb->tableExists('catalog_products')) {
        // check catalog_products constraint
        if (!$oDb->constraintExists('catalog_product_translations', 'catalogProductId', 'catalog_products', 'catalogProductId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `catalog_product_translations`.`catalogProductId` => `catalog_products`.`catalogProductId`';
            if ($bInstall) {
                $oDb->addConstraint('catalog_product_translations', 'catalogProductId', 'catalog_products', 'catalogProductId', 'CASCADE', 'CASCADE');
            }
        }
    }

    if ($oDb->tableExists('languages')) {
        // check languages constraint
        if (!$oDb->constraintExists('catalog_product_translations', 'languageId', 'languages', 'languageId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `catalog_product_translations`.`languageId` => `languages`.`languageId`';
            if ($bInstall) {
                $oDb->addConstraint('catalog_product_translations', 'languageId', 'languages', 'languageId', 'RESTRICT', 'CASCADE');
            }
        }
    }
}

// check catalog_product_type_translations constraints
if ($oDb->tableExists('catalog_product_type_translations')) {
    if ($oDb->tableExists('catalog_product_types')) {
        // check catalog_product_types constraint
        if (!$oDb->constraintExists('catalog_product_type_translations', 'catalogProductTypeId', 'catalog_product_types', 'catalogProductTypeId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `catalog_product_type_translations`.`catalogProductTypeId` => `catalog_product_types`.`catalogProductTypeId`';
            if ($bInstall) {
                $oDb->addConstraint('catalog_product_type_translations', 'catalogProductTypeId', 'catalog_product_types', 'catalogProductTypeId', 'CASCADE', 'CASCADE');
            }
        }
    }

    if ($oDb->tableExists('languages')) {
        // check languages constraint
        if (!$oDb->constraintExists('catalog_product_type_translations', 'languageId', 'languages', 'languageId')) {
            $aLogs[$sModuleName]['errors'][] = 'Missing fk constraint `catalog_product_type_translations`.`languageId` => `languages`.`languageId`';
            if ($bInstall) {
                $oDb->addConstraint('catalog_product_type_translations', 'languageId', 'languages', 'languageId', 'RESTRICT', 'CASCADE');
            }
        }
    }
}

// check color (no-color)
if (moduleExists('catalog') && $oDb->tableExists('catalog_product_colors')) {
    if (!($oColor = CatalogProductColorManager::getProductColorById(CatalogProductColor::colorId_nocolor))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing default color `no color`';
        if ($bInstall) {
            $sQuery = ' INSERT INTO
                        `catalog_product_colors`
                    (
                        `catalogProductColorId`,
                        `order`,
                        `created`,
                        `modified`
                    ) VALUES (
                        ' . db_int(-1) . ',
                        ' . db_int(9999) . ',
                        NOW(),
                        NULL
                    );';
            $oDb->query($sQuery, QRY_NORESULT);

            $sQuery = ' INSERT INTO
                        `catalog_product_color_translations`
                    (
                        `catalogProductColorTranslationId`,
                        `catalogProductColorId`,
                        `languageId`,
                        `name`
                    ) VALUES (
                        NULL,
                        ' . db_int(-1) . ',
                        ' . db_int(DEFAULT_LANGUAGE_ID) . ',
                        ' . db_str('Geen kleur') . '
                    );';
            $oDb->query($sQuery, QRY_NORESULT);
        }
    }
}

// check size (no-size)
if (moduleExists('catalog') && $oDb->tableExists('catalog_product_sizes')) {
    if (!($oColor = CatalogProductSizeManager::getProductSizeById(CatalogProductSize::sizeId_nosize))) {
        $aLogs[$sModuleName]['errors'][] = 'Missing default size `no size`';
        if ($bInstall) {
            $sQuery = ' INSERT INTO
                        `catalog_product_sizes`
                    (
                        `catalogProductSizeId`,
                        `multilingual`,
                        `order`,
                        `created`,
                        `modified`
                    ) VALUES (
                        ' . db_int(-1) . ',
                        ' . db_int(0) . ',
                        ' . db_int(9999) . ',
                        NOW(),
                        NULL
                    );';
            $oDb->query($sQuery, QRY_NORESULT);

            $sQuery = ' INSERT INTO
                        `catalog_product_size_translations`
                    (
                        `catalogProductSizeTranslationId`,
                        `catalogProductSizeId`,
                        `languageId`,
                        `name`
                    ) VALUES (
                        NULL,
                        ' . db_int(-1) . ',
                        ' . db_int(DEFAULT_LANGUAGE_ID) . ',
                        ' . db_str('Geen maat') . '
                    );';
            $oDb->query($sQuery, QRY_NORESULT);
        }
    }
}
