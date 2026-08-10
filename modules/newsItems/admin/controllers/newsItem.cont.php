<?php

# check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

# reset crop settings
unset($_SESSION['aCropSettings']);

global $oPageLayout;

# set page layout properties
$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = sysTranslations::get('global_news');
$oPageLayout->sModuleName  = sysTranslations::get('global_news');

# get status update from session
$oPageLayout->sStatusUpdate = http_session("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once

// handle perPage
if (http_post('setPerPage')) {
    $_SESSION['newsPerPage'] = http_post('perPage');
}

// handle filter
$aNewsItemFilter = http_session('newsItemFilter');
if (http_post('filterNewsItems')) {
    $aNewsItemFilter            = http_post('newsItemFilter');
    $aNewsItemFilter['showAll'] = true; // manually set showAll to true
    $_SESSION['newsItemFilter'] = $aNewsItemFilter;
}

if (http_post('resetFilter') || empty($aNewsItemFilter)) {
    unset($_SESSION['newsItemFilter']);
    $aNewsItemFilter                       = [];
    $aNewsItemFilter['q']                  = '';
    $aNewsItemFilter['showAll']            = true; // manually set showAll to true
    $aNewsItemFilter['newsItemCategoryId'] = '';
}

# handle add/edit
if (http_get("param1") == 'bewerken' || http_get("param1") == 'toevoegen') {

    # set crop referrer for pages module
    $_SESSION['cropReferrer'] = ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . http_get("param2");

    if (http_get("param1") == 'bewerken' && is_numeric(http_get("param2"))) {
        $oNewsItem = NewsItemManager::getNewsItemById(http_get("param2"));
        if (!$oNewsItem) {
            http_redirect(ADMIN_FOLDER . "/");
        }
        # is editable?
        if (!$oNewsItem->isEditable()) {
            $_SESSION['statusUpdate'] = sysTranslations::get('news_item_not_edited'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
        }
    } else {
        $oNewsItem             = new NewsItem();
        $oNewsItem->languageId = AdminLocales::language();
    }

    # action = save
    if (http_post("action") == 'save' && CSRFSynchronizerToken::validate()) {

        # load data in object
        $oNewsItem->_load($_POST);

        # set some properties after load, _load strips tags for all inputs
        $oNewsItem->intro   = convertAbsToRelLinks(http_post('intro'));
        $oNewsItem->content = convertAbsToRelLinks(http_post('content'));

        $oNewsItem->onlineFrom = http_post('onlineFromDate') != '' && http_post('onlineFromTime') != '' ? Date::strToDate(http_post('onlineFromDate'))
                ->format('%Y-%m-%d') . ' ' . http_post('onlineFromTime') : null;
        $oNewsItem->onlineTo   = http_post('onlineToDate') != '' ? Date::strToDate(http_post('onlineToDate'))
                ->format('%Y-%m-%d') . ' ' . http_post('onlineToTime') : null;

        $aNewsItemCategories = [];
        foreach (http_post('newsItemCategoryIds', []) AS $iNewsItemCategoryId) {
            $oNewsItemCategory = NewsItemCategoryManager::getNewsItemCategoryById($iNewsItemCategoryId);
            if (!$oNewsItemCategory) {
                continue;
            }
            $aNewsItemCategories[] = $oNewsItemCategory;
        }
        $oNewsItem->setCategories($aNewsItemCategories, 'all'); // set in list all for saving properly
        # if object is valid, save
        if ($oNewsItem->isValid()) {
            NewsItemManager::saveNewsItem($oNewsItem); //save news item
            $_SESSION['statusUpdate'] = sysTranslations::get('news_item_saved'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oNewsItem->newsItemId);
        } else {
            Debug::logError("", "Nieuws module php validate error", __FILE__, __LINE__, "Tried to save NieuwsItem with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $oPageLayout->sStatusUpdate = sysTranslations::get('news_item_not_saved');
        }
    }

    # action saveImage
    if (http_post("action") == 'saveImage' && CSRFSynchronizerToken::validate()) {


        $bCheckMime = true;

        // for upload from MFUpload
        if (http_post("MFUpload")) {
            $oResObj          = new stdClass();
            $oResObj->success = false;
            $bCheckMime       = true;
        }

        $aImageSettings = TemplateSettings::get('newsItems', 'images');

        // upload file or return error
        $oUpload = new Upload($_FILES['image'], $aImageSettings['imagesPath'] . "/" . $aImageSettings['originalReference'] . "/", (http_post('title') != '' ? http_post('title') : null), [
            'jpg',
            'png',
            'gif',
            'jpeg',
        ], $bCheckMime);

        // save image to database on success
        if ($oUpload->bSuccess === true) {
            $sTitle = http_post('title', '');
            $oImage = ImageManager::handleImageUpload($oUpload, $aImageSettings, $sTitle, $aErrorMsgs);

            if ($oImage) {
                # save news item image relation
                NewsItemManager::saveNewsItemImageRelation($oNewsItem->newsItemId, $oImage->imageId);
            }

            // for MFUpload
            if (http_post("MFUpload")) {
                $oResObj->success = true;
                $oImageFileThumb  = $oImage->getImageFileByReference('cms_thumb');
                // add some extra values
                $oImageFileThumb->isOnlineChangeable  = $oImage->isOnlineChangeable();
                $oImageFileThumb->isCropable          = $oImage->isCropable();
                $oImageFileThumb->isEditable          = $oImage->isEditable();
                $oImageFileThumb->isDeletable         = $oImage->isDeletable();
                $oImageFileThumb->hasNeededImageFiles = $oImage->hasImageFiles(TemplateSettings::get('newsItems', 'images/neededImageFiles'));

                $oResObj->imageFile = $oImageFileThumb; // for adding image to list (last imageFile object)
                print json_encode($oResObj);
                die;
            }

            // back to edit
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oNewsItem->newsItemId);
        } else {

            // for MFUpload
            if (http_post("MFUpload")) {
                $oResObj->errorMsg = $oUpload->getErrorMessage();
                print json_encode($oResObj);
                die;
            }

            $_SESSION['statusUpdate'] = sysTranslations::get('global_image_not_uploaded') . $oUpload->getErrorMessage(); //error uploading file
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oNewsItem->newsItemId);
        }
    }

    # action saveFile
    if (http_post("action") == 'saveFile' && CSRFSynchronizerToken::validate()) {

        $bCheckMime = true;

        // for upload from MFUpload
        if (http_post("MFUpload")) {
            $oResObj          = new stdClass();
            $oResObj->success = false;
            $bCheckMime       = true;
        }

        // upload file or return error
        $oUpload = new Upload($_FILES['file'], NewsItem::FILES_PATH . '/', (http_post('file_title') != '' ? http_post('file_title') : null), null, $bCheckMime);

        // save file to database on success
        if ($oUpload->bSuccess === true) {

            // make File object and save
            $oFile           = new File();
            $oFile->name     = $oUpload->sNewFileBaseName;
            $oFile->mimeType = $oUpload->sMimeType;
            $oFile->size     = $oUpload->iSize;
            $oFile->link     = $oUpload->sNewFilePath;
            $oFile->title    = http_post('title') == '' ? $oUpload->sFileName : http_post('title');

            // save file
            FileManager::saveFile($oFile);

            // save page file relation
            NewsItemManager::saveNewsItemFileRelation($oNewsItem->newsItemId, $oFile->mediaId);

            // for MFUpload
            if (http_post("MFUpload")) {
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
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oNewsItem->newsItemId);
        } else {

            // for MFUpload
            if (http_post("MFUpload")) {
                $oResObj->errorMsg = $oUpload->getErrorMessage();
                print json_encode($oResObj);
                die;
            }

            $_SESSION['statusUpdate'] = sysTranslations::get('global_file_not_uploaded') . $oUpload->getErrorMessage(); //error uploading file
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oNewsItem->newsItemId);
        }
    }

    # action = saveLink
    if (http_post("action") == 'saveLink' && CSRFSynchronizerToken::validate()) {

        # load data in object
        $oLink = new Link();
        $oLink->_load($_POST);

        # if object is valid, save
        if ($oLink->isValid()) {
            LinkManager::saveLink($oLink); //save link
            NewsItemManager::saveNewsItemLinkRelation($oNewsItem->newsItemId, $oLink->mediaId);
            $_SESSION['statusUpdate'] = sysTranslations::get('global_link_saved'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oNewsItem->newsItemId);
        } else {
            Debug::logError("", "Nieuws item module php validate error", __FILE__, __LINE__, "Tried to save Link with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $oPageLayout->sStatusUpdate = sysTranslations::get('global_link_not_saved');
        }
    }

    # action = saveVideoLink
    if (http_post("action") == 'saveVideoLink' && CSRFSynchronizerToken::validate()) {
        if ($oVideoLink = VideoLinkManager::saveVideoLink(Request::postVars())) {
            NewsItemManager::saveNewsItemVideoLinkRelation($oNewsItem->newsItemId, $oVideoLink->mediaId);
            $_SESSION['statusUpdate'] = sysTranslations::get('global_video_saved'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oNewsItem->newsItemId);
        } else {
            Debug::logError("", "Nieuws item module php validate error", __FILE__, __LINE__, "Tried to save Link with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $oPageLayout->sStatusUpdate = sysTranslations::get('global_video_not_saved');
        }
    }

    # set settings for image management
    $oImageManagerHTML                                       = new ImageManagerHTML();
    $oImageManagerHTML->bMultipleFileUpload                  = true;
    $oImageManagerHTML->aMultipleFileUploadAllowedExtensions = [
        'png',
        'jpg',
        'gif',
        'jpeg',
    ];
    $oImageManagerHTML->aImages                              = $oNewsItem->getImages('all');
    $oImageManagerHTML->cropLink                             = ADMIN_FOLDER . '/' . http_get('controller') . '/crop-image';
    $oImageManagerHTML->sUploadUrl                           = ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oNewsItem->newsItemId;
    $oImageManagerHTML->aNeededImageFileReferences           = TemplateSettings::get('newsItems', 'images/neededImageFiles');
    $oImageManagerHTML->bShowCropAfterUploadOption           = false;

    # set settings for file management
    $oFileManagerHTML                      = new FileManagerHTML();
    $oFileManagerHTML->aFiles              = $oNewsItem->getFiles('all');
    $oFileManagerHTML->sUploadUrl          = ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oNewsItem->newsItemId;
    $oFileManagerHTML->bMultipleFileUpload = true;
    $oFileManagerHTML->bTitleRequired      = false;

    # set link manager
    $oLinkManagerHTML             = new LinkManagerHTML();
    $oLinkManagerHTML->aLinks     = $oNewsItem->getLinks('all');
    $oLinkManagerHTML->sUploadUrl = ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oNewsItem->newsItemId;

    # set video manager
    $oVideoLinkManagerHTML              = new VideoLinkManagerHTML();
    $oVideoLinkManagerHTML->aVideoLinks = $oNewsItem->getVideoLinks('all');
    $oVideoLinkManagerHTML->sUploadUrl  = ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oNewsItem->newsItemId;

    ##########################
    # START AUTOCOMPLETE PAGES#
    ##########################

    $aAutocompleters           = [];
    $aAutocompletersToRegister = [];
    if (moduleExists('pages')) {
        $aAutocompletersToRegister[] = [
            'title'       => sysTranslations::get('autocomplete_pages_title'),
            'masterModel' => NewsItem::class,
            'masterId'    => $oNewsItem->newsItemId,
            'slaveModel'  => Page::class,
        ];
    }
    foreach ($aAutocompletersToRegister as $aSetting) {
        array_push($aAutocompleters, AutocompleteManager::create($aSetting));
    }

    #######################
    #END AUTOCOMPLETE PAGES#
    #######################

    $oPageLayout->sViewPath = getAdminView('newsItems/newsItem_form', 'newsItems');
} elseif (http_get("param1") == 'crop-image' && is_numeric(http_get("imageId"))) {
    $oImage = ImageManager::getImageById(http_get("imageId"));

    if (!$oImage) {
        $_SESSION['statusUpdate'] = sysTranslations::get('global_image_not_available'); //error getting image
        http_redirect(http_session('cropReferrer'));
    }

    $aImageSettings = TemplateSettings::get('newsItems', 'images');
    $sReferrer      = http_session('cropReferrer');
    $sReferrerText  = 'nieuwsitem bewerken';
    $aCropSettings  = ImageManager::handleImageCropSettings($oImage, $aImageSettings, $sReferrer, $sReferrerText);

    // add setting to session in an array
    $_SESSION['aCropSettings'] = $aCropSettings;

    http_redirect(ADMIN_FOLDER . '/crop');
} elseif (http_get("param1") == 'verwijderen' && is_numeric(http_get("param2"))) {
    if(CSRFSynchronizerToken::validate()) {
        if (is_numeric(http_get("param2"))) {
            $oNewsItem = NewsItemManager::getNewsItemById(http_get("param2"));
        }

        if ($oNewsItem && NewsItemManager::deleteNewsItem($oNewsItem)) {
            $_SESSION['statusUpdate'] = sysTranslations::get('news_item_deleted'); //save status update into session
        } else {
            $_SESSION['statusUpdate'] = sysTranslations::get('news_item_not_deleted'); //save status update into session
        }
    }
    http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
} elseif (http_get("param1") == 'ajax-setOnline') {
    if(!CSRFSynchronizerToken::validate()){
        die(json_encode(['status'=>false]));
    }
    $bOnline     = http_get("online", 0); //no value, set offline by default
    $bAjax       = http_get("ajax", false); //controller requested by ajax
    $iNewsItemId = http_get("param2");
    $oResObj     = new stdClass(); //standard class for json feedback
    # update online for page
    if (is_numeric($iNewsItemId)) {
        $oResObj->success    = NewsItemManager::updateOnlineByNewsItemId($bOnline, $iNewsItemId);
        $oResObj->newsItemId = $iNewsItemId;
        $oResObj->online     = $bOnline;
    }

    if (!$bAjax) {
        http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
    }
    print json_encode($oResObj);
    die;
} elseif (http_get('param1') == 'image-list') {
    $oNewsItem = NewsItemManager::getNewsItemById(http_get('param2'));

    if (!$oNewsItem) {
        die;
    }

    $aImages     = $oNewsItem->getImages('all');
    $aImageFiles = [];
    $aLinks      = [];
    foreach ($aImages AS $oImage) {
        $oImageFile = $oImage->getImageFileByReference('detail');
        if (!$oImageFile) {
            continue;
        }
        $oLink        = new stdClass();
        $oLink->title = $oImageFile->title ? $oImageFile->title : $oImageFile->name;
        $oLink->value = CLIENT_HTTP_URL . $oImageFile->link;
        $aLinks[]     = $oLink;
    }

    die(json_encode($aLinks));
} else {

    $iNrOfRecords = DBConnection::count('news_items');
    $iPerPage     = http_session('newsPerPage', 10);
    $iCurrPage    = http_get('page', 1);

    if (http_post('setPerPage')) {
        // reset iCurrPage on setPerPage change
        $iCurrPage = 1;
    }
    if ($iCurrPage > ($iNrOfRecords / $iPerPage) + 1) {
        // prevent non existing iCurrpage
        $iCurrPage = (round($iNrOfRecords / $iPerPage) + 1);
    }

    $iStart = (($iCurrPage - 1) * $iPerPage);
    if (!is_numeric($iCurrPage) || $iCurrPage <= 0) {
        http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
    }

    # add language to filter
    $aNewsItemFilter['languageId'] = AdminLocales::language();

    #display news overview
    $aNewsItems             = NewsItemManager::getNewsItemsByFilter($aNewsItemFilter, $iPerPage, $iStart, $iFoundRows);
    $iPageCount             = !empty($iPerPage) ? (ceil($iFoundRows / $iPerPage)) : 0;
    $oPageLayout->sViewPath = getAdminView('newsItems/newsItems_overview', 'newsItems');
}

# inlcude template
include_once getAdminView('layout');
?>