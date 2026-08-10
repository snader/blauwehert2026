<?php

class Order extends Model
{

    public  $orderId               = null;
    public  $customerId            = null;
    public  $email;
    public  $invoice_companyName   = null;
    public  $invoice_gender;
    public  $invoice_firstName;
    public  $invoice_insertion;
    public  $invoice_lastName;
    public  $invoice_address;
    public  $invoice_houseNumber;
    public  $invoice_houseNumberAddition;
    public  $invoice_postalCode;
    public  $invoice_city;
    public  $invoice_phone         = null;
    public  $delivery_companyName  = null;
    public  $delivery_gender;
    public  $delivery_firstName;
    public  $delivery_insertion;
    public  $delivery_lastName;
    public  $delivery_address;
    public  $delivery_houseNumber;
    public  $delivery_houseNumberAddition;
    public  $delivery_postalCode;
    public  $delivery_city;
    public  $deliveryMethodId;
    public  $deliveryMethodName    = null;
    private $deliveryPrice         = null; // delivery price without tax
    private $deliveryTaxPercentage = null; // tax percentage of the delivery method
    public  $paymentMethodId;
    public  $paymentMethodName     = null;
    public  $totalDiscount         = 0;
    public  $taxIncluded           = 0;
    public  $couponId              = null;
    private $paymentPrice          = null; // payment price without tax
    private $paymentTaxPercentage  = null; // tax percentage of the payment method
    private $totalPriceWithoutTax  = null; // total price without tax
    private $totalPriceWithTax     = null; // total price with tax
    public  $status                = null;
    public  $created               = null;
    public  $modified              = null;
    private $oCustomer             = null; // association with Customer class
    private $aProducts             = null; // association with OrderProduct class
    private $oDeliveryMethod       = null; // association with DeliveryMethod class
    private $oPaymentMethod        = null; // association with PaymentMethod class
    private $oStatus               = null; // association with OrderStatus class
    private $aStatusHistory        = null; // association with OrderStatus class
    private $oPayment              = null; // association with OrderPayment class
    private $aPaymentHistory       = null; // association with OrderPayment class
    private $oCoupon;

    public function __construct($aData = [], $bStripTags = true)
    {
        // set default delivery method
        if (empty($this->deliveryMethodId)) {
            $aDeliveryMethods = DeliveryMethodManager::getAllDeliveryMethods();
            if (!empty($aDeliveryMethods)) {
                $this->deliveryMethodId = $aDeliveryMethods[0]->deliveryMethodId;
            }
        }
        // set default payment method
        if (!empty($this->deliveryMethodId) && empty($this->paymentMethodId)) {
            $aPaymentMethods = $this->getDeliveryMethod()
                ->getPaymentMethods('all');
            if (!empty($aPaymentMethods)) {
                $this->paymentMethodId = $aPaymentMethods[0]->paymentMethodId;
            }
        }

        parent::__construct($aData, $bStripTags);
    }

    /**
     * validate object
     */
    public function validate()
    {
        if (!empty($this->customerId) && !is_numeric($this->customerId)) {
            $this->setPropInvalid('customerId');
        }
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->setPropInvalid('email');
        }
        if (empty($this->invoice_gender)) {
            $this->setPropInvalid('invoice_gender');
        }
        if (empty($this->invoice_firstName)) {
            $this->setPropInvalid('invoice_firstName');
        }
        if (empty($this->invoice_lastName)) {
            $this->setPropInvalid('invoice_lastName');
        }
        if (empty($this->invoice_address)) {
            $this->setPropInvalid('invoice_address');
        }
        if (!is_numeric($this->invoice_houseNumber)) {
            $this->setPropInvalid('invoice_houseNumber');
        }
        if (empty($this->invoice_postalCode)) {
            $this->setPropInvalid('invoice_postalCode');
        }
        if (empty($this->invoice_city)) {
            $this->setPropInvalid('invoice_city');
        }
        if (empty($this->delivery_gender)) {
            $this->setPropInvalid('delivery_gender');
        }
        if (empty($this->delivery_firstName)) {
            $this->setPropInvalid('delivery_firstName');
        }
        if (empty($this->delivery_lastName)) {
            $this->setPropInvalid('delivery_lastName');
        }
        if (empty($this->delivery_address)) {
            $this->setPropInvalid('delivery_address');
        }
        if (!is_numeric($this->delivery_houseNumber)) {
            $this->setPropInvalid('delivery_houseNumber');
        }
        if (empty($this->delivery_postalCode)) {
            $this->setPropInvalid('delivery_postalCode');
        }
        if (empty($this->delivery_city)) {
            $this->setPropInvalid('delivery_city');
        }
        if (!empty($this->deliveryPrice) && (!is_numeric($this->deliveryPrice) || $this->deliveryPrice < 0)) {
            $this->setPropInvalid('deliveryPrice');
        }
        if (!empty($this->deliveryTaxPercentage) && !is_numeric($this->deliveryTaxPercentage)) {
            $this->setPropInvalid('deliveryTaxPercentage');
        }
        if (!empty($this->paymentPrice) && (!is_numeric($this->paymentPrice) || $this->paymentPrice < 0)) {
            $this->setPropInvalid('paymentPrice');
        }
        if (!empty($this->paymentTaxPercentage) && !is_numeric($this->paymentTaxPercentage)) {
            $this->setPropInvalid('paymentTaxPercentage');
        }
        if (!empty($this->totalPriceWithoutTax) && (!is_numeric($this->totalPriceWithoutTax) || $this->totalPriceWithoutTax < 0)) {
            $this->setPropInvalid('totalPriceWithoutTax');
        }
        if (!empty($this->totalPriceWithTax) && (!is_numeric($this->totalPriceWithTax) || $this->totalPriceWithTax < 0)) {
            $this->setPropInvalid('totalPriceWithTax');
        }
        if (!DeliveryMethodManager::isValidDeliveryMethodPaymentMethodRelation($this->deliveryMethodId, $this->paymentMethodId)) {
            $this->setPropInvalid('deliveryPaymentMethodCombination');
        }
    }

    /**
     * set a Customer to this Order (this also fills the email and invoice properties if they aren't filed)
     *
     * @param Customer $oCustomer
     */
    public function setCustomer(Customer $oCustomer)
    {
        $this->customerId                  = $oCustomer->customerId;
        $this->email                       = $oCustomer->email;
        $this->invoice_companyName         = (empty($this->invoice_companyName) ? $oCustomer->companyName : $this->invoice_companyName);
        $this->invoice_gender              = (empty($this->invoice_gender) ? $oCustomer->gender : $this->invoice_gender);
        $this->invoice_firstName           = (empty($this->invoice_firstName) ? $oCustomer->firstName : $this->invoice_firstName);
        $this->invoice_insertion           = (empty($this->invoice_insertion) ? $oCustomer->insertion : $this->invoice_insertion);
        $this->invoice_lastName            = (empty($this->invoice_lastName) ? $oCustomer->lastName : $this->invoice_lastName);
        $this->invoice_address             = (empty($this->invoice_address) ? $oCustomer->address : $this->invoice_address);
        $this->invoice_houseNumber         = (empty($this->invoice_houseNumber) ? $oCustomer->houseNumber : $this->invoice_houseNumber);
        $this->invoice_houseNumberAddition = (empty($this->invoice_houseNumberAddition) ? $oCustomer->houseNumberAddition : $this->invoice_houseNumberAddition);
        $this->invoice_postalCode          = (empty($this->invoice_postalCode) ? $oCustomer->postalCode : $this->invoice_postalCode);
        $this->invoice_city                = (empty($this->invoice_city) ? $oCustomer->city : $this->invoice_city);
        $this->invoice_phone               = (empty($this->invoice_phone) ? $oCustomer->phone : $this->invoice_phone);
        $this->oCustomer                   = $oCustomer;
    }

    /**
     * set the invoice info to the delivery (make them the same)
     */
    public function setInvoiceToDelivery()
    {
        $this->delivery_companyName         = $this->invoice_companyName;
        $this->delivery_gender              = $this->invoice_gender;
        $this->delivery_firstName           = $this->invoice_firstName;
        $this->delivery_insertion           = $this->invoice_insertion;
        $this->delivery_lastName            = $this->invoice_lastName;
        $this->delivery_address             = $this->invoice_address;
        $this->delivery_houseNumber         = $this->invoice_houseNumber;
        $this->delivery_houseNumberAddition = $this->invoice_houseNumberAddition;
        $this->delivery_postalCode          = $this->invoice_postalCode;
        $this->delivery_city                = $this->invoice_city;
    }

    /**
     * sets the discount coupon applied to this order
     *
     * @param Coupon $oCoupon
     */
    public function setDiscountCoupon($oCoupon)
    {
        $this->oCoupon = $oCoupon;

        if ($oCoupon === null) {
            $this->couponId      = null;
            $this->totalDiscount = 0.0;
        } else {
            $this->couponId      = $oCoupon->couponId;
            $this->totalDiscount = $this->getDiscount(Settings::get('taxIncluded'));
        }
    }

    /**
     * returns the discount coupon applied to this order
     *
     * @return Coupon
     */
    public function getDiscountCoupon()
    {
        if ($this->oCoupon === null) {
            $this->oCoupon = CouponManager::getCouponById($this->couponId);
        }

        return $this->oCoupon;
    }

    /**
     * get the related Customer
     *
     * @return Customer
     */
    public function getCustomer()
    {
        if ($this->oCustomer === null) {
            $this->oCustomer = CustomerManager::getCustomerById($this->customerId);
        }

        return $this->oCustomer;
    }

    /**
     * add a OrderProduct to this object
     *
     * @param OrderProduct $oOrderProduct
     *
     * @return bool
     */
    public function setProduct(OrderProduct $oOrderProduct)
    {
        if ($this->aProducts === null) {
            $this->aProducts = [];
        }

        $sKey = $oOrderProduct->catalogProductId . '-' . $oOrderProduct->catalogProductSizeId . '-' . $oOrderProduct->catalogProductColorId;

        if (!empty($this->aProducts[$sKey])) {
            $oOrderProduct->amount += $this->aProducts[$sKey]->amount;
        }
        $this->aProducts[$sKey] = $oOrderProduct;
    }

    /**
     * remove a OrderProduct from this object by catalogProductId
     *
     * @param int $iCatalogProductId
     */
    public function unsetProduct($iCatalogProductId, $iProductSizeId, $iProductColorId)
    {
        $sKey = $iCatalogProductId . '-' . $iProductSizeId . '-' . $iProductColorId;
        unset($this->aProducts[$sKey]);
    }

    /**
     * edit a OrderProduct's amount by catalogProductId
     *
     * @param int $iCatalogProductId
     * @param int $iAmount
     *
     * @return bool
     */
    public function setProductAmount($iCatalogProductId, $iProductSizeId, $iProductColorId, $iAmount)
    {
        $sKey = $iCatalogProductId . '-' . $iProductSizeId . '-' . $iProductColorId;

        # check if an OrderProduct exists with the defined catalogProductId
        if (empty($this->aProducts[$sKey])) {
            return false; //return false if the OrderProduct doesn't exist
        } else {
            $this->aProducts[$sKey]->amount = $iAmount;

            return true;
        }
    }

    /**
     * get and order product
     *
     * @param int $iCatalogProductId
     * @param int $iProductSizeId
     * @param int $iProductColorId
     *
     * @return OrderProduct
     */
    public function getProduct($iCatalogProductId, $iProductSizeId, $iProductColorId)
    {
        if (isset($this->aProducts[$iCatalogProductId . '-' . $iProductSizeId . '-' . $iProductColorId])) {
            return $this->aProducts[$iCatalogProductId . '-' . $iProductSizeId . '-' . $iProductColorId];
        }
    }

    /**
     * get the related OrderProduct objects
     *
     * @return OrderProduct[]
     */
    public function getProducts()
    {
        if ($this->aProducts === null) {
            $this->aProducts = OrderProductManager::getProductsByOrderId($this->orderId);
        }

        return $this->aProducts;
    }

    /**
     * count the amount of products in this Order (OrderProduct x amount)
     *
     * @return int
     */
    public function countProducts()
    {
        $iAmount = 0;
        foreach ($this->getProducts() as $oProduct) {
            $iAmount += $oProduct->amount;
        }

        return $iAmount;
    }

    /**
     * set $this->oDeliveryMethod
     *
     * @param DeliveryMethod $oDeliveryMethod
     */
    public function setDeliveryMethod(DeliveryMethod $oDeliveryMethod)
    {
        $this->deliveryMethodId   = $oDeliveryMethod->deliveryMethodId;
        $this->deliveryMethodName = $oDeliveryMethod->getTranslations()->name;
        $this->deliveryPrice      = $oDeliveryMethod->price;
        //$this->deliveryTaxPercentage = $oDeliveryMethod->getTaxPercentage();
        $this->oDeliveryMethod = $oDeliveryMethod;
    }

    /**
     * get the related DeliveryMethod
     *
     * @return DeliveryMethod
     */
    public function getDeliveryMethod()
    {
        if ($this->oDeliveryMethod === null || $this->orderId === null) {
            $this->oDeliveryMethod = DeliveryMethodManager::getDeliveryMethodById($this->deliveryMethodId);
        }

        return $this->oDeliveryMethod;
    }

    /**
     * get the deliveryPrice of this Order
     *
     * @return float
     */
    public function getDeliveryPrice()
    {
        if ($this->deliveryPrice === null || $this->orderId === null) {
            if ($this->getDeliveryMethod()->freeFromPrice === null || $this->getDeliveryMethod()->freeFromPrice > $this->getSubTotal(Settings::get('taxIncluded'))) {
                $this->deliveryPrice = $this->getDeliveryMethod()->price;
            } else {
                $this->deliveryPrice = 0;
            }
        }

        return $this->deliveryPrice;
    }

    /**
     * set $this->oPaymentMethod
     *
     * @param PaymentMethod $oPaymentMethod
     */
    public function setPaymentMethod(PaymentMethod $oPaymentMethod)
    {
        $this->paymentMethodId   = $oPaymentMethod->paymentMethodId;
        $this->paymentMethodName = $oPaymentMethod->getTranslations()->name;
        $this->paymentPrice      = $oPaymentMethod->price;
        //$this->paymentTaxPercentage = $oPaymentMethod->getTaxPercentage();
        $this->oPaymentMethod = $oPaymentMethod;
    }

    /**
     * get the related PaymentPayment
     *
     * @return PaymentPayment
     */
    public function getPaymentMethod()
    {
        if ($this->oPaymentMethod === null || $this->orderId === null) {
            $this->oPaymentMethod = PaymentMethodManager::getPaymentMethodById($this->paymentMethodId);
        }

        return $this->oPaymentMethod;
    }

    /**
     * get the paymentPrice of this Order
     *
     * @return float
     */
    public function getPaymentPrice()
    {
        if ($this->paymentPrice === null || $this->orderId === null) {
            $this->paymentPrice = $this->getPaymentMethod()->price;
        }

        return $this->paymentPrice;
    }

    /**
     * get the latest OrderStatus related to this object
     *
     * @return OrderStatus
     */
    public function getStatus()
    {
        if ($this->oStatus === null) {
            $this->oStatus = OrderStatusManager::getStatusByOrderId($this->orderId);
        }

        return $this->oStatus;
    }

    /**
     * get all OrderStatus objects related to this one
     *
     * @return array OrderStatus
     */
    public function getStatusHistory()
    {
        if ($this->aStatusHistory === null) {
            $this->aStatusHistory = OrderStatusManager::getStatusHistoryByOrderId($this->orderId);
        }

        return $this->aStatusHistory;
    }


    /**
     * get the latest OrderPayment
     *
     * @return OrderPayment
     */
    public function getPayment()
    {
        if ($this->oPayment === null) {
            $this->oPayment = OrderPaymentManager::getPaymentByOrderId($this->orderId);
        }

        return $this->oPayment;
    }

    /**
     * get the OrderPayment objects related to this object
     *
     * @return array OrderPayment
     */
    public function getPaymentHistory()
    {
        if ($this->aPaymentHistory === null) {
            $this->aPaymentHistory = OrderPaymentManager::getPaymentHistoryByOrderId($this->orderId);
        }

        return $this->aPaymentHistory;
    }

    /**
     * process payment status and update order
     *
     * @param int $iPaymentStatus
     */
    public function processPaymentStatus($iPaymentStatus)
    {
        switch ($iPaymentStatus) {
            case OrderPayment::STATUS_ACCEPTED:
                $this->processStatus(OrderStatus::STATUS_PAYED, true, true);
                break;
            default:
                break;
        }
    }

    /**
     * set, save and process orderstatus, handle what needs to be handled and set new status if needed
     *
     * @param int    $iStatus
     * @param bool   $bNotifyCustomer
     * @param bool   $bNotifyClient
     * @param string $sNotificationInfo
     * @param string $sBCCClient send same email, as to client, to BCC address
     */
    public function processStatus($iStatus, $bNotifyCustomer = false, $bNotifyClient = false, $sNotificationInfo = '', $sBCCClient = '',$sCreatedBy = 'global_system')
    {
        $this->status = $iStatus;

        // default templates
        $sTemplateNameCustomer = 'order_status_changed';
        $sTemplateNameClient   = $sTemplateNameCustomer; // same as customer

        if ($this->status == OrderStatus::STATUS_NEW) {
            // save order status
           $this->saveOrderStatus($sCreatedBy);

            // check if the payment not needs to be handles online
            if (!$this->getPaymentMethod()->isOnlinePaymentMethod) {
                OrderManager::updateCatalogProductStock($this); // do this in the payment-handling if there's one
            }
            // process next order status
            if ($this->getTotal() != 0.00) {
                $this->processStatus(OrderStatus::STATUS_AWAITING_PAYMENT);
            } else {
                $this->processStatus(OrderStatus::STATUS_READY_FOR_PROCESSING);
            }

            // send notifications after setting new status to force to email with new status (kind of hack to send `new order template` with other status)
            $this->sendStatusNotifications('order_created_customer', 'order_created_owner', $bNotifyCustomer, $bNotifyClient, $sNotificationInfo, $sBCCClient);
        } elseif ($this->status == OrderStatus::STATUS_PAYED) {
            // save order status
            $this->saveOrderStatus($sCreatedBy);

            // update stock if not yet done
            OrderManager::updateCatalogProductStock($this);

            // send notification
            $this->sendStatusNotifications($sTemplateNameCustomer, $sTemplateNameClient, $bNotifyCustomer, $bNotifyClient, $sNotificationInfo, $sBCCClient);

            // set new status after sending notifications and process next status
            $this->processStatus(OrderStatus::STATUS_READY_FOR_PROCESSING);
        } elseif ($this->status == OrderStatus::STATUS_CANCELED) {
            // save order status
            $this->saveOrderStatus($sCreatedBy);

            // update stock if not yet done
            OrderManager::updateCatalogProductStock($this, false);
            $this->sendStatusNotifications($sTemplateNameCustomer, $sTemplateNameClient, $bNotifyCustomer, $bNotifyClient, $sNotificationInfo, $sBCCClient);
        } else {
            // save order status
            $this->saveOrderStatus($sCreatedBy);
            $this->sendStatusNotifications($sTemplateNameCustomer, $sTemplateNameClient, $bNotifyCustomer, $bNotifyClient, $sNotificationInfo, $sBCCClient);
        }
    }

    /**
     * saves the order status
     */
    private function saveOrderStatus($sCreatedBy = 'global_system'){
        OrderStatusManager::saveStatus(new OrderStatus(['orderId' => $this->orderId, 'status' => $this->status, 'createdBy' => $sCreatedBy]));
    }

    public function getTotal($bWithTaxes = false)
    {
        $fTotal = $this->getSubtotalProducts($bWithTaxes) + $this->getDiscount($bWithTaxes);

        if ($fTotal < 0) {
            $fTotal = 0.0;
        }

        return $fTotal + $this->getDeliveryCosts($bWithTaxes) + $this->getPaymentCosts($bWithTaxes);
    }

    /**
     * get the tax price of the order's total price
     *
     * @return float
     */
    public function getBTW()
    {
        return $this->getTotal(true) - $this->getTotal(false);
    }

    /**
     * get the subtotal price of this Order by adding the prices up of each of the related OrderProduct
     *
     * @param bool $bWithTax
     *
     * @return float
     */
    public function getSubtotalProducts($bWithTax = false, $bIsDiscount = false)
    {
        $fSubTotalPrice = 0;

        foreach ($this->getProducts() as $oProduct) {
            // If the product has a reduced price, I am getting the information to calculate discounts and The setting "Do products with reduce price has discount?" is set to False,
            // then I skip this product in the calculations

            if ($bIsDiscount && Settings::get('reducedPriceWithDiscount') == 0 && ($oProduct->getSalePrice($bWithTax) < $oProduct->getOriginalPrice($bWithTax))) {
                continue;
            }

            $fSubTotalPrice += $oProduct->getSubTotalPrice($bWithTax);
        }

        return $fSubTotalPrice;
    }

    /**
     * get the subtotal once applied the discount
     *
     * @param bool $bWithTax
     *
     * @return float
     */
    public function getSubTotal($bWithTax = false)
    {
        return $this->getSubtotalProducts($bWithTax) + $this->getDiscount($bWithTax);
    }

    /**
     * get the total discount applied to the order
     *
     * @param bool $bWithTax
     *
     * @return float
     */
    public function getDiscount($bWithTax = false)
    {
        $aDiscounts = [];
        if (!empty($this->oCoupon)) {
            $iDiscountType = $this->getDiscountCoupon()
                ->getCouponRule()->discountType;

            if ($iDiscountType == CouponRule::DISCOUNTTYPE_ORDER_FIXED_PRICE) {
                $fDiscountAmount = $this->oCoupon->getCouponRule()->discountAmount;
            } elseif ($iDiscountType == CouponRule::DISCOUNTTYPE_ORDER_PERCENTAGE) {
                $fDiscountAmount = round(($this->oCoupon->getCouponRule()->discountAmount / 100) * $this->getSubtotalProducts(Settings::get('taxIncluded'), true), 2);
            }
        } else {
            $fDiscountAmount = round($this->totalDiscount, 2);
        }
        if ($fDiscountAmount > $this->getSubtotalProducts(Settings::get('taxIncluded'), true)) {
            $fDiscountAmount = $this->getSubtotalProducts(Settings::get('taxIncluded'), true);
        }

        $aSubTotals         = $this->getSubTotalsByTax(false, true);
        $fTotalWithoutTaxes = 0.0;

        foreach ($aSubTotals as $sTaxAmount => $fSubTotal) {
            $fTotalWithoutTaxes += $fSubTotal;
        }

        //calculates the discounts breakdown by type of tax
        foreach ($aSubTotals as $sTaxAmount => $fSubTotal) {
            $aDiscounts[$sTaxAmount] = $fTotalWithoutTaxes ? round($aSubTotals[$sTaxAmount] / $fTotalWithoutTaxes * $fDiscountAmount, 2) : 0.0;
        }

        foreach ($aDiscounts as $sTaxAmount => $fDiscount) {
            if (!$bWithTax && Settings::get('taxIncluded')) {
                $aDiscounts[$sTaxAmount] = TaxManager::subtractTax($fDiscount, $sTaxAmount);
            } elseif ($bWithTax && !Settings::get('taxIncluded')) {
                $aDiscounts[$sTaxAmount] = TaxManager::addTax($fDiscount, $sTaxAmount);
            }
        }

        $fDiscount = 0.0;

        //breakdowns sum to get the total discount
        foreach ($aDiscounts as $sTax => $fDiscAmount) {
            $fDiscount += round($fDiscAmount, 2);
        }

        return round(0 - abs($fDiscount), 2);
    }

    /**
     * get the delivery costs
     *
     * @param bool $bWithTaxes
     *
     * @return float
     */
    public function getDeliveryCosts($bWithTaxes = false)
    {
        $aDeliveryCosts = [];

        $aSubTotals         = $this->getSubTotalsByTax();
        $fTotalWithoutTaxes = 0.0;

        foreach ($aSubTotals as $sTaxAmount => $fSubTotal) {
            $fTotalWithoutTaxes += $fSubTotal;
        }

        //calculates the delivery costs breakdown by type of tax
        foreach ($aSubTotals as $sTaxAmount => $fSubTotal) {
            if ($fTotalWithoutTaxes == 0) {
                $aDeliveryCosts[$sTaxAmount] = 0.00;
            } else {
                $aDeliveryCosts[$sTaxAmount] = round($aSubTotals[$sTaxAmount] / $fTotalWithoutTaxes * $this->getDeliveryPrice(), 2);
            }
        }

        if (!$bWithTaxes) {
            foreach ($aDeliveryCosts as $sTaxAmount => $fDelivery) {
                $aDeliveryCosts[$sTaxAmount] = TaxManager::subtractTax($fDelivery, $sTaxAmount);
            }
        }

        $fDeliveryCosts = 0.0;

        //breakdowns sum to get the total delivery cost
        foreach ($aDeliveryCosts as $sTax => $fAmount) {
            $fDeliveryCosts += round($fAmount, 2);
        }

        return round($fDeliveryCosts, 2);
    }

    /**
     * get the payment costs
     *
     * @param bool $bWithTaxes
     *
     * @return float
     */
    public function getPaymentCosts($bWithTaxes = false)
    {
        $aPaymentCosts = [];

        $aSubTotals         = $this->getSubTotalsByTax();
        $fTotalWithoutTaxes = 0.0;

        foreach ($aSubTotals as $sTaxAmount => $fSubTotal) {
            $fTotalWithoutTaxes += $fSubTotal;
        }

        //calculates the delivery costs breakdown by type of tax
        foreach ($aSubTotals as $sTaxAmount => $fSubTotal) {
            if ($fSubTotal == 0) {
                $aPaymentCosts[$sTaxAmount] = 0.00;
            } else {
                $aPaymentCosts[$sTaxAmount] = round($aSubTotals[$sTaxAmount] / $fTotalWithoutTaxes * $this->getPaymentPrice(), 2);
            }
        }

        if (!$bWithTaxes) {

            foreach ($aPaymentCosts as $sTaxAmount => $fPayment) {
                $aPaymentCosts[$sTaxAmount] = TaxManager::subtractTax($fPayment, $sTaxAmount);
            }
        }

        $fPaymentCosts = 0.0;

        //breakdowns sum to get the total payment cost
        foreach ($aPaymentCosts as $sTax => $fAmount) {
            $fPaymentCosts += round($fAmount, 2);
        }

        return round($fPaymentCosts, 2);
    }

    /**
     * get subtotal amounts grouped by tax type
     *
     * @access public
     *
     * @param bool $bWithTaxes
     *
     * @return array
     */
    private function getSubTotalsByTax($bWithTaxes = false, $bIsDiscount = false)
    {
        $aSubTotals = [];

        foreach ($this->getProducts() as $oOrderItem) {
            if ($bIsDiscount && Settings::get('reducedPriceWithDiscount') == 0 && ($oOrderItem->getSalePrice($bWithTaxes) < $oOrderItem->getOriginalPrice($bWithTaxes))) {
                continue;
            }

            $fTaxPerc = $oOrderItem->getTaxPercentage();

            if (empty($aSubTotals["" . $fTaxPerc])) {
                $aSubTotals["" . $fTaxPerc] = 0.0;
            }

            $aSubTotals["" . $fTaxPerc] += $oOrderItem->getSubTotalPrice($bWithTaxes);
        }

        return $aSubTotals;
    }

    /** generates an MPDF object that represents this order
     */
    public function getPdf()
    {
        $oMPDF = new \Mpdf\Mpdf(['margin_left' => '21', 'margin_right' => '9', 'margin_top' => '60', 'margin_bottom' => '40', 'margin_header' => '9', 'margin_footer' => '9']);
        $oMPDF->SetTitle('Order #' . $this->orderId);
        $oMPDF->use_kwt              = true;
        $oMPDF->shrink_tables_to_fit = 1;
        $oMPDF->setAutoTopMargin     = 'stretch';
        $sStylesheet                 = file_get_contents(DOCUMENT_ROOT . getAdminCss('order_pdf', 'orders'));
        $oMPDF->WriteHTML($sStylesheet, 1);

        ob_start();
        include_once getAdminSnippet('order_pdf_header', 'orders');
        $sHtml = ob_get_contents();
        ob_end_clean();
        $oMPDF->SetHTMLHeader($sHtml);

        ob_start();
        include_once getAdminSnippet('order_pdf_body', 'orders');
        $sHtml = ob_get_contents();
        ob_end_clean();
        $oMPDF->WriteHTML($sHtml);
        $sOrderFooter = file_get_contents(getAdminSnippet('order_pdf_footer', 'orders'));
        $oMPDF->SetHTMLFooter($sOrderFooter);

        return $oMPDF;
    }

    /**
     * send order notifications by template name
     *
     * @param string $sTemplateNameCustomer
     * @param string $sTemplateNameClient
     * @param bool   $bNotifyCustomer
     * @param bool   $bNotifyClient
     * @param string $sNotificationInfo
     */
    private function sendStatusNotifications($sTemplateNameCustomer, $sTemplateNameClient, $bNotifyCustomer = false, $bNotifyClient = false, $sNotificationInfo = '', $sBCCClient = '')
    {

        $this->oStatus = null; // reset status to fix refrence problems and wrong status texts
        // send notifications if needed
        if ($bNotifyCustomer && $sTemplateNameCustomer && $this->email) {
            $oTemplate = TemplateManager::getTemplateByName($sTemplateNameCustomer, Locales::language());

            // check if template exists
            if (empty($oTemplate)) {
                Debug::logError('', 'Template bestaat niet: `bestelling opslaan` (' . $sTemplateNameCustomer . ')', __FILE__, __LINE__, '', Debug::LOG_IN_EMAIL);
            }
            $this->oStatus = null; // reset status to fix refrence problems and wrong status texts
            $oTemplate->replaceVariables($this, ['[special_comment]' => $sNotificationInfo]);
            MailManager::sendMail($this->email, $oTemplate->getSubject(), $oTemplate->getTemplate(), null, null, null, $sBCCClient);
        }

        if ($bNotifyClient && $sTemplateNameClient && Settings::get('defaultEmailOrders')) {
            $oTemplate = TemplateManager::getTemplateByName($sTemplateNameClient, Locales::language());

            // check if template exists
            if (empty($oTemplate)) {
                Debug::logError('', 'Template bestaat niet: `bestelling opslaan` (' . $sTemplateNameClient . ')', __FILE__, __LINE__, '', Debug::LOG_IN_EMAIL);
            }
            $oTemplate->replaceVariables($this, ['[special_comment]' => $sNotificationInfo]);
            MailManager::sendMail(Settings::get('defaultEmailOrders'), $oTemplate->getSubject(), $oTemplate->getTemplate());
        }
    }

}

?>