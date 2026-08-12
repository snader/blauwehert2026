<?php

if (Request::param('ID') == 'session') {
        
    global $oPageLayout;

    $oPageLayout               = new PageLayout();
    $oPageLayout->sWindowTitle = sysTranslations::get('global_home');
    $oPageLayout->sModuleName  = sysTranslations::get('global_home');

    $oPageLayout->sViewPath = getAdminView('home/home');

    include_once getAdminView('session');
    exit;
}

# check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

// no rights for home? send to first item in menu
if (!$oCurrentUser->hasRightsForModule('')) {
    $aMenuModules = $oCurrentUser->getUserAccessGroup()
        ->getModules();
    if (!empty($aMenuModules[0])) {
        http_redirect(ADMIN_FOLDER . '/' . $aMenuModules[0]->name);
    } else {
        return Router::httpError(404);
    }
}


global $oPageLayout;

$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = sysTranslations::get('global_home');
$oPageLayout->sModuleName  = sysTranslations::get('global_home');

$oPageLayout->sViewPath = getAdminView('home/home');

include_once getAdminView('layout');
?>