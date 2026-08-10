<?php

class OrderPayment extends Model
{
    # list of statuses. Do not edit the status numbers afterwards

    const STATUS_AWAITING_PAYMENT = 10; // awaiting payment
    const STATUS_FAILED           = 20; // payment has failed
    const STATUS_ACCEPTED         = 30; // payment is accepted succesfully
    const STATUS_OTHER            = 40; // something else, see externalStatus property
    const STATUS_CANCELED         = 50; // payment has been canceled by client

    public  $orderPaymentId           = null;
    public  $price;
    public  $status;
    public  $externalStatus           = null;
    public  $externalPaymentReference = null;
    public  $response                 = null;
    public  $created                  = null;
    public  $modified                 = null;
    public  $orderId;
    public  $paymentMethodId          = 1;
    public  $paymentMethodName        = null;
    private $sStatusLabel             = null;
    private $oPaymentMethod           = null; // association with PaymentMethod class
    private $oOrder                   = null; // association with Order class

    /**
     * validate object
     */

    public function validate()
    {
        if (!is_numeric($this->price) || $this->price <= 0) {
            $this->setPropInvalid('price');
        }
        if (!is_numeric($this->status)) {
            $this->setPropInvalid('status');
        }
        if (!is_numeric($this->orderId)) {
            $this->setPropInvalid('orderId');
        }
        if (!is_numeric($this->paymentMethodId)) {
            $this->setPropInvalid('paymentMethodId');
        }
    }

    /**
     * get the statuslabel
     *
     * @return string
     */
    public function getStatusLabel()
    {
        if ($this->sStatusLabel === null) {
            $this->sStatusLabel = OrderPaymentManager::getLabelByStatus($this->status);
        }

        return $this->sStatusLabel;
    }

    /**
     * get the related PaymentMethod
     *
     * @return PaymentMethod
     */
    public function getPaymentMethod()
    {
        if ($this->oPaymentMethod === null) {
            $this->oPaymentMethod = PaymentMethodManager::getPaymentMethodById($this->paymentMethodId);
        }

        return $this->oPaymentMethod;
    }

    /**
     * get the related Order
     *
     * @return Order
     */
    public function getOrder()
    {
        if ($this->oOrder === null) {
            $this->oOrder = OrderManager::getOrderById($this->orderId);
        }

        return $this->oOrder;
    }

    /**
     * process new status
     *
     * @param int $iStatus
     */
    public function processStatus($iStatus)
    {
        $this->status = $iStatus;
        OrderPaymentManager::savePayment($this);
        $this->getOrder()
            ->processPaymentStatus($this->status);
    }

    /**
     * return secret payment ID to get payments for displaying info
     */
    public function getPID()
    {
        return md5($this->orderId . $this->created);
    }

}

?>