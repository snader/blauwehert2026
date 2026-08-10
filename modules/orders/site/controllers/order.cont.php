<?php

/*
 * controller to handle the shopping basket (order)
 */

# make PageLayout Object
$oPageLayout = new PageLayout();

// get status update from session
$sOrderStatusUpdate = Session::get("orderStatusUpdate", null);
Session::clear('orderStatusUpdate'); //remove statusupdate, always show once

# define the order settings in the SESSION
if (empty($_SESSION['aOrderSettings'])) {
    $_SESSION['aOrderSettings'] = [];
}

# define variable for checking if this is an AJAX request
$bAjax = http_post("ajax", false);

# order products
if (getCurrentUrlPath() == PageManager::getPageByName('shoppingcart_order')->getUrlPath()) {

    # return to basket if there are no products
    if (count(
            OrderManager::getBasket()
                ->getProducts()
        ) <= 0) {
        http_redirect(getBaseUrl() . '/' . http_get('controller'));
    }

    # go to signup page if the Customer is not logged in and if it's needed
    $_SESSION['frontendLoginReferrer'] = getCurrentUrl();
    if (class_exists('customers') && Settings::get('loginRequiredBeforeOrder') && empty(Customer::getCurrent())) {
        http_redirect(PageManager::getPageByName('account')->getBaseUrlPath());
    }

    // if user is logged in
    if (!empty(Customer::getCurrent())) {
        # add Customer info to the Order (by default, the delivery is the same as invoice)
        OrderManager::getBasket()
            ->setCustomer(Customer::getCurrent());
        OrderManager::getBasket()
            ->setInvoiceToDelivery();
    }
    // true by default
    $_SESSION['aOrderSettings']['delivery_same_as_invoice'] = true;

    # action saveOrder
    if (!empty($_POST['saveOrder'])) {

        OrderManager::getBasket()->email                       = http_post('email');
        OrderManager::getBasket()->invoice_companyName         = http_post('invoice_companyName');
        OrderManager::getBasket()->invoice_gender              = http_post('invoice_gender');
        OrderManager::getBasket()->invoice_firstName           = http_post('invoice_firstName');
        OrderManager::getBasket()->invoice_insertion           = http_post('invoice_insertion');
        OrderManager::getBasket()->invoice_lastName            = http_post('invoice_lastName');
        OrderManager::getBasket()->invoice_address             = http_post('invoice_address');
        OrderManager::getBasket()->invoice_houseNumber         = http_post('invoice_houseNumber');
        OrderManager::getBasket()->invoice_houseNumberAddition = http_post('invoice_houseNumberAddition');
        OrderManager::getBasket()->invoice_postalCode          = http_post('invoice_postalCode');
        OrderManager::getBasket()->invoice_city                = http_post('invoice_city');
        OrderManager::getBasket()->invoice_phone               = http_post('invoice_phone');

        if (http_post('delivery_same_as_invoice')) {
            OrderManager::getBasket()
                ->setInvoiceToDelivery();
        } else {
            OrderManager::getBasket()->delivery_companyName         = http_post('delivery_companyName');
            OrderManager::getBasket()->delivery_gender              = http_post('delivery_gender');
            OrderManager::getBasket()->delivery_firstName           = http_post('delivery_firstName');
            OrderManager::getBasket()->delivery_insertion           = http_post('delivery_insertion');
            OrderManager::getBasket()->delivery_lastName            = http_post('delivery_lastName');
            OrderManager::getBasket()->delivery_address             = http_post('delivery_address');
            OrderManager::getBasket()->delivery_houseNumber         = http_post('delivery_houseNumber');
            OrderManager::getBasket()->delivery_houseNumberAddition = http_post('delivery_houseNumberAddition');
            OrderManager::getBasket()->delivery_postalCode          = http_post('delivery_postalCode');
            OrderManager::getBasket()->delivery_city                = http_post('delivery_city');
        }

        if ((DeliveryMethodManager::isValidDeliveryMethodPaymentMethodRelation(OrderManager::getBasket()->deliveryMethodId, OrderManager::getBasket()->paymentMethodId))) {
            $oDeliveryMethod = DeliveryMethodManager::getDeliveryMethodById(OrderManager::getBasket()->deliveryMethodId);
            $oPaymentMethod  = PaymentMethodManager::getPaymentMethodById(OrderManager::getBasket()->paymentMethodId);
            OrderManager::getBasket()
                ->setDeliveryMethod($oDeliveryMethod);
            OrderManager::getBasket()
                ->setPaymentMethod($oPaymentMethod);
        } else {
            OrderManager::getBasket()->deliveryMethodId = null;
            OrderManager::getBasket()->paymentMethodId  = null;
        }

        // if object is valid, save
        if (OrderManager::getBasket()
            ->isValid()) {

            OrderManager::saveOrder(OrderManager::getBasket()); //save object
            // process status new, handle status and set next status
            OrderManager::getBasket()
                ->processStatus(OrderStatus::STATUS_NEW, true, true);

            // Make and save conversion
            if (class_exists('conversions')) {
                ConversionManager::makeConversion(
                    'order',
                    (OrderManager::getBasket()->invoice_firstName . ' ' . OrderManager::getBasket()->invoice_lastName),
                    OrderManager::getBasket()->email,
                    OrderManager::getBasket()->invoice_phone,
                    getCurrentUrlPath(),
                    OrderManager::getBasket()
                        ->getTotal(true)
                );
            }

            if (OrderManager::getBasket()
                    ->getTotal(false) != 0.00) {
                // check if there needs to be an online payment
                if (OrderManager::getBasket()
                    ->getPaymentMethod()->isOnlinePaymentMethod) {
                    // define and save the new OrderPayment
                    $oOrderPayment = new OrderPayment(
                        [
                            'price'           => OrderManager::getBasket()
                                ->getTotal(true),
                            'status'          => OrderPayment::STATUS_AWAITING_PAYMENT,
                            'orderId'         => OrderManager::getBasket()->orderId,
                            'paymentMethodId' => OrderManager::getBasket()->paymentMethodId,
                        ]
                    );
                    OrderPaymentManager::savePayment($oOrderPayment); //save the OrderPayment and OrderStatus
                }

                # check if the payment needs to be handles online
                if (OrderManager::getBasket()
                    ->getPaymentMethod()->isOnlinePaymentMethod) {
                    $_SESSION['iLatestOrderPaymentId'] = $oOrderPayment->orderPaymentId;
                }

                $sRedirectPage = OrderManager::getBasket()
                    ->getPaymentMethod()
                    ->getTranslations()->redirectPage;
            } else {
                $sRedirectPage = PageManager::getPageByName('shoppingcart_order_placed')
                    ->getUrlPath();
            }

            OrderManager::clearBasket(); //empty the basket
            # redirect to payment page
            http_redirect($sRedirectPage);
        } else {
            $aErrorsBasket = [];
            if (!OrderManager::getBasket()
                ->isPropValid("email")) {
                $aErrorsBasket['email'] = _e(SiteTranslations::get('site_email_not_valid')) . ' (' . _e(SiteTranslations::get('site_invoice_details')) . ').';
            }
            if (!OrderManager::getBasket()
                ->isPropValid("invoice_gender")) {
                $aErrorsBasket['invoice_gender'] = _e(SiteTranslations::get('site_select_gender')) . ' (' . _e(SiteTranslations::get('site_invoice_details')) . ').';
            }
            if (!OrderManager::getBasket()
                ->isPropValid("invoice_firstName")) {
                $aErrorsBasket['invoice_firstName'] = _e(SiteTranslations::get('site_enter_your_first_name')) . ' (' . _e(SiteTranslations::get('site_invoice_details')) . ').';
            }
            if (!OrderManager::getBasket()
                ->isPropValid("invoice_lastName")) {
                $aErrorsBasket['invoice_lastName'] = _e(SiteTranslations::get('site_enter_your_last_name')) . ' (' . _e(SiteTranslations::get('site_invoice_details')) . ').';
            }
            if (!OrderManager::getBasket()
                ->isPropValid("invoice_address")) {
                $aErrorsBasket['invoice_address'] = _e(SiteTranslations::get('site_enter_your_street_name')) . ' (' . _e(SiteTranslations::get('site_invoice_details')) . ').';
            }
            if (!OrderManager::getBasket()
                ->isPropValid("invoice_houseNumber")) {
                $aErrorsBasket['invoice_houseNumber'] = _e(SiteTranslations::get('site_enter_valid_house_number')) . ' (' . _e(SiteTranslations::get('site_invoice_details')) . ').';
            }
            if (!OrderManager::getBasket()
                ->isPropValid("invoice_postalCode")) {
                $aErrorsBasket['invoice_postalCode'] = _e(SiteTranslations::get('site_enter_your_postal_code')) . ' (' . _e(SiteTranslations::get('site_invoice_details')) . ').';
            }
            if (!OrderManager::getBasket()
                ->isPropValid("invoice_city")) {
                $aErrorsBasket['invoice_city'] = _e(SiteTranslations::get('site_enter_your_city')) . ' (' . _e(SiteTranslations::get('site_invoice_details')) . ').';
            }
            if (!OrderManager::getBasket()
                ->isPropValid("delivery_gender")) {
                $aErrorsBasket['delivery_gender'] = _e(SiteTranslations::get('site_select_gender')) . ' (' . _e(SiteTranslations::get('site_delivery_details')) . ').';
            }
            if (!OrderManager::getBasket()
                ->isPropValid("delivery_firstName")) {
                $aErrorsBasket['delivery_firstName'] = _e(SiteTranslations::get('site_enter_your_first_name')) . ' (' . _e(SiteTranslations::get('site_delivery_details')) . ').';
            }
            if (!OrderManager::getBasket()
                ->isPropValid("delivery_lastName")) {
                $aErrorsBasket['delivery_lastName'] = _e(SiteTranslations::get('site_enter_your_last_name')) . ' (' . _e(SiteTranslations::get('site_delivery_details')) . ').';
            }
            if (!OrderManager::getBasket()
                ->isPropValid("delivery_address")) {
                $aErrorsBasket['delivery_address'] = _e(SiteTranslations::get('site_enter_your_street_name')) . ' (' . _e(SiteTranslations::get('site_delivery_details')) . ').';
            }
            if (!OrderManager::getBasket()
                ->isPropValid("delivery_houseNumber")) {
                $aErrorsBasket['delivery_houseNumber'] = _e(SiteTranslations::get('site_enter_valid_house_number')) . ' (' . _e(SiteTranslations::get('site_delivery_details')) . ').';
            }
            if (!OrderManager::getBasket()
                ->isPropValid("delivery_postalCode")) {
                $aErrorsBasket['delivery_postalCode'] = _e(SiteTranslations::get('site_enter_your_postal_code')) . ' (' . _e(SiteTranslations::get('site_delivery_details')) . ').';
            }
            if (!OrderManager::getBasket()
                ->isPropValid("delivery_city")) {
                $aErrorsBasket['delivery_city'] = _e(SiteTranslations::get('site_enter_your_city')) . ' (' . _e(SiteTranslations::get('site_delivery_details')) . ').';
            }
            if (!OrderManager::getBasket()
                ->isPropValid("deliveryMethodId")) {
                $aErrorsBasket['deliveryMethodId'] = _e(SiteTranslations::get('site_delivery_method_not_accepted'));
            }
            if (!OrderManager::getBasket()
                ->isPropValid("paymentMethodId")) {
                $aErrorsBasket['paymentMethodId'] = _e(SiteTranslations::get('site_payment_method_not_accepted'));
            }
            if (!OrderManager::getBasket()
                ->isPropValid("deliveryPaymentMethodCombination")) {
                $aErrorsBasket['deliveryPaymentMethodCombination'] = _e(SiteTranslations::get('site_combiantion_delivery_payment_not_accepted'));
            }
            Debug::logError(
                "",
                "Frontend Order module php validate error",
                __FILE__,
                __LINE__,
                "Tried to save an Order with wrong values despite javascript check.<br />" . _d($_POST, 1, 1) . _d(OrderManager::getBasket(), 1, 1),
                Debug::LOG_IN_EMAIL
            );
        }
    }

    # get page by urlPath (/winkelwagen/bestellen)
    $oPage = PageManager::getPageByUrlPath(getCurrentUrlPath());

    if (empty($oPage) || !$oPage->online) {
        showHttpError('404');
    }

    $oPageLayout->sWindowTitle     = $oPage->getWindowTitle();
    $oPageLayout->sMetaDescription = $oPage->getMetaDescription();
    $oPageLayout->sMetaKeywords    = $oPage->getMetaKeywords();
    $oPageLayout->generateCustomCrumblePath($oPage->getCrumbles());
    $oPageLayout->bIndexable = $oPage->isIndexable();

    $oPageLayout->sViewPath = getSiteView('order', 'orders');

    $bWithLogin = false;
    if (empty(Customer::getCurrent())) {
        $bWithLogin = true;
    }
} # for default text pages
elseif (getCurrentUrlPath() == PageManager::getPageByName('shoppingcart_order_placed')->getUrlPath()) {
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
} # basket
else {
    # add a OrderProduct to the basket
    if (http_post("action") == 'addProductToBasket') {
        $oResObj          = new stdClass(); //standard class for json feedback
        $oResObj->success = false;

        # check for required values
        if (!is_numeric(http_post('catalogProductId'))) {
            $oResObj->orderStatusUpdate = _e(SiteTranslations::get('site_can_not_add_product_try_later'));
            if ($bAjax) {
                die(json_encode($oResObj));
            }
            $_SESSION['orderStatusUpdate'] = $oResObj->orderStatusUpdate; //save status update into session
            http_redirect(getBaseUrl() . '/' . http_get('controller'));
        }

        # get the CatalogProduct
        $oCatalogProduct = CatalogProductManager::getProductById(http_post('catalogProductId'));

        if ($oCatalogProduct->getProductType()->withSizes) {
            $oCatalogProductSize = CatalogProductSizeManager::getProductSizeById(http_post('catalogProductSizeId'));
        } else {
            $oCatalogProductSize = CatalogProductSizeManager::getProductSizeById(CatalogProductSize::sizeId_nosize);
        }

        if ($oCatalogProduct->getProductType()->withColors) {
            $oCatalogProductColor = CatalogProductColorManager::getProductColorById(http_post('catalogProductColorId'));
        } else {
            $oCatalogProductColor = CatalogProductColorManager::getProductColorById(CatalogProductColor::colorId_nocolor);
        }

        // check if product, color and size and stock is available
        if (!empty($oCatalogProduct) && !empty($oCatalogProductSize) && !empty($oCatalogProductColor)) {
            $oSizeColorRelation = CatalogProductSizeColorRelationManager::getCatalogProductSizeColorRelation($oCatalogProduct->catalogProductId, $oCatalogProductSize->catalogProductSizeId, $oCatalogProductColor->catalogProductColorId);
        }

        // size color relation
        if (empty($oSizeColorRelation)) {
            if ($oCatalogProduct->getProductType()->withSizes && $oCatalogProduct->getProductType()->withColors) {
                $oResObj->orderStatusUpdate = _e(SiteTranslations::get('site_select_size_color_amount'));
            } elseif ($oCatalogProduct->getProductType()->withSizes) {
                $oResObj->orderStatusUpdate = _e(SiteTranslations::get('site_select_size_amount'));
            } elseif ($oCatalogProduct->getProductType()->withColors) {
                $oResObj->orderStatusUpdate = _e(SiteTranslations::get('site_select_color_amount'));
            }
            if ($bAjax) {
                die(json_encode($oResObj));
            }
            $_SESSION['orderStatusUpdate'] = $oResObj->orderStatusUpdate; //save status update into session
            http_redirect(getBaseUrl() . '/' . http_get('controller'));
        }

        # define the OrderProduct

        $oOrderProduct = new OrderProduct();
        $oOrderProduct->setOrderProduct($oCatalogProduct, $oSizeColorRelation);

        // get possible existing order product
        $oExistingOrderProduct = OrderManager::getBasket()
            ->getProduct($oCatalogProduct->catalogProductId, $oCatalogProductSize->catalogProductSizeId, $oCatalogProductColor->catalogProductColorId);

        $iAmount = ceil(abs(http_post('amount', 1)));
        if (is_numeric($iAmount)) {
            $oOrderProduct->amount = $iAmount;
        }

        // check new amount also with old amount
        $iNewAmount = ($oExistingOrderProduct ? $oExistingOrderProduct->amount : 0) + $oOrderProduct->amount;

        if ($oSizeColorRelation->stock !== null && $iNewAmount > $oSizeColorRelation->stock) {
            $oOrderProduct->amount      = $oSizeColorRelation->stock;
            $oResObj->orderStatusUpdate = _e(SiteTranslations::get('site_max_stock_of')) . ' ' . $oOrderProduct->amount . ' ' . _e(SiteTranslations::get('site_products_is_added'));
            if (!$bAjax) {
                $_SESSION['orderStatusUpdate'] = $oResObj->orderStatusUpdate; //save status update into session
            }
            // not so many in stock, reset product in order with max amount
            OrderManager::getBasket()
                ->unsetProduct($oSizeColorRelation->catalogProductId, $oSizeColorRelation->catalogProductSizeId, $oSizeColorRelation->catalogProductColorId);
        }

        # add OrderProduct to basket if valid
        if ($oOrderProduct->isValid()) {

            OrderManager::getBasket()
                ->setProduct($oOrderProduct);
            # calculates  the discount again
            OrderManager::getBasket()
                ->getDiscount(Settings::get('taxIncluded'));

            OrderManager::commit();

            # die with object if successful
            if ($bAjax) {
                $oResObj->success = true;
                if (empty($oResObj->orderStatusUpdate)) {
                    $oResObj->orderStatusUpdate = _e(SiteTranslations::get('site_product_added_to_shopping_cart'));
                }
                if (!$bAjax && empty($_SESSION['orderStatusUpdate'])) {
                    $_SESSION['orderStatusUpdate'] = $oResObj->orderStatusUpdate; //save status update into session
                }
                $oResObj->countProducts = OrderManager::getBasket()
                    ->countProducts();
                ob_start();
                include getSiteSnippet('ordersMiniBasketContent', 'orders');
                $oResObj->ordersMiniBasketContent = ob_get_clean();
                die(json_encode($oResObj));
            }
        } else {

            $oResObj->orderStatusUpdate = _e(SiteTranslations::get('site_can_not_add_product_try_later'));

            if ($bAjax) {
                die(json_encode($oResObj));
            }
            $_SESSION['orderStatusUpdate'] = $oResObj->orderStatusUpdate; //save status update into session
        }
        http_redirect(getBaseUrl() . '/' . http_get('controller'));
    }

    # remove product from basket
    if (isset($_POST["removeProductFromBasket"])) {
        $oResObj          = new stdClass(); //standard class for json feedback
        $oResObj->success = false;

        # check for required values
        if (!is_numeric(http_post('catalogProductId')) || !is_numeric(http_post('catalogProductSizeId')) || !is_numeric(http_post('catalogProductColorId'))) {
            $oResObj->orderStatusUpdate = _e(SiteTranslations::get('site_can_not_delete_product_try_later'));
            if ($bAjax) {
                die(json_encode($oResObj));
            }
            $_SESSION['orderStatusUpdate'] = $oResObj->orderStatusUpdate; //save status update into session
            http_redirect(getBaseUrl() . '/' . http_get('controller'));
        }

        # remove the product from the basket
        OrderManager::getBasket()
            ->unsetProduct(http_post('catalogProductId'), http_post('catalogProductSizeId'), http_post('catalogProductColorId'));
        $oResObj->countProducts = OrderManager::getBasket()
            ->countProducts();

        # calculate  the discount again
        OrderManager::getBasket()
            ->getDiscount(Settings::get('taxIncluded'));

        OrderManager::commit();

        # die with object if successful
        if ($bAjax) {
            $oResObj->success = true;
            ob_start();
            include getSiteSnippet('ordersMiniBasketContent', 'orders');
            $oResObj->ordersMiniBasketContent = ob_get_clean();

            die(json_encode($oResObj));
        }
        http_redirect(getBaseUrl() . '/' . http_get('controller'));
    }

    # edit amount of a product in the basket
    if (isset($_POST["updateProductAmount"])) {
        $oResObj          = new stdClass(); //standard class for json feedback
        $oResObj->success = false;

        if (!is_numeric(http_post('catalogProductId')) || !is_numeric(http_post('catalogProductSizeId')) || !is_numeric(http_post('catalogProductColorId')) || !is_numeric(http_post('amount')) || http_post('amount') < 0) {
            $_SESSION['orderStatusUpdate'] = _e(SiteTranslations::get('site_can_not_change_amount_try_later')); //save status update into session

            if ($bAjax) {
                $oResObj->orderStatusUpdate = _e(SiteTranslations::get('site_can_not_change_amount_try_later'));
                die(json_encode($oResObj));
            }

            http_redirect(getBaseUrl() . '/' . http_get('controller'));
        }

        if (http_post('amount') == 0) {
            # remove the product from the basket if the amount is 0
            OrderManager::getBasket()
                ->unsetProduct(http_post('catalogProductId'), http_post('catalogProductSizeId'), http_post('catalogProductColorId'));
        } else {
            $oCatalogProduct = CatalogProductManager::getProductById(http_post('catalogProductId'));
            if ($oCatalogProduct->getProductType()->withSizes) {
                $oCatalogProductSize = CatalogProductSizeManager::getProductSizeById(http_post('catalogProductSizeId'));
            } else {
                $oCatalogProductSize = CatalogProductSizeManager::getProductSizeById(CatalogProductSize::sizeId_nosize);
            }

            if ($oCatalogProduct->getProductType()->withColors) {
                $oCatalogProductColor = CatalogProductColorManager::getProductColorById(http_post('catalogProductColorId'));
            } else {
                $oCatalogProductColor = CatalogProductColorManager::getProductColorById(CatalogProductColor::colorId_nocolor);
            }

            // check if product, color and size and stock is available
            if (!empty($oCatalogProduct) && !empty($oCatalogProductSize) && !empty($oCatalogProductColor)) {
                $oSizeColorRelation = CatalogProductSizeColorRelationManager::getCatalogProductSizeColorRelation($oCatalogProduct->catalogProductId, $oCatalogProductSize->catalogProductSizeId, $oCatalogProductColor->catalogProductColorId);
            }

            if ($oSizeColorRelation) {
                $iAmount = ceil(abs(http_post('amount', 0)));
                if (is_numeric($iAmount)) {
                    if ($oSizeColorRelation->stock !== null && $iAmount > $oSizeColorRelation->stock) {
                        $iAmount                       = $oSizeColorRelation->stock;
                        $_SESSION['orderStatusUpdate'] = _e(SiteTranslations::get('site_max_stock_of')) . ' ' . $iAmount . ' ' . _e(SiteTranslations::get('site_products_is_added')); //save status update into session
                        $oResObj->orderStatusUpdate    = _e(SiteTranslations::get('site_max_stock_of')) . ' ' . $iAmount . ' ' . _e(SiteTranslations::get('site_products_is_added'));
                    }
                } else {
                    $iAmount = 0;
                }
            } else {
                $_SESSION['orderStatusUpdate'] = _e(SiteTranslations::get('site_can_not_change_amount')); //save status update into session
                $oResObj->orderStatusUpdate    = _e(SiteTranslations::get('site_can_not_change_amount'));
            }

            # update the OrderProduct's amount
            OrderManager::getBasket()
                ->setProductAmount(http_post('catalogProductId'), http_post('catalogProductSizeId'), http_post('catalogProductColorId'), $iAmount);
        }

        # calculates  the discount again
        OrderManager::getBasket()
            ->getDiscount(Settings::get('taxIncluded'));

        OrderManager::commit();

        # die with object if Ajax
        if ($bAjax) {
            $oResObj->success                          = true;
            $oResObj->productId                        = 'product_' . http_post('catalogProductId') . '_' . http_post('catalogProductSizeId') . '_' . http_post('catalogProductColorId');
            $oResObj->productOriginalId                = 'product_original_' . http_post('catalogProductId') . '_' . http_post('catalogProductSizeId') . '_' . http_post('catalogProductColorId');
            $oResObj->formattedProductOriginalSubtotal = decimal2valuta(
                OrderManager::getBasket()
                    ->getProduct(http_post('catalogProductId'), http_post('catalogProductSizeId'), http_post('catalogProductColorId'))
                    ->getSubTotalOriginalPrice(true)
            );
            $oResObj->formattedProductSubtotal         = decimal2valuta(
                OrderManager::getBasket()
                    ->getProduct(http_post('catalogProductId'), http_post('catalogProductSizeId'), http_post('catalogProductColorId'))
                    ->getSubTotalPrice(true)
            );
            $oResObj->formattedSubtotal                = decimal2valuta(
                OrderManager::getBasket()
                    ->getSubtotal(true)
            );
            $oResObj->formattedTotalPrice              = decimal2valuta(
                OrderManager::getBasket()
                    ->getTotal(false)
            );
            $oResObj->formattedTotalPriceWithTax       = decimal2valuta(
                OrderManager::getBasket()
                    ->getTotal(true)
            );
            $oResObj->formattedTotalTaxPrice           = decimal2valuta(
                OrderManager::getBasket()
                    ->getBTW()
            );
            $oResObj->formattedTotalDiscount           = decimal2valuta(
                OrderManager::getBasket()
                    ->getDiscount(true)
            );
            $oResObj->formattedDeliveryPriceWithTax    = decimal2valuta(
                OrderManager::getBasket()
                    ->getDeliveryCosts(true)
            );
            die(json_encode($oResObj));
        }

        http_redirect(getBaseUrl() . '/' . http_get('controller'));
    }

    # update the DeliveryMethod in the basket (via ajax)
    if (http_post("action") == 'updateDeliveryMethod') {
        $oResObj          = new stdClass(); //standard class for json feedback
        $oResObj->success = false;

        # check for required fields
        if (!is_numeric(http_post('deliveryMethodId'))) {
            $oResObj->orderStatusUpdate = _e(SiteTranslations::get('site_can_not_change_shipping_method_try_later'));
            if ($bAjax) {
                die(json_encode($oResObj));
            }
            $_SESSION['orderStatusUpdate'] = $oResObj->orderStatusUpdate; //save status update into session
            http_redirect(PageManager::getPageByName('shoppingcart_order')->getBaseUrlPath());
        }

        # get the DeliveryMethod
        $oDeliveryMethod = DeliveryMethodManager::getDeliveryMethodById(http_post('deliveryMethodId'));

        $oPaymentMethod = null;
        if ($aPaymentMethods = $oDeliveryMethod->getPaymentMethods()) {
            $oPaymentMethod = $aPaymentMethods[0];
        }

        # see if the DeliveryMethod exists
        if (empty($oDeliveryMethod)) {
            $oResObj->orderStatusUpdate = _e(SiteTranslations::get('site_can_not_change_delivery_method_try_later'));
            if ($bAjax) {
                die(json_encode($oResObj));
            }
            $_SESSION['orderStatusUpdate'] = $oResObj->orderStatusUpdate; //save status update into session
            http_redirect(PageManager::getPageByName('shoppingcart_order')->getBaseUrlPath());
        }

        # set the DeliveryMethod
        OrderManager::getBasket()
            ->setDeliveryMethod($oDeliveryMethod);
        OrderManager::getBasket()
            ->setPaymentMethod($oPaymentMethod);

        OrderManager::commit();

        # die with object if Ajax
        if ($bAjax) {
            $oResObj->success                       = true;
            $oResObj->formattedDeliveryPriceWithTax = decimal2valuta(
                OrderManager::getBasket()
                    ->getDeliveryCosts(true)
            );
            $oResObj->formattedTotalPriceWithoutTax = decimal2valuta(
                OrderManager::getBasket()
                    ->getTotal(false)
            );
            $oResObj->formattedTotalPriceWithTax    = decimal2valuta(
                OrderManager::getBasket()
                    ->getTotal(true)
            );
            $oResObj->formattedTotalTaxPrice        = decimal2valuta(
                OrderManager::getBasket()
                    ->getBTW()
            );
            $oResObj->deliveryMethodId              = OrderManager::getBasket()->deliveryMethodId;
            $oResObj->paymentMethodId               = OrderManager::getBasket()->paymentMethodId;
            $oResObj->paymentMethodOptions          = '';
            $oResObj->formattedTotalDiscount        = decimal2valuta(
                OrderManager::getBasket()
                    ->getDiscount(true)
            );
            foreach ($aPaymentMethods as $oPaymentMethod) {
                $oResObj->paymentMethodOptions .= '<option value="' . $oPaymentMethod->paymentMethodId . '" ' . ($oPaymentMethod->paymentMethodId == OrderManager::getBasket()->paymentMethodId ? 'selected' : '') . '>' . _e(
                        $oPaymentMethod->getTranslations()->name
                    ) . '</option>';
            }

            die(json_encode($oResObj));
        }

        http_redirect(getBaseUrl() . '/' . http_get('controller') . '/' . http_get('param1'));
    }

    # update the PaymentMethod in the basket (via ajax)
    if (http_post("action") == 'updatePaymentMethod') {
        $oResObj          = new stdClass(); //standard class for json feedback
        $oResObj->success = false;

        # check for required fields
        if (!is_numeric(http_post('paymentMethodId'))) {
            $oResObj->orderStatusUpdate = _e(SiteTranslations::get('site_can_not_change_payment_method_try_later'));
            if ($bAjax) {
                die(json_encode($oResObj));
            }
            $_SESSION['orderStatusUpdate'] = $oResObj->orderStatusUpdate; //save status update into session
            http_redirect(getBaseUrl() . '/' . http_get('controller') . '/' . http_get('param1'));
        }

        # get the PaymentMethod
        $oPaymentMethod = PaymentMethodManager::getPaymentMethodById(http_post('paymentMethodId'));

        # see if the PaymentMethod exists
        if (empty($oPaymentMethod) || !DeliveryMethodManager::isValidDeliveryMethodPaymentMethodRelation(OrderManager::getBasket()->deliveryMethodId, $oPaymentMethod->paymentMethodId)) {
            $oResObj->orderStatusUpdate = _e(SiteTranslations::get('site_can_not_change_payment_method_try_later'));
            if ($bAjax) {
                die(json_encode($oResObj));
            }
            $_SESSION['orderStatusUpdate'] = $oResObj->orderStatusUpdate; //save status update into session
            http_redirect(getBaseUrl() . '/' . http_get('controller') . '/' . http_get('param1'));
        }

        # set the PaymentMethod
        OrderManager::getBasket()
            ->setPaymentMethod($oPaymentMethod);

        OrderManager::commit();

        # die with object if Ajax
        if ($bAjax) {
            $oResObj->success                      = true;
            $oResObj->formattedPaymentPriceWithTax = decimal2valuta(
                OrderManager::getBasket()
                    ->getPaymentCosts(true)
            );
            $oResObj->formattedTotalPrice          = decimal2valuta(
                OrderManager::getBasket()
                    ->getTotal(false)
            );
            $oResObj->formattedTotalPriceWithTax   = decimal2valuta(
                OrderManager::getBasket()
                    ->getTotal(true)
            );
            $oResObj->formattedTotalTaxPrice       = decimal2valuta(
                OrderManager::getBasket()
                    ->getBTW()
            );
            die(json_encode($oResObj));
        }

        http_redirect(getBaseUrl() . '/' . http_get('controller') . '/' . http_get('param1'));
    }

    if (http_post("action") == 'applyDiscount') {
        $oResObj          = new stdClass(); //standard class for json feedback
        $oResObj->success = false;

        # check for required fields
        if (!http_post("couponCode")) {
            $oResObj->orderStatusUpdate = _e(SiteTranslations::get('site_fill_in_a_coupon_code'));
            if ($bAjax) {
                die(json_encode($oResObj));
            }
            $_SESSION['orderStatusUpdate'] = $oResObj->orderStatusUpdate; //save status update into session
            http_redirect(getBaseUrl() . '/' . http_get('controller') . '/' . http_get('param1'));
        }

        $sCouponCode = http_post('couponCode');

        #gets the coupon from database and verify if its valid
        $oCoupon = CouponManager::getCouponByCode($sCouponCode);

        if (empty($oCoupon) || !$oCoupon->isRedeemable()) {
            $oResObj->orderStatusUpdate = _e(SiteTranslations::get('site_coupon_not_valid'));

            if ($bAjax) {
                die(json_encode($oResObj));
            }
            $_SESSION['orderStatusUpdate'] = $oResObj->orderStatusUpdate; //save status update into session
            http_redirect(getBaseUrl() . '/' . http_get('controller') . '/' . http_get('param1'));
        } else {
            OrderManager::getBasket()
                ->setDiscountCoupon($oCoupon);
            $oResObj->orderStatusUpdate = _e(SiteTranslations::get('site_discount_code_accepted'));
        }

        OrderManager::commit();

        # die with object if Ajax
        if ($bAjax) {
            $oResObj->success                    = true;
            $oResObj->formattedTotalPrice        = decimal2valuta(
                OrderManager::getBasket()
                    ->getTotal(false)
            );
            $oResObj->formattedTotalPriceWithTax = decimal2valuta(
                OrderManager::getBasket()
                    ->getTotal(true)
            );
            $oResObj->formattedTotalTaxPrice     = decimal2valuta(
                OrderManager::getBasket()
                    ->getBTW()
            );
            $oResObj->formattedTotalDiscount     = decimal2valuta(
                OrderManager::getBasket()
                    ->getDiscount(true)
            );
            $oResObj->discountApplied            = !empty(OrderManager::getBasket()->couponId);
            die(json_encode($oResObj));
        }

        http_redirect(getBaseUrl() . '/' . http_get('controller') . '/' . http_get('param1'));
    }

    if (http_post("action") == 'removeDiscount') {

        OrderManager::getBasket()
            ->setDiscountCoupon(null);
        OrderManager::commit();

        # die with object if Ajax
        if ($bAjax) {
            $oResObj                             = new stdClass(); //standard class for json feedback
            $oResObj->success                    = true;
            $oResObj->formattedTotalPrice        = decimal2valuta(
                OrderManager::getBasket()
                    ->getTotal(false)
            );
            $oResObj->formattedTotalPriceWithTax = decimal2valuta(
                OrderManager::getBasket()
                    ->getTotal(true)
            );
            $oResObj->formattedTotalTaxPrice     = decimal2valuta(
                OrderManager::getBasket()
                    ->getBTW()
            );
            $oResObj->formattedTotalDiscount     = decimal2valuta(
                OrderManager::getBasket()
                    ->getDiscount(true)
            );
            $oResObj->orderStatusUpdate          = _e(SiteTranslations::get('site_discount_removed'));
            die(json_encode($oResObj));
        }

        http_redirect(getBaseUrl() . '/' . http_get('controller') . '/' . http_get('param1'));
    }

    # get page by urlPath (/winkelwagen)
    $oPage = PageManager::getPageByUrlPath(getCurrentUrlPath());

    if (empty($oPage) || !$oPage->online) {
        showHttpError('404');
    }

    $oPageLayout->sWindowTitle     = $oPage->getWindowTitle();
    $oPageLayout->sMetaDescription = $oPage->getMetaDescription();
    $oPageLayout->sMetaKeywords    = $oPage->getMetaKeywords();
    $oPageLayout->generateCustomCrumblePath($oPage->getCrumbles());
    $oPageLayout->bIndexable = $oPage->isIndexable();

    $oPageLayout->sViewPath = getSiteView('basket', 'orders');
}

OrderManager::commit();
# Get data for this controller
# Include the template
include_once getSiteView('layout');
