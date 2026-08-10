<?php

// Mollie payment service provider script

$oPageLayout = new PageLayout();

if (http_get('param1') == 'webhook') {
    try {
        require_once SYSTEM_MODULES_FOLDER . '/paymentMollie/libs/Mollie/API/Autoloader.php';

        // prepare payment for Mollie
        $oMollie = new Mollie_API_Client();
        $oMollie->setApiKey(Settings::get('mollieAPIKey'));

        // get payment from Mollie
        if (http_get('actor') == 'c') {
            $oOrderPayment  = OrderPaymentManager::getPaymentById(http_get('param2'));
            $oMolliePayment = $oMollie->payments->get($oOrderPayment->externalPaymentReference);
        } else {
            $oMolliePayment = $oMollie->payments->get(http_post('id'));
        }

        if ($oMolliePayment) {
            $iOrderPaymentId = $oMolliePayment->metadata->order_id; // order_id is set with orderPaymentId, more unique than orderId
        } else {
            MailManager::sendMail(DEFAULT_ERROR_EMAIL, 'Mollie webhook error: no payment found', _d($oMolliePayment, 1, 1));
            die;
        }
        // payment and order are present and awaiting payment
        if ($iOrderPaymentId && ($oOrderPayment = OrderPaymentManager::getPaymentById($iOrderPaymentId)) && $oOrderPayment->status == OrderPayment::STATUS_AWAITING_PAYMENT) {
            if ($oMolliePayment->isPaid() == true) {
                // process payment accepted
                $oOrderPayment->processStatus(OrderPayment::STATUS_ACCEPTED);
            } elseif ($oMolliePayment->isOpen() == false) {
                // process payment canceled
                // do nothing will be redirected to page to repay with payment
            } else {
                MailManager::sendMail(DEFAULT_ERROR_EMAIL, 'Mollie webhook payment closed already', _d($oMolliePayment, 1, 1));
            }
        } elseif (!$oOrderPayment) {
            MailManager::sendMail(DEFAULT_ERROR_EMAIL, 'Mollie webhook no orderPayment', _d($oMolliePayment, 1, 1) . _d($_SERVER, 1, 1));
        }

        if (http_get('actor') == 'c') {
            // client needs redirect
            switch ($oOrderPayment->status) {
                case OrderPayment::STATUS_ACCEPTED:
                    unset($_SESSION['iLatestOrderPaymentId']);
                    http_redirect(PageManager::getPageByName('payment_success')->getBaseUrlPath() . '?conversion=1&pid=' . $oOrderPayment->getPID());
                    break;
                default:
                    http_redirect(PageManager::getPageByName('payment_error')->getBaseUrlPath());
                    break;
            }
        } else {
            die('OK'); // Mollie server needs OK
        }
    } catch (Mollie_API_Exception $e) {
        MailManager::sendMail(DEFAULT_ERROR_EMAIL, 'Mollie niet bereikbaar', _d($e->getMessage(), 1, 1) . _d($_SESSION, 1, 1));
        die('Error: ' . $e->getMessage());
    }
} elseif (http_get('param1')) {

    $oPage = PageManager::getPageByUrlPath(getCurrentUrlPath());

    if (empty($oPage) || !$oPage->online) {
        showHttpError('404');
    }

    if ($oPage->level > 1) {
        $oPageForMenu = PageManager::getPageByUrlPath('/' . http_get('controller'));
    } else {
        $oPageForMenu = $oPage;
    }

    $oPageLayout->sViewPath        = getSiteView('page_details', 'pages');
    $oPageLayout->sWindowTitle     = $oPage->getWindowTitle();
    $oPageLayout->sMetaDescription = $oPage->getMetaDescription();
    $oPageLayout->sMetaKeywords    = $oPage->getMetaKeywords();
    $oPageLayout->generateCustomCrumblePath($oPage->getCrumbles());
    $oPageLayout->bIndexable = $oPage->isIndexable();

    $aImages = $oPage->getImages();
    $aVideos = $oPage->getVideoLinks();
    $aFiles  = $oPage->getFiles();
    $aLinks  = $oPage->getLinks();

    // handle conversion stuff
    if (http_get('conversion') == 1 && http_get('pid')) {
        $oOrderPayment = OrderPaymentManager::getPaymentByPID(http_get('pid'));
        if ($oOrderPayment) {
            // get conversion template and add at the bottom of the page
            ob_start();
            include_once getAdminSnippet('order_conversion', 'orders');
            $sConversionTemplate = ob_get_clean();
            $oPageLayout->addJavascript($sConversionTemplate, 99, 'conversions_top');
        }
    }
} else {
    $iPaymentId    = http_session('iLatestOrderPaymentId');
    $oOrderPayment = new OrderPayment();
    if (($oOrderPayment = OrderPaymentManager::getPaymentById($iPaymentId)) && $oOrderPayment->status == OrderPayment::STATUS_AWAITING_PAYMENT) {

        try {

            require_once SYSTEM_MODULES_FOLDER . '/paymentMollie/libs/Mollie/API/Autoloader.php';

            // prepare payment for Mollie
            $oMollie = new Mollie_API_Client();
            $oMollie->setApiKey(Settings::get('mollieAPIKey'));

            $oPayment = $oMollie->payments->create(
                [
                    'amount'      => $oOrderPayment->getOrder()
                        ->getTotal(true),
                    'description' => 'Order ' . $oOrderPayment->orderId,
                    'redirectUrl' => PageManager::getPageByName('payment')->getBaseUrlPath() . '/webhook/' . $oOrderPayment->orderPaymentId . '?actor=c',
                    'webhookUrl'  => PageManager::getPageByName('payment')->getBaseUrlPath() . '/webhook/' . $oOrderPayment->orderPaymentId,
                    'metadata'    => [
                        'order_id' => $oOrderPayment->orderPaymentId, // set orderPaymentId as a referecnce, not the orderId
                    ],
                ]
            );

            $oOrderPayment->externalStatus           = $oPayment->status;
            $oOrderPayment->externalPaymentReference = $oPayment->id;
            OrderPaymentManager::savePayment($oOrderPayment);

            http_redirect($oPayment->getPaymentUrl());
        } catch (Mollie_API_Exception $e) {
            MailManager::sendMail(DEFAULT_ERROR_EMAIL, 'Mollie niet bereikbaar', _d($e->getMessage(), 1, 1) . _d($_SESSION, 1, 1) . _d($oPayment, 1, 1));
            http_redirect(PageManager::getPageByName('payment_error')->getBaseUrlPath());
        }
    } else {
        http_redirect(PageManager::getPageByName('payment_unknown')->getBaseUrlPath());
    }
}
include_once getSiteView('layout');
?>