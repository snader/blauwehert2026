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
$oPageLayout->sWindowTitle = "Reviews";
$oPageLayout->sModuleName  = "Reviews";

// get status update from session
$oPageLayout->sStatusUpdate = Session::get("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once
// re-order the Review objects
if (Request::getVar('ajax') == 'orderReviews') {
    $oResObj          = new stdClass();
    $oResObj->success = false;

    // check for the required POST values
    if (empty($_POST['positions'])) {
        die(json_encode($oResObj));
    }

    // define the id's of the objects in the new ordered positions
    $sPositions = Request::postVar('positions'); // get object ids comma seperated
    $aReviewIds = explode(",", $sPositions); // explode ids into array

    foreach ($aReviewIds as $i => $iReviewId) {
        // get each object
        $oReview = ReviewManager::getReviewById($iReviewId);

        if (empty($oReview)) {
            continue;
        }

        // change the order
        $oReview->order = $i;

        // update the order of the object
        ReviewManager::updateReviewOrder($oReview);
    }

    $oResObj->success = true;

    die(json_encode($oResObj));
}

// handle add/edit
if (Request::param('ID') == 'bewerken' || Request::param('ID') == 'toevoegen') {
    // set crop referrer for this module
    $_SESSION['cropReferrer'] = ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . Request::param('OtherID');

    if (Request::param('ID') == 'bewerken' && is_numeric(Request::param('OtherID'))) {
        $oReview = ReviewManager::getReviewById(Request::param('OtherID'));
        if (empty($oReview)) {
            Router::redirect(ADMIN_FOLDER . "/");
        }
    } else {
        $oReview           = new Review();
        $oReview->localeId = AdminLocales::locale();
        $oReview->online   = 1;
    }

    // action = save
    if (Request::postVar("action") == 'save' && CSRFSynchronizerToken::validate()) {
        // load data in object
        $oReview->_load($_POST);

        $oReview->link   = Request::postVar('link') ? addHttp(Request::postVar('link')) : null;
        $oReview->review = convertAbsToRelLinks(Request::postVar('review'));

        // if object is valid, save
        if ($oReview->isValid()) {
            ReviewManager::saveReview($oReview); //save object
            $_SESSION['statusUpdate'] = sysTranslations::get('review_saved'); //save status update into session
            Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . $oReview->reviewId);
        } else {
            Debug::logError("", "Review module php validate error", __FILE__, __LINE__, "Tried to save Review with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $_SESSION['statusUpdate'] = sysTranslations::get('review_not_saved');
        }
    }

    # action saveImage
    if (Request::postVar("action") == 'saveImage' && CSRFSynchronizerToken::validate()) {


        $bCheckMime = true;

        // for upload from MFUpload
        if (Request::postVar("MFUpload")) {
            $oResObj          = new stdClass();
            $oResObj->success = false;
            $bCheckMime       = true;
        }

        $aImageSettings = TemplateSettings::get('reviews', 'images');

        // upload file or return error
        $oUpload = new Upload(
            $_FILES['image'],
            $aImageSettings['imagesPath'] . "/" . $aImageSettings['originalReference'] . "/",
            (Request::postVar('title') != '' ? Request::postVar('title') : null),
            ['jpg', 'png', 'gif', 'jpeg'],
            $bCheckMime
        );

        // save image to database on success
        if ($oUpload->bSuccess === true) {
            $sTitle = Request::postVar('title', '');
            $oImage = ImageManager::handleImageUpload($oUpload, $aImageSettings, $sTitle, $aErrorMsgs);

            if ($oImage) {
                # save review item image relation
                ReviewManager::saveReviewImageRelation($oReview->reviewId, $oImage->imageId);
            }

            // for MFUpload
            if (Request::postVar("MFUpload")) {
                $oResObj->success = true;
                $oImageFileThumb  = $oImage->getImageFileByReference('cms_thumb');
                // add some extra values
                $oImageFileThumb->isOnlineChangeable  = $oImage->isOnlineChangeable();
                $oImageFileThumb->isCropable          = $oImage->isCropable();
                $oImageFileThumb->isEditable          = $oImage->isEditable();
                $oImageFileThumb->isDeletable         = $oImage->isDeletable();
                $oImageFileThumb->hasNeededImageFiles = $oImage->hasImageFiles(TemplateSettings::get('reviews', 'images/neededImageFiles'));

                $oResObj->imageFile = $oImageFileThumb; // for adding image to list (last imageFile object)
                print json_encode($oResObj);
                die;
            }

            // back to edit
            Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . $oReview->reviewId);
        } else {

            // for MFUpload
            if (Request::postVar("MFUpload")) {
                $oResObj->errorMsg = $oUpload->getErrorMessage();
                print json_encode($oResObj);
                die;
            }

            $_SESSION['statusUpdate'] = sysTranslations::get('global_image_not_uploaded') . $oUpload->getErrorMessage(); //error uploading file
            Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . $oReview->reviewId);
        }
    }

    # set settings for image management
    $oImageManagerHTML                                       = new ImageManagerHTML();
    $oImageManagerHTML->bMultipleFileUpload                  = false;
    $oImageManagerHTML->aMultipleFileUploadAllowedExtensions = ['png', 'jpg', 'gif', 'jpeg'];
    $oImageManagerHTML->aImages                              = $oReview->getImages('all');
    $oImageManagerHTML->cropLink                             = ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/crop-image';
    $oImageManagerHTML->sUploadUrl                           = ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . $oReview->reviewId;
    $oImageManagerHTML->aNeededImageFileReferences           = TemplateSettings::get('reviews', 'images/neededImageFiles');
    $oImageManagerHTML->bShowCropAfterUploadOption           = false;
    $oImageManagerHTML->iMaxImages                           = 1;

    $oPageLayout->sViewPath = getAdminView('review_form', 'reviews');
} // set object online/offline
elseif (Request::param('ID') == 'ajax-setOnline') {
    if(!CSRFSynchronizerToken::validate()){
        die(json_encode(['status'=>false]));
    }
    $bOnline   = Request::getVar("online"); //no value, set offline by default
    $bAjax     = boolval(Request::getVar("ajax")); //controller requested by ajax
    $iReviewId = Request::param('OtherID');
    $oResObj   = new stdClass(); //standard class for json feedback
    // update online for object
    if (is_numeric($iReviewId)) {
        $oResObj->success  = ReviewManager::updateOnlineByReviewId($bOnline, $iReviewId);
        $oResObj->reviewId = $iReviewId;
        $oResObj->online   = $bOnline;
    }

    // redirect to overview page if this isn't AJAX
    if (!$bAjax) {
        Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment() . '');
    }

    die(json_encode($oResObj));
} elseif (Request::param('ID') == 'volgorde-wijzigen') {
    if (Request::postVar('action') == 'saveOrder' && CSRFSynchronizerToken::validate()) {
        if (Request::postVar('order')) {
            $aReviewIds = explode('|', Request::postVar('order'));
            $iC         = 1;
            foreach ($aReviewIds AS $iReviewId) {
                $oReview        = ReviewManager::getReviewById($iReviewId);
                $oReview->order = $iC;
                if ($oReview->isValid()) {
                    ReviewManager::saveReview($oReview);
                }
                $iC++;
            }
        }
        $_SESSION['statusUpdate'] = sysTranslations::get('global_sequence_saved'); //save status update into session

        Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment());
    }

    // get all items for order changing
    $aAllReviews            = ReviewManager::getReviewsByFilter(['showAll' => true]);
    $oPageLayout->sViewPath = getAdminView('reviews_change_order', 'reviews');
} // delete object
elseif (Request::param('ID') == 'verwijderen' && is_numeric(Request::param('OtherID'))) {
    if(CSRFSynchronizerToken::validate()) {
        if (is_numeric(Request::param('OtherID'))) {
            $oReview = ReviewManager::getReviewById(Request::param('OtherID'));
        }

        if (!empty($oReview) && ReviewManager::deleteReview($oReview)) {
            $_SESSION['statusUpdate'] = sysTranslations::get('review_deleted'); //save status update into session
        } else {
            $_SESSION['statusUpdate'] = sysTranslations::get('review_not_deleted'); //save status update into session
        }
    }
    Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment());
} elseif (Request::param('ID') == 'crop-image' && is_numeric(Request::getVar("imageId"))) {
    $oImage = ImageManager::getImageById(Request::getVar("imageId"));

    if (!$oImage) {
        $_SESSION['statusUpdate'] = sysTranslations::get('global_image_not_available'); //error getting image
        Router::redirect(Session::get('cropReferrer'));
    }

    $aImageSettings = TemplateSettings::get('reviews', 'images');
    $sReferrer      = Session::get('cropReferrer');
    $sReferrerText  = sysTranslations::get('review_edition');
    $aCropSettings  = ImageManager::handleImageCropSettings($oImage, $aImageSettings, $sReferrer, $sReferrerText);

    // add setting to session in an array
    $_SESSION['aCropSettings'] = $aCropSettings;

    Router::redirect(ADMIN_FOLDER . '/crop');
} // display overview
else {
    $aAllReviews            = ReviewManager::getReviewsByFilter(['showAll' => true]);
    $oPageLayout->sViewPath = getAdminView('reviews_overview', 'reviews');
}

// include template
include_once getAdminView('layout');
?>