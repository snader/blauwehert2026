<?php

class DeliveryMethodTranslation extends Model
{

    public $deliveryMethodTranslationId = null;
    public $deliveryMethodId            = null;
    public $languageId                  = null;
    public $name                        = null;

    /**
     * validate object
     */
    public function validate()
    {
        if (!is_numeric($this->deliveryMethodId)) {
            $this->setPropInvalid('deliveryMethodId');
        }
        if (!is_numeric($this->languageId)) {
            $this->setPropInvalid('languageId');
        }
        if (empty($this->name)) {
            $this->setPropInvalid('name');
        }
    }

}