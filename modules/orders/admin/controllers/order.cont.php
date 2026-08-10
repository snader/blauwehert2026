<?php

# check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

global $oPageLayout;

# set page layout properties
$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = sysTranslations::get('orders_orders');
$oPageLayout->sModuleName  = sysTranslations::get('orders_orders');

# get status update from session
$oPageLayout->sStatusUpdate = http_session("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once
// get order in PDF format
# handle orderFilter
$aOrderFilter = http_session('orderFilter');
if (http_post('filterForm')) {
    $aOrderFilter            = http_post('orderFilter');
    $_SESSION['orderFilter'] = $aOrderFilter;
}

if (http_post('resetFilter') || empty($aOrderFilter)) {
    unset($_SESSION['orderFilter']);
    $aOrderFilter             = [];
    $aOrderFilter['orderId']  = '';
    $aOrderFilter['statuses'] = [
        OrderStatus::STATUS_NEW,
        OrderStatus::STATUS_AWAITING_PAYMENT,
        OrderStatus::STATUS_PAYED,
        OrderStatus::STATUS_READY_FOR_PROCESSING,
        OrderStatus::STATUS_PROCESSING,
        OrderStatus::STATUS_PROCESSED,
    ];
}

// handle perPage
if (http_post('setPerPage')) {
    $_SESSION['ordersPerPage'] = http_post('perPage');
}

# handle add/edit
if (http_get("param1") == 'bewerken') {

    $oOrder = OrderManager::getOrderById(http_get("param2"));

    if (empty($oOrder)) {
        http_redirect(ADMIN_FOLDER . "/");
    }

    if (http_post('action') == 'getPdf' && CSRFSynchronizerToken::validate()) {
        @$oOrder->getPdf()
            ->Output('order-' . $oOrder->orderId . '.pdf', 'D');
        die;
    }

    # action = save
    if (http_post("action") == 'save' && CSRFSynchronizerToken::validate()) {
        # load data in object
        $oOrder->_load($_POST);

        # if object is valid, save
        if ($oOrder->isValid()) {
            OrderManager::saveOrder($oOrder); //save object
            $_SESSION['statusUpdate'] = sysTranslations::get('order_saved'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oOrder->orderId);
        } else {
            Debug::logError("", "Order module php validate error", __FILE__, __LINE__, "Tried to save Order with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $_SESSION['statusUpdate'] = sysTranslations::get('order_not_saved');
        }
    }

    # define a new OrderStatus to save
    $oStatus = new OrderStatus(['orderId' => $oOrder->orderId]);

    # action = saveStatus
    if (http_post("action") == 'saveStatus' && CSRFSynchronizerToken::validate()) {

        $sNotificationInfo = '';
        if (http_post('comment')) {
            $sNotificationInfo = '<p>' . sysTranslations::get('global_comment') . ': <br />' . http_post('comment') . '</p>';
        }

        $oOrder->processStatus(http_post('status'), http_post('notifyCustomer'), false, $sNotificationInfo, '', UserManager::getCurrentUser()->name);
        $_SESSION['statusUpdate'] = sysTranslations::get('global_status_saved'); //save status update into session
        http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oOrder->orderId);
    }
    $oPageLayout->sViewPath = getAdminView('orders/order_form', 'orders');
} # handle export
elseif (http_get('param1') == 'export') {
    if (!CSRFSynchronizerToken::validate()) {
        Router::redirect(ADMIN_FOLDER . '/' . Request::getControllerSegment());
    }
    $bWithTax = http_get('withtax');
    // I onlye get the filtered orders
    $aOrders = OrderManager::getOrdersByFilter($aOrderFilter);

    include_once getAdminView('/orders/orders_export', 'orders');

    die($sExport);
} # display overview
else {

    $iNrOfRecords = DBConnection::count('orders');
    $iPerPage     = http_session('ordersPerPage', 10);
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

    $aOrders                = OrderManager::getOrdersByFilter($aOrderFilter, $iPerPage, $iStart, $iFoundRows);
    $iPageCount             = !empty($iPerPage) ? (ceil($iFoundRows / $iPerPage)) : 0;
    $oPageLayout->sViewPath = getAdminView('orders/orders_overview', 'orders');
}

# include template
include_once getAdminView('layout');
?>
