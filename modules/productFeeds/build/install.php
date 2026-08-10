<?php

// check dependencies
$aDependencyModules = [
    'core',
    'catalog',
    'orders',
];

$aNeededAdminControllerRoutes = [];
$aNeededClassRoutes           = [];
$aNeededModulesForMenu        = [];

$aNeededSiteControllerRoutes = [
    'product-feed' => [
        'module'     => 'productFeeds',
        'controller' => 'productFeed',
    ],
];

// site translations (front end)
$aNeededSiteTranslations = [
    'nl' => [
        ['label' => 'site_all_products_for', 'text' => 'Alle producten voor', 'editable' => 1],
    ],
];
