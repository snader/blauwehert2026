<?php

// check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

// reset crop settings
unset($_SESSION['aCropSettings']);

// set page layout properties
$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = "Usps";
$oPageLayout->sModuleName  = "Usps";

// get status update from session
$oPageLayout->sStatusUpdate = Session::get("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once
// re-order the Usp objects
if (Request::getVar('ajax') == 'orderUsps' && CSRFSynchronizerToken::validate()) {
    $oResObj          = new stdClass();
    $oResObj->success = false;

    // check for the required POST values
    if (empty($_POST['positions'])) {
        die(json_encode($oResObj));
    }

    // define the id's of the objects in the new ordered positions
    $sPositions   = Request::postVar('positions'); // get object ids comma seperated
    $aUspsIds = explode(",", $sPositions); // explode ids into array

    foreach ($aUspsIds as $i => $iUspId) {
        // get each object
        $oUsp = UspManager::getUspById($iUspId);

        if (empty($oUsp)) {
            continue;
        }

        // change the order
        $oUsp->order = $i;

        // update the order of the object
        UspManager::updateUspOrder($oUsp);
    }

    $oResObj->success = true;

    die(json_encode($oResObj));
}

// handle add/edit
if (Request::param('ID') == 'bewerken' || Request::param('ID') == 'toevoegen') {
    // set crop referrer for this module
    $_SESSION['cropReferrer'] = ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . Request::param('OtherID');

    if (Request::param('ID') == 'bewerken' && is_numeric(Request::param('OtherID'))) {
        $oUsp = UspManager::getUspById(Request::param('OtherID'));
        if (empty($oUsp)) {
            Router::redirect(ADMIN_FOLDER . "/");
        }
    } else {
        $oUsp             = new Usp();
        $oUsp->languageId = AdminLocales::language();
    }

    // action = save
    if (Request::postVar("action") == 'save' && CSRFSynchronizerToken::validate()) {
        // load data in object
        $oUsp->_load($_POST);
        $oUsp->link = Request::postVar('link') ? addHttp(Request::postVar('link')) : null;
        $oUsp->textLine = Request::postVar('textLine') ? Request::postVar('textLine') : null;

        // if object is valid, save
        if ($oUsp->isValid()) {
            UspManager::saveUsp($oUsp); //save object
            $_SESSION['statusUpdate'] = sysTranslations::get('usp_saved'); //save status update into session
            Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . $oUsp->uspId);
        } else {
            Debug::logError("", "Usp module php validate error", __FILE__, __LINE__, "Tried to save Usp with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $_SESSION['statusUpdate'] = sysTranslations::get('usp_not_saved');
        }
    }

    # action saveFile
    if (Request::postVar("action") == 'saveFile' && CSRFSynchronizerToken::validate()) {
        $bCheckMime = true;

        // for upload from MFUpload
        if (Request::postVar("MFUpload")) {
            $oResObj          = new stdClass();
            $oResObj->success = false;
            $bCheckMime       = true;
        }

        // upload file or return error
        $oUpload = new Upload($_FILES['file'], Usp::FILES_PATH . '/', (Request::postVar('file_title') != '' ? Request::postVar('file_title') : null), ['svg'], $bCheckMime);

        // save file to database on success
        if ($oUpload->bSuccess === true) {
            // make File object and save
            $oFile           = new File();
            $oFile->name     = $oUpload->sNewFileBaseName;
            $oFile->mimeType = $oUpload->sMimeType;
            $oFile->size     = $oUpload->iSize;
            $oFile->link     = $oUpload->sNewFilePath;
            $oFile->title    = Request::postVar('title') == '' ? $oUpload->sFileName : Request::postVar('title');

            // save file
            FileManager::saveFile($oFile);

            // save step file relation
            UspManager::saveFileRelation($oUsp->uspId, $oFile->mediaId);

            // for MFUpload
            if (Request::postVar("MFUpload")) {
                $oResObj->success = true;
                // add some extra values
                $oFile->isOnlineChangeable = $oFile->isOnlineChangeable();
                $oFile->isEditable         = $oFile->isEditable();
                $oFile->isDeletable        = $oFile->isDeletable();
                $oFile->extension          = $oFile->getExtension();

                $oResObj->file = $oFile; // for adding file to list
                print json_encode($oResObj);
                die;
            }

            // back to edit
            Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . $oUsp->uspId);
        } else {
            // for MFUpload
            if (Request::postVar("MFUpload")) {
                $oResObj->errorMsg = $oUpload->getErrorMessage();
                print json_encode($oResObj);
                die;
            }

            $_SESSION['statusUpdate'] = sysTranslations::get('global_file_not_uploaded') . $oUpload->getErrorMessage(); //error uploading file
            Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . $oUsp->uspId);
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

        $aImageSettings = TemplateSettings::get('usps', 'images');

        // upload file or return error
        $oUpload = new Upload($_FILES['image'], $aImageSettings['imagesPath'] . "/" . $aImageSettings['originalReference'] . "/", (Request::postVar('title') != '' ? Request::postVar('title') : null), ['jpg', 'png', 'gif', 'jpeg'], $bCheckMime);

        // save image to database on success
        if ($oUpload->bSuccess === true) {
            $sTitle = Request::postVar('title', '');
            $oImage = ImageManager::handleImageUpload($oUpload, $aImageSettings, $sTitle, $aErrorMsgs);
            if ($oImage) {
                // save usp image relation
                UspManager::saveImageRelation($oUsp->uspId, $oImage->imageId);
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
                $oImageFileThumb->hasNeededImageFiles = $oImage->hasImageFiles(TemplateSettings::get('usps', 'images/neededImageFiles'));

                $oResObj->imageFile = $oImageFileThumb; // for adding image to list (last imageFile object)
                print json_encode($oResObj);
                die;
            }

            // back to edit
            Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . $oUsp->uspId);
        } else {
            // for MFUpload
            if (Request::postVar("MFUpload")) {
                $oResObj->errorMsg = $oUpload->getErrorMessage();
                print json_encode($oResObj);
                die;
            }

            $_SESSION['statusUpdate'] = sysTranslations::get('global_image_not_uploaded') . $oUpload->getErrorMessage(); //error uploading file
            Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . $oUsp->uspId);
        }
    }

    # set settings for file management
    $oFileManagerHTML                      = new FileManagerHTML();
    $oFileManagerHTML->aFiles              = ($oUsp->getFile() ? [$oUsp->getFile()] : []);
    $oFileManagerHTML->sUploadUrl          = ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . $oUsp->uspId;
    $oFileManagerHTML->bMultipleFileUpload = true;
    $oFileManagerHTML->bTitleRequired      = false;
    $oFileManagerHTML->iMaxFiles           = 1;

    # set settings for image management
    $oImageManagerHTML                                       = new ImageManagerHTML();
    $oImageManagerHTML->bMultipleFileUpload                  = true;
    $oImageManagerHTML->aMultipleFileUploadAllowedExtensions = ['png', 'jpg', 'gif', 'jpeg'];
    $oImageManagerHTML->iMaxImages                           = 1;
    $oImageManagerHTML->aImages                              = $oUsp->getImage() ? [$oUsp->getImage()] : [];
    $oImageManagerHTML->cropLink                             = ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/crop-image';
    $oImageManagerHTML->sUploadUrl                           = ADMIN_FOLDER . '/' . Request::getControllerSegment() . '/bewerken/' . $oUsp->uspId;
    $oImageManagerHTML->aNeededImageFileReferences           = TemplateSettings::get('usps', 'images/neededImageFiles');
    $oImageManagerHTML->bShowCropAfterUploadOption           = false;
    $oImageManagerHTML->iContainerIDAddition                 = '1';
    $oImageManagerHTML->sExtraUploadLine                     = sysTranslations::get('global_image_size_960_700');

    $oImageManagerHTML->sExtraUploadedLine = '';

    $oPageLayout->sViewPath = getAdminView('usp_form', 'usps');
} // set object online/offline
elseif (Request::param('ID') == 'ajax-setOnline' && CSRFSynchronizerToken::validate()) {
    $bOnline    = Request::getVar("online"); //no value, set offline by default
    $bAjax      = boolval(Request::getVar("ajax")); //controller requested by ajax
    $iUspId = Request::param('OtherID');
    $oResObj    = new stdClass(); //standard class for json feedback
    // update online for object
    if (is_numeric($iUspId)) {
        $oResObj->success   = UspManager::updateOnlineByUspId($bOnline, $iUspId);
        $oResObj->uspId = $iUspId;
        $oResObj->online    = $bOnline;
    }

    // redirect to overview page if this isn't AJAX
    if (!$bAjax) {
        Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment() . '');
    }

    die(json_encode($oResObj));
} elseif (Request::param('ID') == 'volgorde-wijzigen') {
    if (Request::postVar('action') == 'saveOrder' && CSRFSynchronizerToken::validate()) {
        if (Request::postVar('order')) {
            $aUspsIds = explode('|', Request::postVar('order'));
            $iC           = 1;
            foreach ($aUspsIds AS $iUspId) {
                $oUsp        = UspManager::getUspById($iUspId);
                $oUsp->order = $iC;
                if ($oUsp->isValid()) {
                    UspManager::saveUsp($oUsp);
                }
                $iC++;
            }
        }
        $_SESSION['statusUpdate'] = sysTranslations::get('global_sequence_saved'); //save status update into session

        Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment());
    }

    // get all items for order changing
    $aAllUsps           = UspManager::getUspsByFilter(['showAll' => true, 'languageId' => AdminLocales::language()]);
    $oPageLayout->sViewPath = getAdminView('usp_change_order', 'usps');
} elseif (Request::param('ID') == 'crop-image' && is_numeric(Request::getVar("imageId"))) {
    $oImage = ImageManager::getImageById(Request::getVar("imageId"));
    if (!$oImage) {
        $_SESSION['statusUpdate'] = sysTranslations::get('global_image_not_available'); //error getting image
        Router::redirect(Session::get('cropReferrer'));
    }

    $aImageSettings = TemplateSettings::get('usps', 'images');
    $sReferrer      = Session::get('cropReferrer');
    $sReferrerText  = 'usp bewerken';
    $aCropSettings  = ImageManager::handleImageCropSettings($oImage, $aImageSettings, $sReferrer, $sReferrerText);

    // add setting to session in an array
    $_SESSION['aCropSettings'] = $aCropSettings;

    Router::redirect(ADMIN_FOLDER . '/crop');
} // delete object
elseif (Request::param('ID') == 'verwijderen' && is_numeric(Request::param('OtherID'))) {
    if (is_numeric(Request::param('OtherID')) && CSRFSynchronizerToken::validate()) {
        $oUsp = UspManager::getUspById(Request::param('OtherID'));
    }
    if (!empty($oUsp) && UspManager::deleteUsp($oUsp)) {
        $_SESSION['statusUpdate'] = sysTranslations::get('usp_deleted'); //save status update into session
    } else {
        $_SESSION['statusUpdate'] = sysTranslations::get('usp_not_deleted'); //save status update into session
    }
    Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment());
} // display overview
else {
    $aAllUsps           = UspManager::getUspsByFilter(['showAll' => true, 'languageId' => AdminLocales::language()]);
    $oPageLayout->sViewPath = getAdminView('usps_overview', 'usps');
}

// include template
include_once getAdminView('layout');
