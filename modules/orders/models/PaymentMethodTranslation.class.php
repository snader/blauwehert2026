<?php

class PaymentMethodTranslation extends Model
{

    public $paymentMethodTranslationId = null;
    public $paymentMethodId            = null;
    public $languageId                 = null;
    public $name                       = null;
    public $redirectPage               = '/winkelwagen/bestelling-geplaatst';

    /**
     * validate object
     */
    public function validate()
    {
        if (!is_numeric($this->paymentMethodId)) {
            $this->setPropInvalid('paymentMethodId');
        }
        if (!is_numeric($this->languageId)) {
            $this->setPropInvalid('languageId');
        }
        if (empty($this->name)) {
            $this->setPropInvalid('name');
        }
        if (empty($this->redirectPage)) {
            $this->setPropInvalid('redirectPage');
        }
    }

}