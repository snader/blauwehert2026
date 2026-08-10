<?php

class OrderStatus extends Model
{
    # list of statuses. Do not edit the status numbers afterwards

    const STATUS_NEW                  = 10; // Order has been made
    const STATUS_AWAITING_PAYMENT     = 20; // Order is awaiting payment
    const STATUS_PAYED                = 30; // Order has been payed
    const STATUS_READY_FOR_PROCESSING = 40; // Ready for processing
    const STATUS_PROCESSING           = 50; // Order is being processed, waiting for stock, packing etc
    const STATUS_PROCESSED            = 60; // Order is processed
    const STATUS_DISPATCHED           = 70; // Order is dispatched
    const STATUS_CANCELED             = 80; // Order is canceled

    public  $orderStatusId = null;
    public  $status; // statuses are numeric and defined in the OrderStatusManager
    public  $timestamp     = null;
    public  $orderId;
    public  $createdBy    = null;
    private $sLabel        = null;

    /**
     * validate object
     */
    public function validate()
    {
        if (!is_numeric($this->status)) {
            $this->setPropInvalid('status');
        }
        if (!is_numeric($this->orderId)) {
            $this->setPropInvalid('orderId');
        }
    }

    /**
     * get the OrderStatus' label
     *
     * @return string
     */
    public function getLabel()
    {
        if ($this->sLabel === null) {
            $this->sLabel = OrderStatusManager::getLabelByStatus($this->status);
        }

        return $this->sLabel;
    }


    public function getCreatedBy()
    {
        if($this->createdBy != 'global_system'){
            return $this->createdBy;
        }else {
            return sysTranslations::get('global_system');
        }
    }
}

?>