<?php

# check if controller is required by index.php
if (!defined('ACCESS')) {
    die;
}

global $oPageLayout;

# set page layout properties
$oPageLayout               = new PageLayout();
$oPageLayout->sWindowTitle = sysTranslations::get('global_customers');
$oPageLayout->sModuleName  = sysTranslations::get('global_customers');

# get status update from session
$oPageLayout->sStatusUpdate = http_session("statusUpdate");
unset($_SESSION['statusUpdate']); //remove statusupdate, always show once
# handle customerFilter
$aCustomerFilter = http_session('customerFilter');
if (http_post('filterForm')) {
    $aCustomerFilter            = http_post('customerFilter');
    $_SESSION['customerFilter'] = $aCustomerFilter;
}

if (http_post('resetFilter') || empty($aCustomerFilter)) {
    unset($_SESSION['customerFilter']);
    $aCustomerFilter         = [];
    $aCustomerFilter['name'] = '';
}

# handle perPage
if (http_post('setPerPage')) {
    $_SESSION['customersPerPage'] = http_post('perPage');
}

# check if an email address exists
if (http_get('ajax') == 'checkEmail') {
    # return if the required fields are empty
    if (empty($_GET['email'])) {
        die(json_encode(null));
    }

    # return bool (true if email doesn't exists and vice versa)
    die(json_encode(!CustomerManager::emailExists(http_get('email'), http_get('customerId', null))));
}

# handle add/edit
if (http_get("param1") == 'bewerken' || http_get("param1") == 'toevoegen') {
    if (http_get("param1") == 'bewerken' && is_numeric(http_get("param2"))) {
        $oCustomer = CustomerManager::getCustomerById(http_get("param2"));
        if (empty($oCustomer)) {
            http_redirect(ADMIN_FOLDER . "/");
        }
    } else {
        $oCustomer = new Customer();
    }

    # action = save
    if (http_post("action") == 'save' && CSRFSynchronizerToken::validate()) {
        # do special things before load (because of password hashing)
        if (http_get("param1") == 'bewerken') {
            if (empty($_POST["password"])) {
                #set password from object
                $_POST['password'] = $oCustomer->password;
            } else {
                #hash and check password for saving later
                if (!empty($_POST['password']) && strlen(http_post('password')) >= 8) {
                    $_POST['password'] = hashPasswordForDb(http_post('password'));
                } else {
                    $_POST['password'] = null;
                }
            }
        } else {
            # check password is valid
            if (!empty($_POST['password']) && strlen(http_post('password')) >= 8) {
                $_POST['password'] = hashPasswordForDb(http_post('password'));
            } else {
                $_POST['password'] = null; //object validation will trigger error
            }
        }

        # load data in object
        $oCustomer->_load($_POST);

        # update didSignOffForEmail property when adminuser overwrites contactByEmail property
        if ($oCustomer->contactByEmail == 1) {
            $oCustomer->didSignOffForEmail = 0;
        }

        # add http when not already added
        $oCustomer->companyWebsite = addHttp($oCustomer->companyWebsite);

        $aCustomerGroups = [];
        foreach (http_post("customerGroupIds", []) AS $iCustomerGroupId) {
            $aCustomerGroups[] = new CustomerGroup(['customerGroupId' => $iCustomerGroupId]);
        }

        # set modules into customer
        $oCustomer->setCustomerGroups($aCustomerGroups);

        # if object is valid, save
        if ($oCustomer->isValid()) {
            CustomerManager::saveCustomer($oCustomer); //save object
            if (http_post('resetBounce') == 1) {
                # reset general bounce
                CustomerManager::saveCustomerBounceRuleDescriptionByCustomerEmail($oCustomer->email, null, null, null, 1);
            }
            $_SESSION['statusUpdate'] = sysTranslations::get('customer_saved'); //save status update into session
            http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '/bewerken/' . $oCustomer->customerId);
        } else {
            Debug::logError("", "Customer module php validate error", __FILE__, __LINE__, "Tried to save Customer with wrong values despite javascript check.<br />" . _d($_POST, 1, 1), Debug::LOG_IN_EMAIL);
            $_SESSION['statusUpdate'] = sysTranslations::get('customer_not_saved');
        }
    }

    $oPageLayout->sViewPath = getAdminView('customers/customer_form', 'customers');
} # set object online/offline
elseif (http_get("param1") == 'ajax-setOnline') {
    if(!CSRFSynchronizerToken::validate()){
        die(json_encode(['status'=>false]));
    }
    $bOnline     = http_get("online", 0); //no value, set offline by default
    $bAjax       = http_get("ajax", false); //controller requested by ajax
    $iCustomerId = http_get("param2");
    $oResObj     = new stdClass(); //standard class for json feedback
    # update online for object
    if (is_numeric($iCustomerId)) {
        $oResObj->success    = CustomerManager::updateOnlineByCustomerId($bOnline, $iCustomerId);
        $oResObj->customerId = $iCustomerId;
        $oResObj->online     = $bOnline;
    }

    # redirect to overview page if this isn't AJAX
    if (!$bAjax) {
        http_redirect(ADMIN_FOLDER . '/' . http_get('controller') . '');
    }

    die(json_encode($oResObj));
} # delete object
elseif (http_get("param1") == 'verwijderen' && is_numeric(http_get("param2"))) {
    if(CSRFSynchronizerToken::validate()) {
        if (is_numeric(http_get("param2"))) {
            $oCustomer = CustomerManager::getCustomerById(http_get("param2"));
        }

        if (!empty($oCustomer) && CustomerManager::deleteCustomer($oCustomer)) {
            $_SESSION['statusUpdate'] = sysTranslations::get('customer_deleted'); //save status update into session
        } else {
            $_SESSION['statusUpdate'] = sysTranslations::get('customer_not_deleted'); //save status update into session
        }
    }
    http_redirect(ADMIN_FOLDER . '/' . http_get('controller'));
} # display overview
else {

    if (http_post('action') == 'unlockCustomer') {
        if(!CSRFSynchronizerToken::validate()){
            die(json_encode(['status'=>false]));
        }
        $oCustomer = CustomerManager::getCustomerById(http_post('customerId'));
        if ($oCustomer->locked) {
            CustomerManager::unlockCustomer($oCustomer, http_post('unlockReason'));
        }
        http_redirect(getCurrentUrlPath());
    }

    $iNrOfRecords = DBConnection::count('customers');
    $iPerPage     = http_session('customersPerPage', 10);
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

    $aCustomers = CustomerManager::getCustomersByFilter($aCustomerFilter, $iPerPage, $iStart, $iFoundRows);
    $iPageCount = !empty($iPerPage) ? (ceil($iFoundRows / $iPerPage)) : 0;

    $oPageLayout->sViewPath = getAdminView('customers/customers_overview', 'customers');
}

# include template
include_once getAdminView('layout');
?>