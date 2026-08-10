<?php

/*
 * Controller to handle contact page
 */

# Make pageLayout Object
$oPageLayout = new PageLayout();

# Get Page by url path
$oPage = PageManager::getPageByUrlPath(getCurrentUrlPath());

# Check if Page exists or is online
if (empty($oPage) || !$oPage->online) {
    showHttpError('404');
}

# Get submenu structure
if ($oPage->level > 1) {
    $oPageForMenu = PageManager::getPageByUrlPath('/' . http_get('controller'));
} else {
    $oPageForMenu = $oPage;
}

# Get SEO parts
$oPageLayout->sWindowTitle     = $oPage->getWindowTitle();
$oPageLayout->sMetaDescription = $oPage->getMetaDescription();
$oPageLayout->sMetaKeywords    = $oPage->getMetaKeywords();
$oPageLayout->bIndexable       = $oPage->isIndexable();

# Get crumbles
$oPageLayout->generateCustomCrumblePath($oPage->getCrumbles());

if (http_get('param1')) {
    $oPageLayout->sViewPath = getSiteView('page_details', 'pages');
} else {

    if (http_post('action') == 'sendForm') {

        $aErrors   = [];
        $bLogError = true;
        if (!http_post('name')) {
            $aErrors['name'] = _e(SiteTranslations::get('site_fill_in_your_name'));
        }
        if (!http_post('email')) {
            $aErrors['email'] = _e(SiteTranslations::get('site_fill_in_your_email'));
        } elseif (!filter_var(http_post('email'), FILTER_VALIDATE_EMAIL)) {
            $aErrors['email'] = _e(SiteTranslations::get('site_email_not_valid'));
        }
        if (!http_post('message')) {
            $aErrors['message'] = _e(SiteTranslations::get('site_fill_in_your_message'));
        }

        // do SPAM checks
        if (hasLinks(http_post('message'))) {
            $aErrors['message'] = _e(SiteTranslations::get('site_message_has_links'));
            $bLogError          = false;
        }

        if (http_post('rumpelstiltskin-empty') || !http_post('rumpelstiltskin-filled')) { // temporary removed for testing trap || hasLinks(http_post('bericht'))
            $aErrors['S-P-A-M'] = _e(SiteTranslations::get('site_rumpelstiltskin_error'));
            $bLogError          = false;
        }

        ###########################################################################
        ## RECAPTCHA CHECK
        ###########################################################################
        if (SettingManager::getSettingByName('reCaptchaSecretKey') && SettingManager::getSettingByName('reCaptchaWebsiteKey') && Settings::get('reCaptchaSecretKey') && Settings::get('reCaptchaWebsiteKey')) {
            $oCaptcha = new Captcha();
            if (!$oCaptcha->validateBackend(http_post('g-recaptcha-response'))) {
                $aErrors['googleReCaptcha'] = SiteTranslations::get('captcha_error');
                $bLogError                  = false;
            }
        }
        ###########################################################################

        // end SPAM checks

        if (empty($aErrors)) {
            $sSubject = 'Contactformulier verstuurd';
            $sMail    = '  <span style="font: 14px/22px Arial, sans-serif">
                            ' . _e(http_post('name')) . ' heeft het contactformulier ingevuld en verstuurd. Hieronder lees je de verstuurde gegevens.<br />
                            <br />
                            <table style="font: 14px/22px Arial, sans-serif">
                                <tr>
                                    <td>Naam:</td>
                                    <td>' . _e(http_post('name')) . '</td>
                                </tr>
                                <tr>
                                    <td>Organisatie:</td>
                                    <td>' . _e(http_post('organisation')) . '</td>
                                </tr>
                                <tr>
                                    <td>Telefoon:</td>
                                    <td>' . _e(http_post('phone')) . '</td>
                                </tr>
                                <tr>
                                    <td>E-mail:</td>
                                    <td>' . _e(http_post('email')) . '</td>
                                </tr>
                                <tr>
                                    <td>Onderwerp:</td>
                                    <td>' . _e(http_post('subject')) . '</td>
                                </tr>
                                <tr>
                                    <td>Bericht:</td>
                                    <td>' . nl2br(_e(http_post('message'))) . '</td>
                                </tr>
                              </table>
                            <br />
                            <br />
                            Neem zo spoedig mogelijk contact op voor de afhandeling.<br />
                        </span>
                        ';

            MailManager::sendMail((SettingManager::getSettingByName('contact_email_to') && Settings::get('contact_email_to') ? Settings::get('contact_email_to') : CLIENT_DEFAULT_EMAIL_TO), $sSubject, $sMail, CLIENT_DEFAULT_EMAIL_TO, http_post('email'));

            // Make and save form backup
            if (moduleExists('formBackups')) {
                FormBackupManager::makeFormBackup('contactformulier', $_POST);
            }

            // Make and save conversion
            if (moduleExists('conversions')) {
                ConversionManager::makeConversion('contact', http_post('name'), http_post('email'), http_post('phone'), getCurrentUrlPath(), null, http_post('message'));
            }

            $oPageThankYou = PageManager::getPageByName('contact_thanks');

            if ($oPageThankYou) {
                http_redirect($oPageThankYou->getBaseUrlPath());
            } else {
                http_redirect(getCurrentUrlPath());
            }

        } else {
            if ($bLogError) {
                Debug::logError(
                    "",
                    "Contact form on frontend validate error",
                    __FILE__,
                    __LINE__,
                    "Tried to post the Contact form with wrong values despite javascript check (or rumpelstiltkin values are incorrect)." . print_r($_POST, 1),
                    Debug::LOG_IN_EMAIL
                );
            }
        }
    }

    $oPageLayout->sViewPath = getSiteView('contact_form', 'contact');
}

# used for Google maps
$sLatitude            = Settings::get('contactLatitude');
$sLongitude           = Settings::get('contactLongitude');
$sClientName          = _e(Settings::get('clientName'));
$sClientStreet        = _e(Settings::get('clientStreet'));
$sClientPostalCode    = _e(Settings::get('clientPostalCode'));
$sClientCity          = _e(Settings::get('clientCity'));
$sClientStreetEncoded = _e(urlencode(Settings::get('clientStreet')));
$sClientCityEncoded   = _e(urlencode(Settings::get('clientCity')));
$sDirections          = _e(SiteTranslations::get('site_directions'));

# Maps key
if (Settings::exists('googleMapsKey')) {
    $sMapsKey = Settings::get('googleMapsKey');
} else {
    $sMapsKey = null;
}

if ($sLatitude && $sLongitude) {

    $sJavascript = <<<EOT
<script>
    function initGoogleMaps() {

        /* lat/long used by Google Maps */
        var myLatLng = new google.maps.LatLng($sLatitude, $sLongitude);

        /* options for the map */
        var myOptions = {
            zoom: 15,
            center: myLatLng,
            /*
                Set google maps option draggable false on mobile screen size otherwise you maybe
                can not get to the bottom of the page because of scrolling in the google maps instead of the page
            */
            draggable: $(document).width() > 767,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            styles: [{"featureType": "landscape", "stylers": [{"hue": "#FFBB00"}, {"saturation": 43.400000000000006}, {"lightness": 37.599999999999994}, {"gamma": 1}]}, {"featureType": "road.highway", "stylers": [{"hue": "#FFC200"}, {"saturation": -61.8}, {"lightness": 45.599999999999994}, {"gamma": 1}]}, {"featureType": "road.arterial", "stylers": [{"hue": "#FF0300"}, {"saturation": -100}, {"lightness": 51.19999999999999}, {"gamma": 1}]}, {"featureType": "road.local", "stylers": [{"hue": "#FF0300"}, {"saturation": -100}, {"lightness": 52}, {"gamma": 1}]}, {"featureType": "water", "stylers": [{"hue": "#0078FF"}, {"saturation": -13.200000000000003}, {"lightness": 2.4000000000000057}, {"gamma": 1}]}, {"featureType": "poi", "stylers": [{"hue": "#00FF6A"}, {"saturation": -1.0989010989011234}, {"lightness": 11.200000000000017}, {"gamma": 1}]}]
        };

        /* placing the map object */
        var map = new google.maps.Map(document.getElementById('contact-map'), myOptions);

        /* placing the marker with his options */
        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            title: '$sClientName'
        });

        /* for responsive layouts */
        google.maps.event.addDomListener(window, 'resize', function() {
            map.setCenter(myLatLng);
        });

        var infowindow = new google.maps.InfoWindow({
            content: '<p class="google-maps-window">$sClientName<br />$sClientStreet<br />$sClientPostalCode $sClientCity</p><a href="https://maps.google.nl/maps?q=$sClientStreetEncoded,+$sClientCityEncoded&hl=nl&t=m&z=17&iwloc=A" target="_blank">$sDirections</a>'
        });

        google.maps.event.addListener(marker, 'click', function() {
            infowindow.open(map, marker);
        });
    }

    $(window).load(function(){
        initGoogleMaps();
    });
    </script>
EOT;

    $oPageLayout->addJavascript('<script src="https://maps.googleapis.com/maps/api/js' . (!empty($sMapsKey) ? '?key=' . $sMapsKey : '') . '"></script>');
    $oPageLayout->addJavascript($sJavascript);
}

# Include the template
include_once getSiteView('layout');
?>
