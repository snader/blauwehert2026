<?php

/*
 * Controller to handle review pages
 */

# Make pageLayout Object
$oPageLayout = new PageLayout();

# Get Page by url path
$oPage = PageManager::getPageByUrlPath(getCurrentUrlPath());

# Check if Page exists or is online
if (empty($oPage) || !$oPage->online) {
    return Router::httpError('404');
}

# Get submenu structure
if ($oPage->level > 1) {
    $oPageForMenu = PageManager::getPageByUrlPath('/' . Request::getControllerSegment());
} else {
    $oPageForMenu = $oPage;
}

# Get SEO parts
$oPageLayout->sWindowTitle     = $oPage->getWindowTitle();
$oPageLayout->sMetaDescription = $oPage->getMetaDescription();
$oPageLayout->sMetaKeywords    = $oPage->getMetaKeywords();
$oPageLayout->bIndexable       = $oPage->isIndexable();

# Get OG settings
$oPageLayout->sOGType        = 'website';
$oPageLayout->sOGTitle       = $oPage->getWindowTitle();
$oPageLayout->sOGDescription = $oPage->getMetaDescription();
$oPageLayout->sOGUrl         = getCurrentUrl();
if (($oImage = $oPage->getImages('first-online')) && ($oImageFile = $oImage->getImageFileByReference('crop_small'))) {
    $oPageLayout->sOGImage       = CLIENT_HTTP_URL . $oImageFile->link;
    $oPageLayout->sOGImageWidth  = $oImageFile->getWidth();
    $oPageLayout->sOGImageHeight = $oImageFile->getHeight();
}

# Get crumbles
$oPageLayout->generateCustomCrumblePath($oPage->getCrumbles());

# Get ViewPath
$oPageLayout->sViewPath = getSiteView('review_form', 'reviews');

# Get standard media files
$aImages        = $oPage->getImages();
$aVideos = $oPage->getVideoLinks();
$aFiles         = $oPage->getFiles();
$aLinks         = $oPage->getLinks();

if (Request::param('ID')) {
    $oPageLayout->sViewPath = getSiteView('page_details', 'pages');
} else {

    # save review
    if (Request::postVar('action') == 'saveReview') {

        $aErrors   = [];
        $bLogError = true;
        if (!Request::postVar('reviewTitle')) {
            $aErrors['reviewTitle'] = 'U heeft de titel niet ingevuld';
        }

        if (!Request::postVar('reviewRating')) {
            $aErrors['reviewRating'] = 'U heeft geen waardering geselecteerd';
        }

        // do SPAM checks
        if (hasLinks(Request::postVar('reviewReview'))) {
            $aErrors['reviewReview'] = 'Uw bericht bevat een of meer links, dit is niet toegestaan. Verwijder deze en probeer nog eens.';
            $bLogError               = false;
        }

        if (Request::postVar('rumpelstiltskin-empty') || !Request::postVar('rumpelstiltskin-filled')) { // temporary removed for testing trap || hasLinks(Request::self::postVar('bericht'))
            $aErrors['S-P-A-M'] = 'Er is een fout opgetreden bij het versturen van de aanvraag. Probeer het nogmaals of neem op een andere manier contact met ons op.';
            $bLogError          = false;
        }
        // end SPAM checks

        if (empty($aErrors)) {

            $aProps = [
                'localeId'  => Locales::locale(),
                'title'     => Request::postVar('reviewTitle'),
                'rating'    => Request::postVar('reviewRating'),
                'author'    => Request::postVar('reviewAuthor'),
                'review'    => Request::postVar('reviewReview'),
                'reference' => Request::getVar('reference'),
            ];

            // Make new review
            $oReview = New Review($aProps);

            // save review
            if ($oReview->isValid()) {
                ReviewManager::saveReview($oReview);
            } else {
                Debug::logError("", "Review module php validate error", __FILE__, __LINE__, "Tried to save review with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            }

            // Make and save form backup
            if (class_exists('FormBackupManager')) {
                FormBackupManager::makeFormBackup('reviewformulier', $_POST);
            }

            $oPageThankYou = PageManager::getPageByName('review_thanks');
            if ($oPageThankYou) {
                Router::redirect($oPageThankYou->getBaseUrlPath());
            } else {
                Router::redirect(getCurrentUrlPath());
            }
        } else {
            if ($bLogError) {
                Debug::logError(
                    "",
                    "Review form on frontend validate error",
                    __FILE__,
                    __LINE__,
                    "Tried to post the Review form with wrong values despite javascript check (or rumpelstiltkin values are incorrect)." . print_r($_POST, 1),
                    Debug::LOG_IN_EMAIL
                );
            }
        }
    }
}

# Get data for this controller

# Include the template
include_once getSiteView('layout');
?>