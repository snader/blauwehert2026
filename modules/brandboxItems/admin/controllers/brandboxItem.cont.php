<?php

// check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

// reset crop settings
unset($_SESSION['aCropSettings']);

global $oPageLayout;

// set page layout properties
$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = "Brandbox";
$oPageLayout->sModuleName  = "Brandbox";

// get status update from session
$oPageLayout->sStatusUpdate = http_session("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once
// re-order the BrandboxItem objects
if (http_get('ajax') == 'orderBrandboxItems') {
    $oResObj          = new stdClass();
    $oResObj->success = false;

    // check for the required POST values
    if (empty($_POST['positions'])) {
        die(json_encode($oResObj));
    }

    // define the id's of the objects in the new ordered positions
    $sPositions       = http_post('positions'); // get object ids comma seperated
    $aBrandboxItemIds = explode(",", $sPositions); // explode ids into array

    foreach ($aBrandboxItemIds as $i => $iBrandboxItemId) {
        // get each object
        $oBrandboxItem = BrandboxItemManager::getBrandboxItemById($iBrandboxItemId);

        if (empty($oBrandboxItem)) {
            continue;
        }

        // change the order
        $oBrandboxItem->order = $i;

        // update the order of the object
        BrandboxItemManager::updateBrandboxItemOrder($oBrandboxItem);
    }

    $oResObj->success = true;

    die(json_encode($oResObj));
}

// handle add/edit
if (http_get("param1") == 'bewerken' || http_get("param1") == 'toevoegen') {
    // set crop referrer for this module
    $_SESSION['cropReferrer'] = ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . http_get("param2");

    if (http_get("param1") == 'bewerken' && is_numeric(http_get("param2"))) {
        $oBrandboxItem = BrandboxItemManager::getBrandboxItemById(http_get("param2"));
        if (empty($oBrandboxItem)) {
            http_redirect(ADMIN_FOLDER . "/");
        }
    } else {
        $oBrandboxItem             = new BrandboxItem();
        $oBrandboxItem->languageId = AdminLocales::language();
    }

    // action = save
    if (http_post("action") == 'save' && CSRFSynchronizerToken::validate()) {
        // load data in object
        $oBrandboxItem->_load($_POST);

        $oBrandboxItem->link       = http_post('link') ? addHttp(http_post('link')) : null;
        $oBrandboxItem->pageId     = http_post('pageId');
        $oBrandboxItem->newsItemId = http_post('newsItemId');

        // if object is valid, save
        if ($oBrandboxItem->isValid()) {
            BrandboxItemManager::saveBrandboxItem($oBrandboxItem); //save object
            $_SESSION['statusUpdate'] = sysTranslations::get('brandbox_item_saved'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oBrandboxItem->brandboxItemId);
        } else {
            Debug::logError("", "BrandboxItem module php validate error", __FILE__, __LINE__, "Tried to save BrandboxItem with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $_SESSION['statusUpdate'] = sysTranslations::get('brandbox_item_not_saved');
        }
    }

    // action saveImage
    if (http_post("action") == 'saveImage' && CSRFSynchronizerToken::validate()) {

        $bCheckMime = true;

        $aImageSettings = TemplateSettings::get('brandboxItems', 'images');

        // upload file or return error
        $oUpload = new Upload($_FILES['image'], $aImageSettings['imagesPath'] . "/" . $aImageSettings['originalReference'] . "/", (http_post('title') != '' ? http_post('title') : null), ['jpg', 'png', 'gif', 'jpeg'], $bCheckMime);

        // save image to database on success
        if ($oUpload->bSuccess === true) {
            $sTitle = http_post('title', '');
            $oImage = ImageManager::handleImageUpload($oUpload, $aImageSettings, $sTitle, $aErrorMsgs);

            // delete the old Image
            $oOldImage = $oBrandboxItem->getImage();
            if (!empty($oOldImage)) {
                ImageManager::deleteImage($oOldImage);
            }

            if ($oImage) {
                // save Image
                ImageManager::saveImage($oImage);
            }

            // save object's Image relation
            BrandboxItemManager::saveBrandboxItemImageRelations($oBrandboxItem->brandboxItemId, $oImage->imageId);

            // back to edit
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/crop-image/?imageId=' . $oImage->imageId);
        } else {
            $_SESSION['statusUpdate'] = sysTranslations::get('global_image_not_uploaded') . ' ' . $oUpload->getErrorMessage(); //error uploading file
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oBrandboxItem->brandboxItemId);
        }
    }

    if (Request::postVar('action') == 'saveVideoLink' && CSRFSynchronizerToken::validate()) {
        // if object is valid, save
        if ($oVideoLink = VideoLinkManager::saveVideoLink(Request::postVars())) {
            BrandboxItemManager::saveBrandboxItemMediaRelations($oBrandboxItem->brandboxItemId, $oVideoLink->mediaId);
            $_SESSION['statusUpdate'] = sysTranslations::get('global_video_saved'); //save status update into session

            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oBrandboxItem->brandboxItemId);
        } else {
            Debug::logError("", "BrandboxItem module php validate error", __FILE__, __LINE__, "Tried to save VideoLink with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $oPageLayout->sStatusUpdate = sysTranslations::get('global_video_not_saved');
        }
        _d($oVideoLink);
        exit;
    }

    if (moduleExists('pages')) {
        $oPage = PageManager::getPageByUrlPath('/');
    }
    // set settings for image management
    $oImageManagerHTML                             = new ImageManagerHTML();
    $oImageManagerHTML->aImages                    = ($oBrandboxItem->getImage() ? [$oBrandboxItem->getImage()] : []);
    $oImageManagerHTML->cropLink                   = ADMIN_FOLDER . '/' . http_get('controller') . '/crop-image';
    $oImageManagerHTML->sUploadUrl                 = ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oBrandboxItem->brandboxItemId;
    $oImageManagerHTML->aNeededImageFileReferences = TemplateSettings::get('brandboxItems', 'images/neededImageFiles');
    $oImageManagerHTML->bShowCropAfterUploadOption = false;
    $oImageManagerHTML->onlineChangeable           = false;
    $oImageManagerHTML->sExtraUploadedLine         = sysTranslations::get('global_images_displayed') . (!empty($oPage) ? ' <a href="' . $oPage->getBaseUrlPath() . '" target="_blank">' . $oPage->getShortTitle() . '</a>' : '');

    // Get the media early
    $oBrandboxItemMedia = $oBrandboxItem->getVideoLinks();

    // set video manager
    $oVideoLinkManagerHTML              = new VideoLinkManagerHTML();
    $oVideoLinkManagerHTML->iMaxVideoLinks = 1;
    $oVideoLinkManagerHTML->aVideoLinks = $oBrandboxItemMedia ? [$oBrandboxItemMedia] : [];
    $oVideoLinkManagerHTML->sUploadUrl  = ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oBrandboxItem->brandboxItemId;

    $oPageLayout->sViewPath = getAdminView('brandboxItem_form', 'brandboxItems');
} // set object online/offline
elseif (http_get("param1") == 'ajax-setOnline') {
    if(!CSRFSynchronizerToken::validate()){
        die(json_encode(['status'=>false]));
    }
    $bOnline         = http_get("online", 0); //no value, set offline by default
    $bAjax           = http_get("ajax", false); //controller requested by ajax
    $iBrandboxItemId = http_get("param2");
    $oResObj         = new stdClass(); //standard class for json feedback
    // update online for object
    if (is_numeric($iBrandboxItemId)) {
        $oResObj->success        = BrandboxItemManager::updateOnlineByBrandboxItemId($bOnline, $iBrandboxItemId);
        $oResObj->brandboxItemId = $iBrandboxItemId;
        $oResObj->online         = $bOnline;
    }

    // redirect to overview page if this isn't AJAX
    if (!$bAjax) {
        http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '');
    }

    die(json_encode($oResObj));
} elseif (http_get('param1') == 'volgorde-wijzigen') {

    if (http_post('action') == 'saveOrder' && CSRFSynchronizerToken::validate()) {
        if (http_post('order')) {
            $aBrandboxItemIds = explode('|', http_post('order'));
            $iC               = 1;
            foreach ($aBrandboxItemIds AS $iBrandboxItemId) {
                $oBrandboxItem        = BrandboxItemManager::getBrandboxItemById($iBrandboxItemId);
                $oBrandboxItem->order = $iC;
                if ($oBrandboxItem->isValid()) {
                    BrandboxItemManager::saveBrandboxItem($oBrandboxItem);
                }
                $iC++;
            }
        }
        $_SESSION['statusUpdate'] = sysTranslations::get('global_sequence_saved'); //save status update into session

        http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
    }

    // get all items for order changing
    $aAllBrandboxItems      = BrandboxItemManager::getBrandboxItemsByFilter(['showAll' => true, 'languageId' => AdminLocales::language()]);
    $oPageLayout->sViewPath = getAdminView('brandboxItems_change_order', 'brandboxItems');
} // delete object
elseif (http_get("param1") == 'verwijderen' && is_numeric(http_get("param2")) && CSRFSynchronizerToken::validate()) {
    if (is_numeric(http_get("param2"))) {
        $oBrandboxItem = BrandboxItemManager::getBrandboxItemById(http_get("param2"));
    }

    if (!empty($oBrandboxItem) && BrandboxItemManager::deleteBrandboxItem($oBrandboxItem)) {
        $_SESSION['statusUpdate'] = sysTranslations::get('brandbox_item_deleted'); //save status update into session
    } else {
        $_SESSION['statusUpdate'] = sysTranslations::get('brandbox_item_not_deleted'); //save status update into session
    }
    http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
} // crop Image
elseif (http_get("param1") == 'crop-image' && is_numeric(http_get("imageId"))) {
    $oImage = ImageManager::getImageById(http_get("imageId"));

    if (!$oImage) {
        $_SESSION['statusUpdate'] = sysTranslations::get('global_image_not_available'); //error getting image
        http_redirect(http_session('cropReferrer'));
    }

    $aImageSettings = TemplateSettings::get('brandboxItems', 'images');
    $sReferrer      = http_session('cropReferrer');
    $sReferrerText  = sysTranslations::get('brandbox_item_edition');
    $aCropSettings  = ImageManager::handleImageCropSettings($oImage, $aImageSettings, $sReferrer, $sReferrerText);

    // add setting to session in an array
    $_SESSION['aCropSettings'] = $aCropSettings;

    http_redirect(ADMIN_FOLDER . '/crop');
} // display overview
else {
    $aAllBrandboxItems      = BrandboxItemManager::getBrandboxItemsByFilter(['showAll' => true, 'languageId' => AdminLocales::language()]);
    $oPageLayout->sViewPath = getAdminView('brandboxItems_overview', 'brandboxItems');
}

// include template
include_once getAdminView('layout');
?>